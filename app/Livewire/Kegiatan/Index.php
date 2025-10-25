<?php

namespace App\Livewire\Kegiatan;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Kegiatan')]

class Index extends Component
{
    use WithPagination;

    // Properti untuk modal & form Create/Edit
    public bool $showModal = false;
    public ?Kegiatan $editingKegiatan = null;

    // Properti untuk modal Hapus
    public bool $showDeleteModal = false;
    public ?Kegiatan $deletingKegiatan = null;

    // Properti Form
    public string $nama_kegiatan = '';
    public string $sub_kegiatan = '';
    public string $tanggal_mulai = '';
    public string $tanggal_selesai = '';
    public int|null $pic_id = null;
    public string $ketua_pelaksana = '';
    public string $pagu_anggaran = '';
    public string $total_pengajuan = '';

    // Properti untuk Pencarian dan Filter
    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $filterPicId = '';

    #[Url(except: '')]
    public string $filterStatus = '';

    protected function rules()
    {
        return [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'sub_kegiatan' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'pic_id' => ['required', 'exists:users,id'],
            'ketua_pelaksana' => ['required', 'string', 'max:255'],
            'pagu_anggaran' => ['required', 'numeric', 'min:0'],
            'total_pengajuan' => ['required', 'numeric', 'min:0', 'lte:pagu_anggaran'],
        ];
    }

    // --- LOGIKA CREATE ---
    public function openModal(): void
    {
        $this->reset();
        $this->editingKegiatan = null;
        $this->showModal = true;
    }

    // --- LOGIKA EDIT ---
    public function edit(Kegiatan $kegiatan): void
    {
        $this->resetValidation();
        $this->editingKegiatan = $kegiatan;

        // Isi properti form dengan data yang ada
        $this->nama_kegiatan = $kegiatan->nama_kegiatan;
        $this->sub_kegiatan = $kegiatan->sub_kegiatan;
        $this->tanggal_mulai = $kegiatan->tanggal_mulai;
        $this->tanggal_selesai = $kegiatan->tanggal_selesai;
        $this->pic_id = $kegiatan->pic_id;
        $this->ketua_pelaksana = $kegiatan->ketua_pelaksana;
        $this->pagu_anggaran = $kegiatan->pagu_anggaran;
        $this->total_pengajuan = $kegiatan->total_pengajuan;

        $this->showModal = true;
    }

    // --- LOGIKA SAVE (Create & Update) ---
    public function save(): void
    {
        // Validasi data dari form
        $validated = $this->validate();

        // Cek apakah ini mode Edit atau mode Tambah Baru
        if ($this->editingKegiatan) {
            // Jika mode Edit, update data yang ada
            $this->editingKegiatan->update($validated);
            $this->dispatch('notify', message: 'Kegiatan berhasil diperbarui.', type: 'success');
        } else {
            // Jika mode Tambah, buat data baru
            Kegiatan::create($validated);
            $this->dispatch('notify', message: 'Kegiatan berhasil ditambahkan.', type: 'success');
        }

        // Tutup modal setelah selesai
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset();
    }

    // --- LOGIKA DELETE ---
    public function confirmDelete(Kegiatan $kegiatan): void
    {
        $this->deletingKegiatan = $kegiatan;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingKegiatan) {
            $this->deletingKegiatan->delete();
            $this->dispatch('notify', message: 'Kegiatan berhasil dihapus.', type: 'info');
        }
        $this->showDeleteModal = false;
    }

    public function render(): View
    {
        $kegiatans = Kegiatan::query()
            ->with('pic')
            // Terapkan filter PENCARIAN
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                        ->orWhere('sub_kegiatan', 'like', '%' . $this->search . '%')
                        ->orWhere('ketua_pelaksana', 'like', '%' . $this->search . '%');
                });
            })
            // Terapkan filter PIC
            ->when($this->filterPicId, function ($query) {
                $query->where('pic_id', $this->filterPicId);
            })
            // Terapkan filter STATUS
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
            ->paginate(10);

        $users = User::query()->orderBy('name')->get();

        return view('livewire.kegiatan.index', [
            'kegiatans' => $kegiatans,
            'users' => $users,
        ]);
    }
}
