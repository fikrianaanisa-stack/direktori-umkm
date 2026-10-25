<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Direktori UMKM Kota Ambon</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 8px;
            color: #1e293b;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        input:focus {
            outline: 2px solid #2563eb;
            border-color: transparent;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #2563eb;
            text-decoration: none;
        }

        .login-text {
            text-align: center;
            font-size: 14px;
            color: #64748b;
            margin-top: 22px;
        }

        .login-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="register-card">
        <h1>Daftar Akun</h1>
        <p class="subtitle">
            Direktori UMKM Kota Ambon
        </p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap"
                autocomplete="name"
                maxlength="255"
                required
                autofocus
            >

            <label for="email">Alamat Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan alamat email"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <label for="password_confirmation">
                Konfirmasi Password
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button type="submit">Daftar Sekarang</button>
        </form>

        <p class="login-text">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

        <a href="{{ route('home') }}" class="back-link">
            Kembali ke Halaman Utama
        </a>
    </div>
</body>
</html>
