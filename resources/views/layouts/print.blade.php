<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Laporan Kegiatan</title>
        <link rel="icon" type="image/png" href="{{ asset('images\Logo-Polinema-(Politeknik-Negeri-Malang).png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-white">
        <main>
            {{ $slot }}
        </main>
        @livewireScripts
        <script>
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-theme', 'light');
        </script>
    </body>
</html>