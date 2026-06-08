# Roomor Recommendation API

FastAPI subsystem for Hybrid AHP-SAW ranking. Laravel remains the main system and sends kost alternatives to this service through HTTP.

## Local Setup

```bash
cd recommendation_api
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
uvicorn main:app --reload --host 127.0.0.1 --port 8001
```

Open:

```text
http://127.0.0.1:8001/docs
```

Laravel should point to:

```env
RECOMMENDATION_API_URL=http://127.0.0.1:8001
```

## Demo With Ngrok

Run Laravel and FastAPI locally:

```bash
php artisan serve --port=8000
uvicorn main:app --reload --host 127.0.0.1 --port 8001
```

Expose only Laravel:

```bash
ngrok http 8000
```

The public ngrok URL opens Laravel. Laravel calls FastAPI locally through `RECOMMENDATION_API_URL`, so FastAPI does not need its own ngrok tunnel for normal demos.
