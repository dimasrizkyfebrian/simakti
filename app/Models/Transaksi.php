<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaksi extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kegiatan_id',
        'jenis_transaksi',
        'keterangan',
        'jumlah',
        'tanggal_transaksi',
        'created_by_user_id',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
