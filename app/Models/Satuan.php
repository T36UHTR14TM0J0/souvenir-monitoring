<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuans';

    protected $fillable = [
        'nama_satuan',
        'singkatan',
        'deskripsi',
    ];

    /**
     * Relasi ke Produk: Satu satuan dapat digunakan oleh banyak produk.
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class, 'satuan_id');
    }
}
