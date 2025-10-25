<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Login</title>
        <link rel="icon" type="image/png" href="{{ asset('images\Logo-Polinema-(Politeknik-Negeri-Malang).png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex items-stretch min-h-screen">
            <div class="hidden lg:flex w-full lg:w-1/2 justify-around items-center" style="background-image: url('{{ asset('images/Background-Login.jpg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                <div class="text-center text-white px-10">
                    <x-application-logo class="w-24 h-24 mx-auto mb-4"/>
                    <h1 class="text-4xl font-bold mb-2">SIMAKTI</h1>
                    <p class="text-lg leading-tight">Sistem Informasi Manajemen Kegiatan <br> Jurusan Teknologi Informasi</p>
                    <div class="w-24 border-b-2 mx-auto mt-6"></div>
                </div>
            </div>

            <div class="flex w-full lg:w-1/2 justify-center items-center bg-gray-200">
                <div class="w-full max-w-sm p-8">
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="text-center mb-10">
                            <h1 class="text-4xl font-bold text-gray-800">Selamat Datang</h1>
                            <p class="text-md text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
                        </div>
                        
                        <div class="form-control mb-4">
                            <label class="input input-bordered flex items-center gap-2">
                                <x-heroicon-o-envelope class="w-4 h-4 opacity-70 text-gray-100"/>
                                <input type="email" name="email" class="grow" placeholder="Email" value="{{ old('email') }}" required autofocus />
                            </label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="form-control mb-2">
                            <label class="input input-bordered flex items-center gap-2">
                                <x-heroicon-o-key class="w-4 h-4 opacity-70 text-gray-100"/>
                                <input type="password" name="password" class="grow" placeholder="Password" required autocomplete="current-password" />
                            </label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex justify-between items-center my-6">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="checkbox checkbox-primary checkbox-sm" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <div class="form-control mt-6">
                            <button class="btn btn-primary w-full">Log in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>