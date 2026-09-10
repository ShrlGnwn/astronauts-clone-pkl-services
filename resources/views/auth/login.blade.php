<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — ASTRO</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <form class="box" method="POST" action="{{ url('/login') }}">
        @csrf
        <h1>Login Dashboard</h1>
        <p class="sub">Khusus user dengan access <b>admin</b>.</p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Masuk</button>

        <p class="demo">Demo: admin@demo.com / password</p>
    </form>
</body>
</html>
