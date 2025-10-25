<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div
            x-data="{ loaded: false }"
            x-init="setTimeout(() => loaded = true, 200)"
            class="max-w-7xl mx-auto sm:px-6 lg:px-8"
        >

            {{-- BARIS 1: KARTU STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                {{-- Widget 1: Total Kegiatan --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="stats shadow bg-white dark:bg-gray-800">
                    <div class="stat">
                        <div class="stat-figure text-primary"><x-heroicon-o-briefcase class="h-8 w-8"/></div>
                        <div class="stat-title dark:text-gray-400">Total Kegiatan</div>
                        <div class="stat-value text-primary">{{ $totalKegiatan }}</div>
                    </div>
                </div>

                {{-- Widget 2: Kegiatan Berlangsung --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="stats shadow bg-white dark:bg-gray-800">
                    <div class="stat">
                        <div class="stat-figure text-secondary"><x-heroicon-o-play-circle class="h-8 w-8"/></div>
                        <div class="stat-title dark:text-gray-400">Kegiatan Berlangsung</div>
                        <div class="stat-value text-secondary">{{ $kegiatanBerlangsung }}</div>
                    </div>
                </div>

                {{-- Widget 3: Total Realisasi --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="stats shadow bg-white dark:bg-gray-800">
                    <div class="stat">
                        <div class="stat-figure text-accent"><x-heroicon-o-banknotes class="h-8 w-8"/></div>
                        <div class="stat-title dark:text-gray-400">Total Realisasi</div>
                        <div class="stat-value text-accent">{{ Number::currency($totalRealisasi, 'IDR') }}</div>
                    </div>
                </div>

                {{-- Widget 4: Total Pengguna --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300 delay-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="stats shadow bg-white dark:bg-gray-800">
                    <div class="stat">
                        <div class="stat-figure text-info"><x-heroicon-o-users class="h-8 w-8"/></div>
                        <div class="stat-title dark:text-gray-400">Total Pengguna</div>
                        <div class="stat-value text-info">{{ $totalPengguna }}</div>
                    </div>
                </div>
            </div>

            {{-- BARIS 2: TABEL AKTIVITAS TERAKHIR --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

                {{-- Kolom Kiri: Kegiatan Terbaru --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-bold text-lg mb-4">Kegiatan Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <tbody>
                                    @forelse ($kegiatanTerbaru as $kegiatan)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" wire:navigate class="font-bold link link-hover">{{ $kegiatan->nama_kegiatan }}</a>
                                                <div class="text-sm opacity-70">PIC: {{ $kegiatan->pic->name }}</div>
                                            </td>
                                            <td><div class="badge badge-info">{{ $kegiatan->status }}</div></td>
                                        </tr>
                                    @empty
                                        <tr><td>Belum ada kegiatan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Transaksi Terakhir --}}
                <div x-show="loaded" x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-bold text-lg mb-4">Transaksi Terakhir</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <tbody>
                                    @forelse ($transaksiTerakhir as $transaksi)
                                        <tr>
                                            <td>
                                                <div class="font-bold">{{ $transaksi->keterangan }}</div>
                                                <div class="text-sm opacity-70">Pada kegiatan: {{ Str::limit($transaksi->kegiatan->nama_kegiatan, 30) }}</div>
                                            </td>
                                            <td class="text-right font-mono @if($transaksi->jenis_transaksi == 'pemasukan') text-success @else text-warning @endif">
                                                {{ Number::currency($transaksi->jumlah, 'IDR') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td>Belum ada transaksi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>