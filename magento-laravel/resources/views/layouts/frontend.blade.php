<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Magento - Laravel - Vuejs test</title>

        <link rel="preload" as="font" href="{{ asset('fonts/Luma-Icons.woff2') }}" type="font/woff2" crossorigin />
        @vite('resources/css/app.css')
    </head>
    <body>
        <main>
            <div id="app" data-vars='{{ json_encode($vars) }}'>
                @vite('resources/js/app.js')
            </div>
        </main>
    </body>
</html>