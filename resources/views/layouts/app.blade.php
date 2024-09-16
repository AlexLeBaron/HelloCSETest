<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Mon application')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <h1>HelloCSE Test</h1>
        <nav>
            <ul>
                <li><a href="{{ route('profiles.index') }}">Profils actifs</a></li>
                <li><a href="/admin/dashboard">Dashboard Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')  <!-- Content specific to each view will be injected here -->
    </main>

    <footer>
        <p>&copy; 2024 HelloCSE Test</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
