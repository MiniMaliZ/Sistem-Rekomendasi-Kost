<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use illuminate\support\Facades\DB;
use Illuminate\support\Collection;

class ListkostController extends Controller
{
    /**
     * Route model binding customization
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return Kost::where('id_kost', $value)->first();
    }
    /**
     * Display a listing of kost with filters and pagination.
     */
    public function index(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Initialize query
        $query = Kost::with('fotoKost', 'feedback');;

        // SEARCH - Filter by name, location, or other searchable fields
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_kost', 'like', "%{$search}%")
                  ->orWhere('tempat_terdekat', 'like', "%{$search}%");
            });
        }

        // FILTER - Tipe Kost (Putra, Putri, Campur)
        if ($request->filled('tipe_kos')) {
            $tipeKos = $request->input('tipe_kos');
            if (is_array($tipeKos)) {
                $query->whereIn('tipe_kos', $tipeKos);
            } else {
                $query->where('tipe_kos', $tipeKos);
            }
        }

        // FILTER - Price Range
        if ($request->filled('harga_min')) {
            $hargaMin = $request->input('harga_min');
            $query->where('harga', '>=', $hargaMin);
        }

        if ($request->filled('harga_max')) {
            $hargaMax = $request->input('harga_max');
            $query->where('harga', '<=', $hargaMax);
        }

        // FILTER - Fasilitas (if applicable - adjust based on your data structure)
        if ($request->filled('fasilitas')) {
            $fasilitas = $request->input('fasilitas');
            if (is_array($fasilitas)) {
                foreach ($fasilitas as $facility) {
                    $query->where(function ($q) use ($facility) {
                        $q->where('fasilitas_kamar', 'like', "%{$facility}%")
                          ->orWhere('fasilitas_kamar_mandi', 'like', "%{$facility}%")
                          ->orWhere('fasilitas_umum', 'like', "%{$facility}%")
                          ->orWhere('fasilitas_parkir', 'like', "%{$facility}%");

                    });
                }
            }
        }

        // SORTING
        $sortBy = $request->input('sort', 'newest'); // Default sorting: newest
        
        switch ($sortBy) {
            case 'price_asc':
                // Harga terendah
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                // Harga tertinggi
                $query->orderBy('harga', 'desc');
                break;
            case 'name':
                // Nama A-Z
                $query->orderBy('nama_kost', 'asc');
                break;
            case 'newest':
            default:
                // Terbaru
                $query->orderBy('id_kost', 'desc');
                break;
        }

        // PAGINATION
        $perPage = $request->input('per_page', 18); // Default 6 items per page
        $kosts = $query->paginate($perPage);

        $favoriteKostIds = [];

        if ($user) {
            $favoriteKostIds = Favorit::where('id_user', $user->id_user)
                ->pluck('id_kost')
                ->toArray();
        }

        $kosts->getCollection()->transform(function ($kost) use ($favoriteKostIds) {
            return $this->prepareKost($kost, $favoriteKostIds);
        });

        // Get filter options for dropdown/filter UI
        $filterOptions = [
            'tipe_kos_options' => ['kos putra' => 'Putra', 'kos putri' => 'Putri', 'kos campur' => 'Campur'],
            'harga_ranges' => [
                'Dibawah 500rb' => ['min' => 0, 'max' => 500000],
                '500rb - 1jt' => ['min' => 500000, 'max' => 1000000],
                '1jt - 1.5jt' => ['min' => 1000000, 'max' => 1500000],
                '1.5jt - 2jt' => ['min' => 1500000, 'max' => 2000000],
                'Diatas 2jt' => ['min' => 2000000, 'max' => 999999999],
            ],
        ];

        // Return view with data
        return view('user.listkost', [
            'kosts' => $kosts,
            'filterOptions' => $filterOptions,
            'currentSort' => $sortBy,
            'currentSearch' => $request->input('search'),
            'currentFilters' => [
                'tipe_kos' => $request->input('tipe_kos'),
                'harga_min' => $request->input('harga_min'),
                'harga_max' => $request->input('harga_max'),
                'fasilitas' => $request->input('fasilitas'),
            ],
        ]);
    }

    private function formatHarga(float $harga): string
    {
        return 'Rp. ' . number_format($harga, 0, ',', '.');
    }
    
    private function fasilitasTags(array $fasilitas): array
    {
        $visible = array_slice($fasilitas, 0, 3);
        $extra   = count($fasilitas) - 3;
        if ($extra > 0) {
            $visible[] = "{$extra}+";
        }
        return $visible;
    }

    private function parseFasilitas(Kost $kost): array
    {
        $cols = [
            $kost->fasilitas_umum,
            $kost->fasilitas_kamar,
            $kost->fasilitas_kamar_mandi,
            $kost->fasilitas_parkir,
        ];

        $all = [];
        foreach ($cols as $col) {
            if (!empty($col)) {
                foreach (explode(',', $col) as $item) {
                    $trimmed = trim($item);
                    if ($trimmed !== '') {
                        $all[] = $trimmed;
                    }
                }
            }
        }

        return array_values(array_unique($all));
    }

