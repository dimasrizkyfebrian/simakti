<div class="p-8">
    {{-- Header Laporan --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold">Laporan Detail Kegiatan</h1>
        <p class="text-lg">{{ $kegiatan->nama_kegiatan }}</p>
        <p class="text-sm text-gray-600">
            Periode: {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}
        </p>
    </div>

    {{-- Info Utama --}}
    <div class="mb-8 grid grid-cols-2 gap-4">
        <div>
            <p><span class="font-bold">Penanggung Jawab (PIC):</span> {{ $kegiatan->pic->name }}</p>
            <p><span class="font-bold">Ketua Pelaksana:</span> {{ $kegiatan->ketua_pelaksana }}</p>
        </div>
        <div class="text-right">
            <p><span class="font-bold">Pagu Anggaran:</span> {{ Number::currency($kegiatan->pagu_anggaran, 'IDR') }}</p>
            <p><span class="font-bold">Total Pengajuan:</span> {{ Number::currency($kegiatan->total_pengajuan, 'IDR') }}</p>
            <p><span class="font-bold text-blue-600">Total Realisasi:</span> <span class="text-blue-600">{{ Number::currency($kegiatan->total_realisasi, 'IDR') }}</span></p>
        </div>
    </div>
    
    {{-- Tombol Aksi (Cetak) --}}
    <div class="mb-4 text-right print:hidden">
        <button onclick="window.print()" class="btn btn-primary">
            <x-heroicon-o-printer class="h-5 w-5 mr-2"/>
            Cetak Laporan
        </button>
    </div>

    {{-- Riwayat Transaksi --}}
    <div>
        <h2 class="text-xl font-bold mb-4">Rincian Transaksi</h2>
        <div class="overflow-x-auto border rounded-lg">
            <table class="table w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jenis</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan->transaksis->sortBy('tanggal_transaksi') as $transaksi)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                            <td>{{ $transaksi->keterangan }}</td>
                            <td>
                                @if ($transaksi->jenis_transaksi == 'pemasukan')
                                    <span class="badge badge-success">Pemasukan</span>
                                @else
                                    <span class="badge badge-warning">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="text-right font-mono">{{ Number::currency($transaksi->jumlah, 'IDR') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="text-right">Total Pengeluaran (Realisasi)</td>
                        <td class="text-right font-mono">{{ Number::currency($kegiatan->total_realisasi, 'IDR') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>