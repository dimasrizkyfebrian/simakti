<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\Kegiatan\Index as KegiatanIndex;
use App\Livewire\Kegiatan\Show as KegiatanShow;
use App\Livewire\Laporan\KegiatanDetail as LaporanKegiatanDetail;
use App\Livewire\Profile\EditPage as ProfileEditPage;
use App\Livewire\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', UserIndex::class)->name('admin.users.index');

    Route::get('/kegiatan', KegiatanIndex::class)->name('admin.kegiatan.index');

    Route::get('/kegiatan/{kegiatan}', KegiatanShow::class)->name('admin.kegiatan.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/laporan/kegiatan/{kegiatan}', LaporanKegiatanDetail::class)->name('laporan.kegiatan.detail');

    Route::get('/profile', ProfileEditPage::class)->name('profile.edit');
});


require __DIR__ . '/auth.php';
