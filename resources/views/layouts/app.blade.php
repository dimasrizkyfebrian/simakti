<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          theme: localStorage.getItem('theme') || 'light',
          toggleTheme() {
              this.theme = this.theme === 'light' ? 'dark' : 'light';
              localStorage.setItem('theme', this.theme);
              this.applyTheme();
          },
          applyTheme() {
              if (this.theme === 'dark') {
                  document.documentElement.classList.add('dark');
                  document.documentElement.setAttribute('data-theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  document.documentElement.setAttribute('data-theme', 'light');
              }
          }
      }"
      x-init="applyTheme()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'SIMAKTI - ' . config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images\Logo-Polinema-(Politeknik-Negeri-Malang).png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{-- TAMBAHKAN BARIS INI --}}
                    {{ Breadcrumbs::render() }}

                    {{-- Ini adalah slot header yang sudah ada --}}
                    {{ $header }}
                </div>
            </header>
        @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- WADAH & LOGIKA NOTIFIKASI --}}
        <div
            x-data="{
                toasts: [],
                addToast(toast) {
                    this.toasts.push(toast);
                    setTimeout(() => this.toasts.splice(this.toasts.indexOf(toast), 1), 3000);
                }
            }"
            @notify.window="addToast($event.detail)"
            class="toast toast-top toast-end p-4 space-y-3"
        >
            <template x-for="(toast, index) in toasts" :key="index">
                <div class="alert shadow-lg"
                    :class="{
                        'alert-success': toast.type === 'success',
                        'alert-info': toast.type === 'info',
                        'alert-warning': toast.type === 'warning',
                        'alert-error': toast.type === 'error',
                    }"
                >
                    <div class="flex items-center">
                        {{-- Icon dinamis berdasarkan tipe notifikasi --}}
                        <template x-if="toast.type === 'success'"><x-heroicon-o-check-circle class="h-6 w-6"/></template>
                        <template x-if="toast.type === 'info'"><x-heroicon-o-information-circle class="h-6 w-6"/></template>
                        <template x-if="toast.type === 'warning'"><x-heroicon-o-exclamation-triangle class="h-6 w-6"/></template>
                        <template x-if="toast.type === 'error'"><x-heroicon-o-x-circle class="h-6 w-6"/></template>
                        
                        <span class="ml-3" x-text="toast.message"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- SCRIPT UNTUK LIGHT/DARK MODE SAAT NAVIGASI --}}
        <script>
            document.addEventListener('livewire:navigated', () => {
                // Ambil tema dari localStorage, jika tidak ada, default ke 'light'
                const theme = localStorage.getItem('theme') || 'light';

                // Terapkan kembali class 'dark' dan atribut 'data-theme' ke tag <html>
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.documentElement.setAttribute('data-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.setAttribute('data-theme', 'light');
                }
            });
        </script>
    </body>
</html>
