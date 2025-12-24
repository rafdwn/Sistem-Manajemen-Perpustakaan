<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #cce0ff, #a8ffdd);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-card {
            width: 380px;
            padding: 30px;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }

        .title {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
            height: 45px;
            font-weight: bold;
        }

        .back-link {
            margin-top: 10px;
            display: block;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="forgot-card">

    <h4 class="title">Lupa Password</h4>
    <p>Masukkan email Anda untuk menerima username dan password baru.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('password.send') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>

        <a href="/login" class="back-link">Kembali ke halaman login</a>
    </form>
</div>

</body>
</html>
