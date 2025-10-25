<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Kegiatan;
use App\Models\Transaksi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendapatkan semua kegiatan di mana user ini adalah PIC.
     */
    public function kegiatanPic(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'pic_id');
    }

    /**
     * Mendapatkan semua transaksi di mana user ini adalah PIC.
     */
    public function transaksiPic(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'pic_id');
    }
}
