<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'kategori_id',
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimal',
        'deskripsi',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function scopeStokMenipis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimal');
    }

    public static function generateKodeProduk(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? ((int) substr($last->kode_produk, 3)) + 1 : 1;
        return 'PRD' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}