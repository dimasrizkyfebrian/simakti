<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Kegiatan;
use App\Models\Transaksi;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{

    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $filterPicId = '';

    #[Url(except: '')]
    public string $filterStatus = '';

    public function render(): View
    {
        // Cek role user yang sedang login
        if (Auth::user()->role === 'admin') {
            // --- JIKA ADMIN, TAMPILKAN DASHBOARD STATISTIK ---
            $totalKegiatan = Kegiatan::count();
            $kegiatanBerlangsung = Kegiatan::where('tanggal_mulai', '<=', now())->where('tanggal_selesai', '>=', now())->count();
            $totalRealisasi = Kegiatan::sum('total_realisasi');
            $totalPengguna = User::count();
            $kegiatanTerbaru = Kegiatan::with('pic')->latest()->take(5)->get();
            $transaksiTerakhir = Transaksi::with('kegiatan')->latest()->take(5)->get();

            return view('livewire.dashboard', [
                'totalKegiatan' => $totalKegiatan,
                'kegiatanBerlangsung' => $kegiatanBerlangsung,
                'totalRealisasi' => $totalRealisasi,
                'totalPengguna' => $totalPengguna,
                'kegiatanTerbaru' => $kegiatanTerbaru,
                'transaksiTerakhir' => $transaksiTerakhir,
            ]);
        } else {
            // --- JIKA BUKAN ADMIN (KAPRODI/JURUSAN), TAMPILKAN HALAMAN LAPORAN ---
            $kegiatans = Kegiatan::query()
                ->with('pic')
                // Terapkan filter yang sama seperti di halaman admin
                ->when($this->search, fn($q) => $q->where('nama_kegiatan', 'like', '%' . $this->search . '%'))
                ->when($this->filterPicId, fn($q) => $q->where('pic_id', $this->filterPicId))
                ->when($this->filterStatus, function ($query) {
                    $now = Carbon::now();
                    match ($this->filterStatus) {
                        'Direncanakan' => $query->where('tanggal_mulai', '>', $now)->where('total_realisasi', 0),
                        'Persiapan' => $query->where('tanggal_mulai', '>', $now)->where('total_realisasi', '>', 0),
                        'Berlangsung' => $query->where('tanggal_mulai', '<=', $now)->where('tanggal_selesai', '>=', $now),
                        'Telah Selesai' => $query->where('tanggal_selesai', '<', $now),
                        default => '',
                    };
                })
                ->latest()
                ->paginate(15);

            // Ambil data user untuk dropdown filter PIC
            $users = User::query()->orderBy('name')->get();

            return view('livewire.laporan.index', [
                'kegiatans' => $kegiatans,
                'users' => $users,
            ]);
        }
    }
}
