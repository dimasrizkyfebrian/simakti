<?php

use App\Models\Kegiatan;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Breadcrumb untuk Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail): void {
    $trail->push('Dashboard', route('dashboard'));
});

// Breadcrumb untuk Halaman Profil
Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard'); // Induknya adalah Dashboard
    $trail->push('Profil', route('profile.edit'));
});


// Breadcrumb untuk Manajemen Pengguna
Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard'); // <-- Induknya adalah Dashboard
    $trail->push('Manajemen Pengguna', route('admin.users.index'));
});

// Breadcrumb untuk Manajemen Kegiatan
Breadcrumbs::for('admin.kegiatan.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('dashboard'); // <-- Induknya adalah Dashboard
    $trail->push('Manajemen Kegiatan', route('admin.kegiatan.index'));
});

// Breadcrumb untuk Detail Kegiatan
Breadcrumbs::for('admin.kegiatan.show', function (BreadcrumbTrail $trail, Kegiatan $kegiatan): void {
    $trail->parent('admin.kegiatan.index'); // <-- Induknya adalah Manajemen Kegiatan
    // Tampilkan nama kegiatan sebagai breadcrumb terakhir (tidak bisa diklik)
    $trail->push($kegiatan->nama_kegiatan, route('admin.kegiatan.show', $kegiatan));
});
