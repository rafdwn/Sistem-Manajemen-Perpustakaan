<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pustakawan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #cce0ff, #a8ffdd);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 380px;
            padding: 30px;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }

        .title {
            text-align: left;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .btn-primary {
            width: 100%;
            height: 45px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="login-card">
    <h4 class="title">Login Pustakawan</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                placeholder="Masukkan username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control"
                placeholder="Masukkan password" required>
        </div>

        <div class="text-right mb-3">
            <a href="{{ route('password.forgot') }}" style="font-size: 14px;">Lupa Password?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            Login
        </button>
    </form>
</div>

</body>
</html>
