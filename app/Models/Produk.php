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
        'satuan_id',
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

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function scopeStokMenipis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimal');
    }

    public static function generateKodeProduk(): string
    {
        $last = self::where('kode_produk', 'like', 'PRD%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $numOnly = preg_replace('/\D/', '', $last->kode_produk);
            $number  = ((int) $numOnly) + 1;
        } else {
            $number = 1;
        }

        return 'PRD' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}