private function prepareKost(Kost $kost, array $favoritIds): array
    {
        $fasilitas = $this->parseFasilitas($kost);

        return [
            'id'             => $kost->id_kost,
            'nama'           => $kost->nama_kost,
            'tipe'           => strtoupper(str_replace('Kos ', '', $kost->tipe_kos)),
            'kota'           => 'Malang',
            'provinsi'       => 'Jawa Timur',
            'harga'          => (float) $kost->harga,
            'harga_format'   => $this->formatHarga((float) $kost->harga),
            'rating'         => $this->getRating($kost),
            'ulasan'         => $kost->feedback->count(),
            'foto'           => $kost->fotoKost?->foto_bangunan_url ?? asset('images/no-image.jpg'),
            'lokasi'         => $this->getLokasi($kost->nama_kost),
            'fasilitas'      => $fasilitas,
            'fasilitas_tags' => $this->fasilitasTags($fasilitas),
            'is_baru'        => false,
            'is_favorit'     => in_array($kost->id_kost, $favoritIds),
        ];
    }
    
    

    private function kecamatanMap(): array
    {
    return [
        'Lowokwaru'     => 'kota',
        'Blimbing'      => 'kota',
        'Klojen'        => 'kota',
        'Sukun'         => 'kota',
        'Kedungkandang' => 'kota',
        'Karang Ploso'  => 'kab',
        'Dau'           => 'kab',
    ];
    }

private function getLokasi(string $namaKost): string
{
    foreach ($this->kecamatanMap() as $kecamatan => $jenis) {
        if (stripos($namaKost, $kecamatan) !== false) {
            $wilayah = $jenis === 'kota'
                ? 'Kota Malang'
                : 'Kab. Malang';

            return "{$kecamatan}, {$wilayah}";
        }
    }

    return 'Malang';
}

    private function getRating(Kost $kost): float
    {
        if ($kost->feedback->isEmpty()) {
            return 0.0;
        }

        return round($kost->feedback->avg('rating'), 1);
    }
    /**
     * Filter helper to validate and handle harga_range parameter
     */
    private function parseHargaRange(Request $request)
    {
        if ($request->filled('harga_range')) {
            $range = $request->input('harga_range');
            if (str_contains($range, '-')) {
                [$min, $max] = explode('-', $range);
                return ['min' => (int)$min, 'max' => (int)$max];
            }
        }
        return null;
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kost $kost)
    {
        // Load kost with its relationships
        $kost->load(['kostKriteria', 'favorit', 'riwayat', 'feedback','fotoKost']);

        // Get authenticated user
        $user = Auth::user();

        // Check if current kost is in user's favorites
        $isFavorite = false;
        if ($user) {
            $isFavorite = Favorit::where('id_user', $user->id_user)
                ->where('id_kost', $kost->id_kost)
                ->exists();
        }

        return view('user_listkost_detail', [
            'kost' => $kost,
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kost $kost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kost $kost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kost $kost)
    {
        //
    }
}