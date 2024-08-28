<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>@yield('title', 'Gestión de Gastos Uber')</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>@yield('header', 'Gestión de Gastos Uber')</h1>
        </header>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
