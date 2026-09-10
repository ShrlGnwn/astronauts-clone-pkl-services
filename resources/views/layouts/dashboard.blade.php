<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — ASTRO Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">ASTRO <span>Admin</span></div>
        <nav>
            <a href="{{ route('dashboard.home') }}" class="{{ request()->routeIs('dashboard.home') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('dashboard.orders') }}" class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}">Order</a>
            <a href="{{ route('dashboard.categories') }}" class="{{ request()->routeIs('dashboard.categories*') ? 'active' : '' }}">Kategori (contoh CRUD)</a>
        </nav>
    </aside>

    <div class="main">
        <div class="topbar">
            <span></span>
            <div class="user">
                <span>{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </div>
        </div>

        <main class="content">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
