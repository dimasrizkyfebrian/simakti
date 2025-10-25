<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Rekapitulasi Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- BARIS UNTUK FILTER & SEARCH --}}
                    <div class="flex flex-col md:flex-row gap-4 justify-between mb-4">
                        <div class="flex gap-4 items-center">
                            <input type="text" wire:model.live.debounce.300ms="search" class="input input-bordered w-full md:w-auto" placeholder="Cari kegiatan...">

                            <select wire:model.live="filterPicId" class="select select-bordered w-full md:w-auto">
                                <option value="">Semua PIC</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <select wire:model.live="filterStatus" class="select select-bordered w-full md:w-auto">
                                <option value="">Semua Status</option>
                                <option value="Direncanakan">Direncanakan</option>
                                <option value="Persiapan">Persiapan</option>
                                <option value="Berlangsung">Berlangsung</option>
                                <option value="Telah Selesai">Telah Selesai</option>
                            </select>
                        </div>
                    </div>

                    {{-- "Slider" atau div dengan horizontal scroll --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th>Sub Kegiatan</th>
                                    <th>Tanggal Pelaksanaan</th>
                                    <th>PIC</th>
                                    <th>Ketua Pelaksana</th>
                                    <th>Pagu Anggaran</th>
                                    <th>Pengajuan</th>
                                    <th>Realisasi</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kegiatans as $kegiatan)
                                    <tr class="hover">
                                        <td class="whitespace-nowrap font-bold">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td class="whitespace-nowrap">{{ $kegiatan->sub_kegiatan ?? '-' }}</td>
                                        <td class="whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}
                                        </td>
                                        <td class="whitespace-nowrap">{{ $kegiatan->pic->name }}</td>
                                        <td class="whitespace-nowrap">{{ $kegiatan->ketua_pelaksana }}</td>
                                        <td class="whitespace-nowrap">{{ Number::currency($kegiatan->pagu_anggaran, 'IDR') }}</td>
                                        <td class="whitespace-nowrap">{{ Number::currency($kegiatan->total_pengajuan, 'IDR') }}</td>
                                        <td class="whitespace-nowrap">{{ Number::currency($kegiatan->total_realisasi, 'IDR') }}</td>
                                        <td class="whitespace-nowrap">
                                            @php
                                                $status = $kegiatan->status;
                                                $badgeColor = match ($status) {
                                                    'Direncanakan' => 'badge-ghost',
                                                    'Persiapan' => 'badge-info',
                                                    'Sedang Berlangsung' => 'badge-primary',
                                                    'Telah Selesai' => 'badge-success',
                                                    'Selesai & Lunas' => 'badge-accent',
                                                    default => 'badge-neutral',
                                                };
                                            @endphp
                                            <div class="badge {{ $badgeColor }}">{{ $status }}</div>
                                        </td>
                                        <td class="whitespace-nowrap text-center">
                                            <div class="tooltip" data-tip="Lihat Detail Laporan">
                                                <a href="{{ route('laporan.kegiatan.detail', $kegiatan) }}" target="_blank" class="btn btn-ghost btn-sm btn-square">
                                                    <x-heroicon-o-eye class="h-5 w-5"/>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center p-4">
                                            Belum ada data kegiatan untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="p-4">
                        {{ $kegiatans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>