<?php

namespace App\Observers;

use App\Models\Kegiatan;
use App\Models\Transaksi;

class TransaksiObserver
{
    // Method untuk menghitung ulang total realisasi
    private function updateTotalRealisasi(Transaksi $transaksi): void
    {
        // Ambil kegiatan yang berelasi
        $kegiatan = Kegiatan::find($transaksi->kegiatan_id);

        if ($kegiatan) {
            // Hitung total dari semua transaksi pengeluaran untuk kegiatan ini
            $total = $kegiatan->transaksis()->where('jenis_transaksi', 'pengeluaran')->sum('jumlah');

            // Update kolom total_realisasi di tabel kegiatans
            $kegiatan->total_realisasi = $total;
            $kegiatan->saveQuietly(); // saveQuietly agar tidak memicu event lain
        }
    }

    public function created(Transaksi $transaksi): void
    {
        $this->updateTotalRealisasi($transaksi);
    }

    public function updated(Transaksi $transaksi): void
    {
        $this->updateTotalRealisasi($transaksi);
    }

    public function deleted(Transaksi $transaksi): void
    {
        $this->updateTotalRealisasi($transaksi);
    }
}
