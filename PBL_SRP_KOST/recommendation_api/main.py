from __future__ import annotations

import re
from typing import Any, Literal

from fastapi import FastAPI
from pydantic import BaseModel, Field


app = FastAPI(
    title="Roomor Recommendation API",
    description="Hybrid AHP-SAW recommendation subsystem for kost ranking.",
    version="1.0.0",
)

RI_TABLE = {
    1: 0.00,
    2: 0.00,
    3: 0.58,
    4: 0.90,
    5: 1.12,
    6: 1.24,
    7: 1.32,
    8: 1.41,
    9: 1.45,
    10: 1.49,
}

CRITERIA = [
    {"key": "harga", "name": "Harga", "type": "cost"},
    {"key": "jarak_kampus", "name": "Jarak Kampus", "type": "cost"},
    {"key": "luas_kamar", "name": "Luas Kamar", "type": "benefit"},
    {"key": "kecocokan_fasilitas", "name": "Kecocokan Fasilitas", "type": "benefit"},
    {"key": "keamanan_cctv", "name": "CCTV", "type": "benefit"},
    {"key": "listrik_termasuk", "name": "Listrik Termasuk", "type": "benefit"},
]

FACILITY_OPTIONS = {
    "wifi": {"label": "WiFi / Internet", "keywords": ["wifi", "wi-fi", "internet"]},
    "ac": {"label": "AC", "keywords": ["ac"]},
    "kamar_mandi_dalam": {
        "label": "Kamar mandi dalam",
        "keywords": ["k. mandi dalam", "kamar mandi dalam"],
    },
    "parkir_motor": {
        "label": "Parkir motor",
        "keywords": ["parkir motor", "motor & sepeda"],
    },
    "parkir_mobil": {"label": "Parkir mobil", "keywords": ["parkir mobil"]},
    "dapur": {"label": "Dapur", "keywords": ["dapur"]},
    "laundry": {"label": "Laundry / mesin cuci", "keywords": ["laundry", "mesin cuci"]},
    "cctv": {"label": "CCTV", "keywords": ["cctv"]},
    "kulkas": {"label": "Kulkas", "keywords": ["kulkas"]},
    "kasur": {"label": "Kasur", "keywords": ["kasur"]},
    "lemari": {"label": "Lemari", "keywords": ["lemari", "storage"]},
    "akses_24_jam": {"label": "Akses 24 jam", "keywords": ["akses 24 jam"]},
}


class KostAlternative(BaseModel):
    id_kost: int
    nama_kost: str
    harga: float = 0
    tipe_kos: str = ""
    sepesifikasi_tipe_kamar: str | None = None
    fasilitas_kamar: str | None = None
    fasilitas_kamar_mandi: str | None = None
    fasilitas_umum: str | None = None
    fasilitas_parkir: str | None = None
    tempat_terdekat: str | None = None
    peraturan_kos: str | None = None
    link_original: str | None = None


class RecommendationPreferences(BaseModel):
    budget_max: str | float | int | None = None
    max_distance: str | float | int | None = None
    tipe_kos: str | None = ""
    facilities: list[str] = Field(default_factory=list)
    require_all_facilities: bool = False


class RecommendationRequest(BaseModel):
    alternatives: list[KostAlternative]
    preferences: RecommendationPreferences = Field(default_factory=RecommendationPreferences)
    scenario: Literal["default", "hemat", "fasilitas"] = "default"
    limit: int = Field(default=10, ge=1, le=100)


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok", "service": "hybrid-ahp-saw"}


