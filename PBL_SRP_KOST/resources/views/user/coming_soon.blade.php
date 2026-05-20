<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $halaman }} - KostApp</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fefcf8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #1e1e1e;
            gap: 1rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        p {
            color: #696969;
            font-size: 0.95rem;
        }

        a {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.6rem 1.5rem;
            border: 1px solid #3f2419;
            border-radius: 9999px;
            color: #3f2419;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }

        a:hover {
            background: #f3f4f6;
        }
    </style>
</head>

<body>
    <h1>{{ $halaman }}</h1>
    <p>Halaman ini akan aktif setelah database selesai dikerjakan.</p>
    <a href="{{ route('user.home') }}">← Kembali ke Home</a>
</body>

</html>
