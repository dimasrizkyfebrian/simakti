<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Kegiatan: {{ $kegiatan->nama_kegiatan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- STATS KEUANGAN --}}
            <div class="stats shadow w-full bg-base-200">
                <div class="stat">
                    <div class="stat-figure text-secondary"><x-heroicon-o-wallet class="h-8 w-8"/></div>
                    <div class="stat-title">Pagu Anggaran</div>
                    <div class="stat-value text-secondary">{{ Number::currency($kegiatan->pagu_anggaran, 'IDR') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-figure text-primary"><x-heroicon-o-arrow-up-on-square class="h-8 w-8"/></div>
                    <div class="stat-title">Total Pengajuan</div>
                    <div class="stat-value text-primary">{{ Number::currency($kegiatan->total_pengajuan, 'IDR') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-figure text-accent"><x-heroicon-o-arrow-down-on-square class="h-8 w-8"/></div>
                    <div class="stat-title">Total Realisasi</div>
                    <div class="stat-value text-accent">{{ Number::currency($kegiatan->total_realisasi, 'IDR') }}</div>
                </div>
                <div class="stat">
                    <div class="stat-figure text-info"><x-heroicon-o-receipt-percent class="h-8 w-8"/></div>
                    <div class="stat-title">Sisa Dana</div>
                    <div class="stat-value text-info">{{ Number::currency($kegiatan->total_pengajuan - $kegiatan->total_realisasi, 'IDR') }}</div>
                </div>
            </div>

            {{-- STATS PEMASUKAN/PENGELUARAN --}}
            <div class="stats shadow w-full bg-base-200">
                <div class="stat">
                    <div class="stat-figure text-success"><x-heroicon-o-arrow-down-tray class="h-8 w-8"/></div>
                    <div class="stat-title">Total Pemasukan Eksternal</div>
                    <div class="stat-value text-success">{{ Number::currency($totalPemasukan, 'IDR') }}</div>
                    <div class="stat-desc">Dana dari sponsor, tiket, dll.</div>
                </div>
                <div class="stat">
                    <div class="stat-figure text-warning"><x-heroicon-o-arrow-up-tray class="h-8 w-8"/></div>
                    <div class="stat-title">Total Pengeluaran</div>
                    <div class="stat-value text-warning">{{ Number::currency($kegiatan->total_realisasi, 'IDR') }}</div>
                    <div class="stat-desc">Total dana yang dibelanjakan.</div>
                </div>
                <div class="stat">
                    <div class="stat-figure"><x-heroicon-o-scale class="h-8 w-8"/></div>
                    <div class="stat-title">Saldo Kas Akhir</div>
                    <div class="stat-value">{{ Number::currency($saldoKas, 'IDR') }}</div>
                    <div class="stat-desc">Pemasukan - Pengeluaran</div>
                </div>
            </div>

            {{-- RIWAYAT TRANSAKSI --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Riwayat Transaksi / Progress</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('laporan.kegiatan.detail', $kegiatan) }}" target="_blank" class="btn btn-outline btn-sm">
                                <x-heroicon-o-printer class="h-5 w-5 mr-2"/>
                                Lihat Laporan
                            </a>
                            <button wire:click="openModal" class="btn btn-primary btn-sm">
                                <x-heroicon-o-plus class="h-5 w-5 mr-2"/>
                                Tambah Transaksi
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Jenis</th>
                                    <th class="text-right">Jumlah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kegiatan->transaksis->sortByDesc('tanggal_transaksi') as $transaksi)
                                    <tr class="hover">
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                                        <td>{{ $transaksi->keterangan }}</td>
                                        <td>
                                            @if ($transaksi->jenis_transaksi == 'pemasukan')
                                                <div class="badge badge-success gap-2">Pemasukan</div>
                                            @else
                                                <div class="badge badge-warning gap-2">Pengeluaran</div>
                                            @endif
                                        </td>
                                        <td class="text-right font-mono @if($transaksi->jenis_transaksi == 'pemasukan') text-success @else text-warning @endif">
                                            {{ Number::currency($transaksi->jumlah, 'IDR') }}
                                        </td>
                                        <td class="text-center space-x-1">
                                            <div class="tooltip" data-tip="Edit">
                                                <button wire:click="edit({{ $transaksi->id }})" class="btn btn-ghost btn-xs btn-square">
                                                    <x-heroicon-o-pencil-square class="h-5 w-5"/>
                                                </button>
                                            </div>
                                            <div class="tooltip" data-tip="Hapus">
                                                <button wire:click="confirmDelete({{ $transaksi->id }})" class="btn btn-ghost btn-xs btn-square text-red-500">
                                                    <x-heroicon-o-trash class="h-5 w-5"/>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH TRANSAKSI --}}
    <div class="modal {{ $showModal ? 'modal-open' : '' }}">
        <div class="modal-box">
            {{-- Judul Modal Dinamis --}}
            <h3 class="font-bold text-lg">{{ $editingTransaksi ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}</h3>
            <form wire:submit.prevent="saveTransaksi">
                <div class="py-4 space-y-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Jenis Transaksi</span></label>
                        <select wire:model="jenis_transaksi" class="select select-bordered w-full">
                            <option value="pengeluaran">Pengeluaran (Realisasi)</option>
                            <option value="pemasukan">Pemasukan</option>
                        </select>
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Tanggal Transaksi</span></label>
                        <input type="date" wire:model="tanggal_transaksi" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Keterangan</span></label>
                        <input type="text" wire:model="keterangan" placeholder="Contoh: Pembelian spanduk" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text">Jumlah (Rp)</span></label>
                        <input type="number" step="any" wire:model="jumlah" placeholder="Contoh: 150000" class="input input-bordered w-full" />
                    </div>
                </div>
            </form>
            <div class="modal-action">
                <button wire:click="closeModal" class="btn">Batal</button>
                <button wire:click="saveTransaksi" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS TRANSAKSI --}}
    <div class="modal {{ $showDeleteModal ? 'modal-open' : '' }}">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
            <p class="py-4">
                Anda yakin ingin menghapus transaksi <span class="font-bold">"{{ $deletingTransaksi?->keterangan }}"</span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="modal-action">
                <button wire:click="$set('showDeleteModal', false)" class="btn">Batal</button>
                <button wire:click="delete" class="btn btn-error">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>