@app.post("/recommend")
def recommend(request: RecommendationRequest) -> dict[str, Any]:
    ahp = calculate_ahp(request.scenario)
    selected_facilities = list(request.preferences.facilities)
    selected_count = len(selected_facilities)
    budget_max = to_float(request.preferences.budget_max)
    max_distance = to_float(request.preferences.max_distance)
    tipe_kos = (request.preferences.tipe_kos or "").strip()

    rows = [
        build_decision_row(alternative, selected_facilities)
        for alternative in request.alternatives
    ]

    filtered_rows = []
    for row in rows:
        if budget_max > 0 and row["harga"] > budget_max:
            continue

        if max_distance > 0 and (
            row["jarak_kampus_asli"] is None or row["jarak_kampus_asli"] > max_distance
        ):
            continue

        if tipe_kos and row["tipe_kos"] != tipe_kos:
            continue

        if (
            request.preferences.require_all_facilities
            and selected_count > 0
            and row["matched_facility_count"] < selected_count
        ):
            continue

        filtered_rows.append(row)

    if not filtered_rows:
        return {
            "ahp": ahp,
            "rows": [],
            "summary": {
                "total_alternatives": len(request.alternatives),
                "filtered_alternatives": 0,
                "max_score": 0.0,
                "avg_score": 0.0,
            },
        }

    stats = criteria_stats(filtered_rows)
    weight_map = ahp["weight_map"]
    ranked_rows = []

    for row in filtered_rows:
        normalized = {}
        contributions = {}
        score = 0.0

        for criterion in CRITERIA:
            key = criterion["key"]
            value = float(row[key])

            if criterion["type"] == "benefit":
                normal_value = value / stats[key]["max"] if stats[key]["max"] > 0 else 0.0
            else:
                normal_value = stats[key]["min"] / value if value > 0 else 0.0

            weighted_value = normal_value * weight_map.get(key, 0.0)
            normalized[key] = normal_value
            contributions[key] = weighted_value
            score += weighted_value

        row["normalized"] = normalized
        row["contributions"] = contributions
        row["score"] = score
        ranked_rows.append(row)

    ranked_rows.sort(key=lambda row: row["score"], reverse=True)
    max_score = ranked_rows[0]["score"] if ranked_rows else 0.0
    limited_rows = ranked_rows[: request.limit]

    for index, row in enumerate(limited_rows):
        row["rank"] = index + 1
        row["score_percent"] = (row["score"] / max_score) * 100 if max_score > 0 else 0.0

    return {
        "ahp": ahp,
        "rows": limited_rows,
        "summary": {
            "total_alternatives": len(request.alternatives),
            "filtered_alternatives": len(filtered_rows),
            "max_score": max_score,
            "avg_score": sum(row["score"] for row in limited_rows) / len(limited_rows),
        },
    }


def calculate_ahp(scenario: str = "default") -> dict[str, Any]:
    matrix = pairwise_matrix(scenario)
    size = len(matrix)
    column_sums = [sum(row[index] for row in matrix) for index in range(size)]
    normalized_matrix = []
    weights = []

    for row in matrix:
        normalized_row = [
            value / column_sums[index] if column_sums[index] > 0 else 0.0
            for index, value in enumerate(row)
        ]
        normalized_matrix.append(normalized_row)
        weights.append(sum(normalized_row) / size)

    weighted_sum_vector = [
        sum(value * weights[column_index] for column_index, value in enumerate(row))
        for row in matrix
    ]
    lambda_values = [
        weighted_sum_vector[index] / weights[index] if weights[index] > 0 else 0.0
        for index in range(size)
    ]
    lambda_max = sum(lambda_values) / max(len(lambda_values), 1)
    ci = (lambda_max - size) / (size - 1) if size > 1 else 0.0
    ri = RI_TABLE.get(size, 1.49)
    cr = ci / ri if ri > 0 else 0.0
    weight_map = {
        criterion["key"]: weights[index] if index < len(weights) else 0.0
        for index, criterion in enumerate(CRITERIA)
    }

    return {
        "criteria": CRITERIA,
        "matrix": matrix,
        "normalized_matrix": normalized_matrix,
        "weights": weights,
        "weight_map": weight_map,
        "lambda_max": lambda_max,
        "ci": ci,
        "ri": ri,
        "cr": cr,
        "is_consistent": cr < 0.1,
    }


def pairwise_matrix(scenario: str) -> list[list[float]]:
    if scenario == "hemat":
        return [
            [1, 3, 5, 5, 7, 9],
            [1 / 3, 1, 3, 2, 5, 7],
            [1 / 5, 1 / 3, 1, 1 / 2, 2, 3],
            [1 / 5, 1 / 2, 2, 1, 3, 4],
            [1 / 7, 1 / 5, 1 / 2, 1 / 3, 1, 3],
            [1 / 9, 1 / 7, 1 / 3, 1 / 4, 1 / 3, 1],
        ]

    if scenario == "fasilitas":
        return [
            [1, 1 / 3, 2, 1 / 2, 3, 5],
            [3, 1, 4, 2, 5, 7],
            [1 / 2, 1 / 4, 1, 1 / 3, 2, 3],
            [2, 1 / 2, 3, 1, 4, 5],
            [1 / 3, 1 / 5, 1 / 2, 1 / 4, 1, 3],
            [1 / 5, 1 / 7, 1 / 3, 1 / 5, 1 / 3, 1],
        ]

    return [
        [1, 1, 3, 2, 5, 7],
        [1, 1, 3, 2, 5, 7],
        [1 / 3, 1 / 3, 1, 1 / 2, 2, 4],
        [1 / 2, 1 / 2, 2, 1, 3, 5],
        [1 / 5, 1 / 5, 1 / 2, 1 / 3, 1, 3],
        [1 / 7, 1 / 7, 1 / 4, 1 / 5, 1 / 3, 1],
    ]


