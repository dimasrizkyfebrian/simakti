<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-end mb-4">
                        {{-- Area Filter & Search --}}
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
                        {{-- Tombol Tambah Kegiatan --}}
                        <button wire:click="openModal" class="btn btn-primary ml-4">
                            <x-heroicon-o-plus class="h-5 w-5 mr-2"/>
                            Tambah Kegiatan
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th>Sub Kegiatan</th>
                                    <th>Tanggal Pelaksanaan</th>
                                    <th>PIC</th>
                                    <th>KAPEL</th>
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
                                        <td class="whitespace-nowrap text-center space-x-1">
                                            {{-- Tombol Lihat Detail Kegiatan dihubungkan ke method show() --}}
                                            <div class="tooltip" data-tip="Lihat Detail Kegiatan">
                                                <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" wire:navigate class="btn btn-ghost btn-sm btn-square">
                                                    <x-heroicon-o-eye class="h-5 w-5"/>
                                                </a>
                                            </div>
                                            {{-- Tombol Edit dihubungkan ke method edit() --}}
                                            <div class="tooltip" data-tip="Edit">
                                                <button wire:click="edit('{{ $kegiatan->id }}')" class="btn btn-ghost btn-sm btn-square">
                                                    <x-heroicon-o-pencil-square class="h-5 w-5"/>
                                                </button>
                                            </div>
                                            {{-- Tombol Hapus dihubungkan ke method confirmDelete() --}}
                                            <div class="tooltip" data-tip="Hapus">
                                                <button wire:click="confirmDelete('{{ $kegiatan->id }}')" class="btn btn-ghost btn-sm btn-square text-red-500">
                                                    <x-heroicon-o-trash class="h-5 w-5"/>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center p-4">
                                            Belum ada data kegiatan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $kegiatans->links() }}
                    </div>

                    {{-- MODAL TAMBAH KEGIATAN --}}
                    <div class="modal {{ $showModal ? 'modal-open' : '' }}">
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg">Tambah Kegiatan Baru</h3>
                            <form wire:submit.prevent="save">
                                <div class="py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Input Nama Kegiatan --}}
                                    <div class="form-control w-full col-span-2">
                                        <label class="label"><span class="label-text">Nama Kegiatan</span></label>
                                        <input type="text" wire:model="nama_kegiatan" placeholder="Masukkan nama kegiatan" class="input input-bordered w-full" />
                                        @error('nama_kegiatan') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Input Sub Kegiatan --}}
                                    <div class="form-control w-full col-span-2">
                                        <label class="label"><span class="label-text">Sub Kegiatan (Opsional)</span></label>
                                        <input type="text" wire:model="sub_kegiatan" placeholder="Masukkan sub kegiatan" class="input input-bordered w-full" />
                                    </div>

                                    {{-- Tanggal Mulai --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Tanggal Mulai</span></label>
                                        <input type="date" wire:model="tanggal_mulai" class="input input-bordered w-full" />
                                        @error('tanggal_mulai') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Tanggal Selesai --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Tanggal Selesai</span></label>
                                        <input type="date" wire:model="tanggal_selesai" class="input input-bordered w-full" />
                                        @error('tanggal_selesai') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Pilihan PIC --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Penanggung Jawab (PIC)</span></label>
                                        <select wire:model="pic_id" class="select select-bordered w-full">
                                            <option value="">Pilih PIC</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pic_id') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Input Ketua Pelaksana --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Ketua Pelaksana</span></label>
                                        <input type="text" wire:model="ketua_pelaksana" placeholder="Masukkan nama ketua pelaksana" class="input input-bordered w-full" />
                                        @error('ketua_pelaksana') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Input Pagu Anggaran --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Pagu Anggaran</span></label>
                                        <input type="number" step="any" wire:model="pagu_anggaran" placeholder="Contoh: 5000000" class="input input-bordered w-full" />
                                        @error('pagu_anggaran') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Input Total Pengajuan --}}
                                    <div class="form-control w-full">
                                        <label class="label"><span class="label-text">Total Pengajuan</span></label>
                                        <input type="number" step="any" wire:model="total_pengajuan" placeholder="Contoh: 4500000" class="input input-bordered w-full" />
                                        @error('total_pengajuan') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </form>
                            <div class="modal-action">
                                <button wire:click="closeModal" class="btn">Batal</button>
                                <button wire:click="save" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL KONFIRMASI HAPUS --}}
                    <div class="modal {{ $showDeleteModal ? 'modal-open' : '' }}">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                            <p class="py-4">
                                Anda yakin ingin menghapus kegiatan <span class="font-bold">"{{ $deletingKegiatan?->nama_kegiatan }}"</span>?
                                Semua data transaksi yang terkait juga akan terhapus. Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <div class="modal-action">
                                <button wire:click="$set('showDeleteModal', false)" class="btn">Batal</button>
                                <button wire:click="delete" class="btn btn-error">Ya, Hapus!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>