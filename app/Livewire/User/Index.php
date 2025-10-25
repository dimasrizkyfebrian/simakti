<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Manajemen Pengguna')]

class Index extends Component
{
    use WithPagination;

    // Properti untuk Modal Tambah/Edit
    public bool $showModal = false;
    public ?User $editingUser = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'jurusan';

    // Properti untuk Modal Hapus
    public bool $showDeleteModal = false;
    public ?User $deletingUser = null;

    // Properti untuk Pencarian dan Filter
    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $filterRole = '';

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Saat edit, email boleh sama dengan email user itu sendiri
                Rule::unique('users')->ignore($this->editingUser?->id),
            ],
            // Saat edit, password tidak wajib diisi
            'password' => [$this->editingUser ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,kaprodi,jurusan'],
        ];
    }

    // Method untuk membuka modal (mode Tambah)
    public function openModal(): void
    {
        $this->reset();
        $this->editingUser = null; // Pastikan mode edit mati
        $this->showModal = true;
    }

    // Method untuk membuka modal (mode Edit)
    public function edit(int $userId): void
    {
        // Cari user secara manual berdasarkan ID
        $user = User::findOrFail($userId);

        $this->resetValidation();
        $this->editingUser = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';

        $this->showModal = true;
    }

    // Method untuk menutup modal
    public function closeModal(): void
    {
        $this->showModal = false;
    }

    // Method untuk menyimpan (bisa untuk Tambah atau Edit)
    public function save(): void
    {
        $this->validate();

        if ($this->editingUser) {
            // LOGIKA UNTUK EDIT
            $this->editingUser->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            // Hanya update password jika diisi
            if ($this->password) {
                $this->editingUser->update([
                    'password' => Hash::make($this->password),
                ]);
            }
            $message = 'Pengguna berhasil diperbarui.'; // <-- Pesan untuk edit
        } else {
            // LOGIKA UNTUK TAMBAH BARU
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
            $message = 'Pengguna berhasil ditambahkan.'; // <-- Pesan untuk tambah
        }
        // Kirim notifikasi setelah salah satu aksi di atas berhasil
        $this->dispatch('notify', message: $message, type: 'success');

        $this->reset();
        $this->closeModal();
    }

    // Method untuk membuka modal konfirmasi hapus
    public function confirmDelete(User $user): void
    {
        $this->deletingUser = $user;
        $this->showDeleteModal = true;
    }

    // Method untuk benar-benar menghapus user
    public function deleteUser(): void
    {
        if ($this->deletingUser) {
            $this->deletingUser->delete();
        }
        // Kirim notifikasi setelah hapus berhasil
        $this->dispatch('notify', message: 'Pengguna berhasil dihapus.', type: 'info');

        $this->deletingUser = null;
        $this->showDeleteModal = false;
    }

    public function render(): View
    {
        $users = User::query()
            // Terapkan filter PENCARIAN jika $this->search tidak kosong
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            // Terapkan filter ROLE jika $this->filterRole tidak kosong
            ->when($this->filterRole, function ($query) {
                $query->where('role', $this->filterRole);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.index', [
            'users' => $users,
        ]);
    }
}
