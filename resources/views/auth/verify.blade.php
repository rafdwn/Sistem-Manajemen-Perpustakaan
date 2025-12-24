<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #cce0ff, #a8ffdd);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            width: 380px;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
<div class="box">
    <h4>Verifikasi Password</h4>
    <p>Masukkan nama akun dan password yang dikirimkan ke email Anda.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.verify.process') }}">
        @csrf

        <div class="form-group">
            <label>Nama Akun</label>
            <input type="text" name="username" class="form-control"
                placeholder="Masukkan Nama Akun" required>
        </div>

        <div class="form-group">
            <label>Masukkan Password</label>
            <input type="password" name="password" class="form-control"
                placeholder="Masukkan Password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            Kembali Login
        </button>

        <div class="text-center mt-3">
            <a href="/login">Kembali ke halaman login</a>
        </div>
    </form>
</div>
</body>
</html>
