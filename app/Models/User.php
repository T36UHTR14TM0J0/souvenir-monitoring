<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Cek apakah user aktif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Cek apakah user punya role tertentu
     */
    public function hasRoleName(string $role): bool
    {
        return $this->hasRole($role);
    }

    /**
     * Ambil nama role pertama
     */
    public function getRoleNameAttribute(): string
    {
        return $this->getRoleNames()->first() ?? '-';
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
