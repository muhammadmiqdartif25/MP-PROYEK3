<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>@yield('title', 'Karhutla Care')</title>
</head>
<body>
    <nav class="site-nav">
        <div class="site-nav__inner">
            <a href="{{ auth()->check() ? route('home.index') : route('welcome.index') }}" class="site-nav__brand">
                Karhutla Care
            </a>

            @auth
                <nav class="main-nav">
                    <a href="{{ route('home.index') }}" class="nav-link">Beranda</a>
                    <a href="{{ route('reports.index') }}" class="nav-link">Laporan</a>
                    @can('manage-areas')
                        <a href="{{ route('area.index') }}" class="nav-link">Wilayah</a>
                    @endcan
                </nav>

                <div class="user-menu">
                    <span>{{ auth()->user()->full_name }}</span>
                    <span class="badge">{{ auth()->user()->role }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-link">Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login.index') }}" class="btn-link">Login</a>
            @endauth
        </div>
    </nav>

    <main class="site-main">
        @if (session('status'))
            <p class="alert" role="status">{{ session('status') }}</p>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; 2026 Karhutla Care
    </footer>
</body>
</html>
