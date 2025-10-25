<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SIMAKTI - Politeknik Negeri Malang</title>
        <link rel="icon" type="image/png" href="{{ asset('public\images\Logo-Polinema-(Politeknik-Negeri-Malang).png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        {{-- Latar Belakang --}}
        <div class="text-white min-h-screen flex flex-col" style="background-image: url('{{ asset('images/Background-Gaussian-Blur.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    
            {{-- Konten Utama (Hero Section) --}}
            {{-- Dibuat setinggi layar penuh dan kontennya di tengah --}}
            <main class="min-h-screen flex flex-col items-center justify-center text-center relative">
                {{-- Navbar Transparan diletakkan di dalam main agar posisinya relatif --}}
                <header class="absolute inset-x-0 top-0 z-50">
                    <nav class="flex items-center justify-end p-6 lg:px-8" aria-label="Global">
                        <div class="sm:flex sm:gap-x-12">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 hover:text-gray-300">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline btn-info">Log in</a>
                                @endauth
                            @endif
                        </div>
                    </nav>
                </header>
                
                {{-- Konten Hero --}}
                <div>
                    <img src="{{ asset('images\Logo-Polinema-(Politeknik-Negeri-Malang).png') }}" alt="Logo Polinema" class="mx-auto h-32 w-auto mb-6 opacity-0 animate-fade-in-up">
                    <h1 class="text-5xl font-bold tracking-tight text-white sm:text-7xl opacity-0 animate-fade-in-up [animation-delay:200ms]">
                        SIMAKTI
                    </h1>
                    <p class="mt-4 text-lg leading-8 text-gray-300 opacity-0 animate-fade-in-up [animation-delay:400ms]">
                        Sistem Informasi Manajemen Kegiatan TI
                    </p>
                </div>
            </main>
    
            {{-- Footer dari DaisyUI --}}
            <footer class="footer sm:footer-horizontal bg-neutral text-neutral-content grid-rows-1 p-10">
            {{-- Kolom Pertama: Logo dan Nama --}}
            <aside>
                <x-application-logo class="h-16 w-16 fill-current"/>
                <p class="font-bold text-lg">
                SIMAKTI
                </p>
                <p>Sistem Informasi Manajemen Kegiatan TI<br>Politeknik Negeri Malang</p>
                <p class="opacity-70">Copyright © {{ date('Y') }} - All right reserved</p>
            </aside> 

            {{-- Kolom Kedua: Layanan --}}
            <nav>
                <header class="footer-title">Layanan</header> 
                <a class="link link-hover">Manajemen Kegiatan</a>
                <a class="link link-hover">Manajemen Keuangan</a>
                <a class="link link-hover">Pelaporan</a>
            </nav> 

            {{-- Kolom Ketiga: Tentang --}}
            <nav>
                <header class="footer-title">Tentang</header> 
                <p class="w-64">Aplikasi ini dibuat untuk membantu pengelolaan kegiatan dan keuangan di lingkungan Jurusan Teknologi Informasi.</p>
            </nav> 

            {{-- Kolom Keempat: Kontak --}}
            <nav>
                <header class="footer-title">Kontak</header> 
                <p>Jl. Soekarno Hatta No.9, Jatimulyo, <br>Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</p>
            </nav>
        </footer>
        </div>
    </body>
</html>