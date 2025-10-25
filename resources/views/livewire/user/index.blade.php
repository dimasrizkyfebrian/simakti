<div>
    {{-- Header halaman, sesuai standar layout Breeze --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- BARIS UNTUK FILTER, SEARCH, DAN TOMBOL AKSI --}}
                    <div class="flex justify-end mb-4">
                        <div class="flex flex-col md:flex-row gap-4 justify-between mb-4">
                            {{-- Area Filter & Search --}}
                            <div class="flex gap-4">
                                <input type="text" wire:model.live.debounce.300ms="search" class="input input-bordered w-full md:w-auto" placeholder="Cari nama atau email...">
                                <select wire:model.live="filterRole" class="select select-bordered w-full md:w-auto">
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="kaprodi">Kaprodi</option>
                                    <option value="jurusan">Jurusan</option>
                                </select>
                            </div>
                        </div>
                        {{-- Tombol Tambah Pengguna --}}
                        <button wire:click="openModal" class="btn btn-primary ml-4">
                            <x-heroicon-o-plus class="h-5 w-5 mr-2"/>
                            Tambah Pengguna
                        </button>
                    </div>

                    {{-- Tabel dengan kelas DaisyUI --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Nama Pengguna</th>
                                    <th>Role</th>
                                    <th>Tanggal Bergabung</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="hover">
                                        <td>
                                            <div class="font-bold">{{ $user->name }}</div>
                                            <div class="text-sm opacity-50">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            @if ($user->role == 'admin')
                                                <div class="badge badge-error gap-2">Admin</div>
                                            @elseif ($user->role == 'kaprodi')
                                                <div class="badge badge-info gap-2">Kaprodi</div>
                                            @else
                                                <div class="badge badge-success gap-2">Jurusan</div>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M, Y') }}</td>
                                        <th class="text-center space-x-1">
                                            {{-- Tombol Aksi diganti dengan Ikon dari Heroicons --}}
                                            <div class="tooltip" data-tip="Edit">
                                                <button wire:click="edit({{ $user->id }})" class="btn btn-ghost btn-sm btn-square">
                                                    <x-heroicon-o-pencil-square class="h-5 w-5"/>
                                                </button>
                                            </div>
                                            <div class="tooltip" data-tip="Hapus">
                                                <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-ghost btn-sm btn-square text-red-500">
                                                    <x-heroicon-o-trash class="h-5 w-5"/>
                                                </button>
                                            </div>
                                        </th>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center p-4">Tidak ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT PENGGUNA --}}
    <div class="modal {{ $showModal ? 'modal-open' : '' }}">
        <div class="modal-box">
            <h3 class="font-bold text-lg">
                {{ $editingUser ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
            </h3>
            <form wire:submit.prevent="save">
                <div class="py-4 space-y-4">
                    {{-- Input Nama --}}
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Nama Lengkap</span></label>
                        <input type="text" wire:model="name" placeholder="Masukkan nama" class="input input-bordered w-full" />
                        @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    {{-- Input Email --}}
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" wire:model="email" placeholder="Masukkan email" class="input input-bordered w-full" />
                        @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    {{-- Input Password --}}
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Password</span></label>
                        <input type="password" wire:model="password" placeholder="••••••••" class="input input-bordered w-full" />
                        @if ($editingUser)
                            <div class="label">
                                <span class="label-text-alt">Kosongkan jika tidak ingin mengubah password.</span>
                            </div>
                        @endif
                        @error('password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    {{-- Input Role --}}
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Role</span></label>
                        <select wire:model="role" class="select select-bordered w-full">
                            <option value="jurusan">Jurusan</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-error text-sm">{{ $message }}</span> @enderror
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
                Anda yakin ingin menghapus pengguna
                <span class="font-bold">"{{ $deletingUser?->name }}"</span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="modal-action">
                {{-- Tombol Batal, langsung ubah properti showDeleteModal menjadi false --}}
                <button wire:click="$set('showDeleteModal', false)" class="btn">Batal</button>

                {{-- Tombol Hapus, panggil method deleteUser --}}
                <button wire:click="deleteUser" class="btn btn-error">Hapus</button>
            </div>
        </div>
    </div>
</div>