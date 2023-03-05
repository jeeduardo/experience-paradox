<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Magento - Laravel - admin</title>

        <link rel="preload" as="font" href="{{ asset('fonts/Luma-Icons.woff2') }}" type="font/woff2" crossorigin />
        @vite('resources/css/app.css')
    </head>
    <body>
        <main class="content">
            @yield('content')
        </main>
    </body>
</html>