def build_decision_row(alternative: KostAlternative, selected_facilities: list[str]) -> dict[str, Any]:
    distance = parse_campus_distance_km(alternative.tempat_terdekat or "")
    matched_facilities = match_facilities(alternative, selected_facilities)
    all_facility_matches = match_facilities(alternative, list(FACILITY_OPTIONS.keys()))
    selected_count = len(selected_facilities)
    searchable = searchable_text(alternative)

    return {
        "id_kost": alternative.id_kost,
        "nama_kost": alternative.nama_kost,
        "harga": float(alternative.harga),
        "tipe_kos": alternative.tipe_kos,
        "sepesifikasi_tipe_kamar": alternative.sepesifikasi_tipe_kamar,
        "jarak_kampus": distance if distance is not None else 999.0,
        "jarak_kampus_asli": distance,
        "luas_kamar": parse_room_size(alternative.sepesifikasi_tipe_kamar or ""),
        "kecocokan_fasilitas": (
            len(matched_facilities) / selected_count
            if selected_count > 0
            else max(len(all_facility_matches), 0)
        ),
        "keamanan_cctv": 1.0 if text_contains(searchable, ["cctv"]) else 0.0,
        "listrik_termasuk": 1.0
        if includes_electricity(alternative.sepesifikasi_tipe_kamar or "")
        else 0.0,
        "total_facilities": len(all_facility_matches),
        "matched_facilities": matched_facilities,
        "matched_facility_count": len(matched_facilities),
    }


def criteria_stats(rows: list[dict[str, Any]]) -> dict[str, dict[str, float]]:
    stats = {}
    for criterion in CRITERIA:
        key = criterion["key"]
        values = [float(row[key]) for row in rows if float(row[key]) > 0]
        stats[key] = {
            "min": min(values) if values else 0.0,
            "max": max(values) if values else 0.0,
        }

    return stats


def match_facilities(alternative: KostAlternative, keys: list[str]) -> dict[str, str]:
    text = searchable_text(alternative)
    matches = {}

    for key in keys:
        option = FACILITY_OPTIONS.get(key)
        if option and text_contains(text, option["keywords"]):
            matches[key] = option["label"]

    return matches


def searchable_text(alternative: KostAlternative) -> str:
    return " ".join(
        str(value or "")
        for value in [
            alternative.sepesifikasi_tipe_kamar,
            alternative.fasilitas_kamar,
            alternative.fasilitas_kamar_mandi,
            alternative.fasilitas_umum,
            alternative.fasilitas_parkir,
            alternative.tempat_terdekat,
            alternative.peraturan_kos,
        ]
    ).lower()


def text_contains(text: str, keywords: list[str]) -> bool:
    return any(keyword.lower() in text for keyword in keywords)


def parse_room_size(value: str) -> float:
    match = re.search(r"(\d+(?:[.,]\d+)?)\s*x\s*(\d+(?:[.,]\d+)?)", value, re.IGNORECASE)
    if not match:
        return 0.0

    return float(match.group(1).replace(",", ".")) * float(match.group(2).replace(",", "."))


def parse_campus_distance_km(value: str) -> float | None:
    distances = []
    for segment in [segment.strip() for segment in value.split("|")]:
        lower_segment = segment.lower()
        is_campus = any(
            keyword in lower_segment
            for keyword in [
                "universitas",
                "politeknik",
                "kampus",
                "institut",
                "sekolah tinggi",
                "akademi",
            ]
        )

        if not is_campus:
            continue

        match = re.search(r"\(([\d.,]+)\s*(km|m)\)", segment, re.IGNORECASE)
        if match:
            distance = float(match.group(1).replace(",", "."))
            distances.append(distance / 1000 if match.group(2).lower() == "m" else distance)

    return min(distances) if distances else None


def includes_electricity(value: str) -> bool:
    lower_value = value.lower()
    return "termasuk listrik" in lower_value and "tidak termasuk listrik" not in lower_value


def to_float(value: Any) -> float:
    clean = re.sub(r"[^0-9,.]", "", str(value or ""))
    if clean == "":
        return 0.0

    if "," in clean and "." in clean:
        clean = clean.replace(".", "").replace(",", ".")
    elif clean.count(".") > 1 or re.search(r"\.\d{3}$", clean):
        clean = clean.replace(".", "")
    elif "," in clean and "." not in clean:
        clean = clean.replace(",", ".")
    else:
        clean = clean.replace(",", "")

    return float(clean)
