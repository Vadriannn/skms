<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'SKMS' }}</title>
</head>
<body>

    <div>
        <h2>SKMS UBAYA</h2>
        <p>
            <a href="{{ route('categories.index') }}">Manajemen Kategori</a> | 
            <a href="#">Input FAQ Baru</a>
        </p>
        @auth
            <p>
                Halo, <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->role }})
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </p>
        @else
            <p><a href="{{ route('login') }}">Login</a></p>
        @endauth
        <hr>
    </div>

    <div>
        @if(session('success'))
            <p><strong>{{ session('success') }}</strong></p>
        @endif

        @if(session('error'))
            <p style="color: red;"><strong>{{ session('error') }}</strong></p>
        @endif

        @yield('content')
    </div>

    <hr>
    <p><small>&copy; 2026 Program Studi Sistem Informasi UBAYA</small></p>

</body>
</html>
