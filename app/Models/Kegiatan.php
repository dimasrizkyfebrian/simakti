<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kegiatan extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kegiatan',
        'sub_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'pic_id',
        'ketua_pelaksana',
        'pagu_anggaran',
        'total_pengajuan',
        'total_realisasi',
        'progress',
    ];

    protected $appends = ['status'];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                $sekarang = Carbon::now();
                $tanggalMulai = Carbon::parse($this->tanggal_mulai);
                $tanggalSelesai = Carbon::parse($this->tanggal_selesai);

                if ($sekarang->isAfter($tanggalSelesai)) {
                    if ($this->total_realisasi > 0 && $this->total_realisasi >= $this->total_pengajuan) {
                        return 'Selesai & Lunas';
                    }
                    return 'Telah Selesai';
                }

                if ($sekarang->between($tanggalMulai, $tanggalSelesai)) {
                    return 'Sedang Berlangsung';
                }

                if ($sekarang->isBefore($tanggalMulai)) {
                    if ($this->total_realisasi > 0) {
                        return 'Persiapan';
                    }
                    return 'Direncanakan';
                }

                return 'Status Tidak Diketahui';
            }
        );
    }

    /**
     * Mendapatkan data user PIC (Penanggung Jawab).
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * Mendapatkan semua data transaksi yang dimiliki oleh kegiatan ini.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
