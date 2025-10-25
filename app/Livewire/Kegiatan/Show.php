<?php

namespace App\Livewire\Kegiatan;

use App\Models\Kegiatan;
use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class Show extends Component
{

    public Kegiatan $kegiatan;

    // Properti untuk modal Create/Edit
    public bool $showModal = false;
    public ?Transaksi $editingTransaksi = null;

    // Properti untuk modal Hapus
    public bool $showDeleteModal = false;
    public ?Transaksi $deletingTransaksi = null;

    // Properti Form
    public string $jenis_transaksi = 'pengeluaran';
    public string $keterangan = '';
    public string $jumlah = '';
    public string $tanggal_transaksi = '';

    protected function rules()
    {
        return [
            'jenis_transaksi' => ['required', 'in:pemasukan,pengeluaran'],
            'keterangan' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'tanggal_transaksi' => ['required', 'date'],
        ];
    }

    public function mount(Kegiatan $kegiatan): void
    {
        $this->kegiatan = $kegiatan;
        $this->tanggal_transaksi = now()->format('Y-m-d');
    }

    public function openModal(): void
    {
        $this->reset(['keterangan', 'jumlah', 'jenis_transaksi', 'tanggal_transaksi']);
        $this->editingTransaksi = null; // Pastikan mode edit mati
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function edit(Transaksi $transaksi): void
    {
        $this->resetValidation();
        $this->editingTransaksi = $transaksi;

        // Isi form dengan data transaksi yang ada
        $this->jenis_transaksi = $transaksi->jenis_transaksi;
        $this->keterangan = $transaksi->keterangan;
        $this->jumlah = $transaksi->jumlah;
        $this->tanggal_transaksi = $transaksi->tanggal_transaksi;

        $this->showModal = true;
    }

    // Method baru untuk menghitung ulang realisasi
    public function updateRealisasi(): void
    {
        // Logika kalkulasi
        $total = $this->kegiatan->transaksis()->where('jenis_transaksi', 'pengeluaran')->sum('jumlah');

        $this->kegiatan->total_realisasi = $total;
        $this->kegiatan->save();
    }

    public function saveTransaksi(): void
    {
        $validated = $this->validate();

        if ($this->editingTransaksi) {
            // --- LOGIKA UPDATE ---
            $this->editingTransaksi->update($validated);
            $this->dispatch('notify', message: 'Transaksi berhasil diperbarui.', type: 'success');
        } else {
            // --- LOGIKA CREATE ---
            $validated['created_by_user_id'] = auth()->id();
            $this->kegiatan->transaksis()->create($validated);
            $this->dispatch('notify', message: 'Transaksi berhasil ditambahkan.', type: 'success');
        }

        $this->updateRealisasi();
        $this->closeModal();
    }

    public function confirmDelete(Transaksi $transaksi): void
    {
        $this->deletingTransaksi = $transaksi;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingTransaksi) {
            $this->deletingTransaksi->delete();
            $this->updateRealisasi(); // Panggil kalkulasi ulang setelah hapus
            $this->dispatch('notify', message: 'Transaksi berhasil dihapus.', type: 'info');
        }
        $this->showDeleteModal = false;
    }

    public function render(): View
    {
        // Muat ulang data kegiatan untuk memastikan data di view selalu fresh
        $this->kegiatan->refresh();

        // --- TAMBAHKAN KALKULASI ---
        $totalPemasukan = $this->kegiatan->transaksis()
            ->where('jenis_transaksi', 'pemasukan')
            ->sum('jumlah');

        // Total pengeluaran sama dengan total realisasi yang sudah dihitung
        $totalPengeluaran = $this->kegiatan->total_realisasi;

        // Saldo kas adalah total pemasukan dikurangi total pengeluaran
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        // Kirim semua data yang dibutuhkan ke view
        return view('livewire.kegiatan.show', [
            'totalPemasukan' => $totalPemasukan,
            'saldoKas' => $saldoKas,
        ])->title('Detail: ' . $this->kegiatan->nama_kegiatan);
    }
}
