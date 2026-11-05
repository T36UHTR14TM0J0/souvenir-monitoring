<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanProdukDetail extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_produk_details';

    protected $fillable = [
        'penerimaan_produk_id',
        'produk_id',
        'nama_produk',
        'qty_bagus',
        'qty_reject',
        'satuan',
        'keterangan',
    ];

    protected $casts = [
        'qty_bagus' => 'decimal:2',
        'qty_reject' => 'decimal:2',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanProduk::class, 'penerimaan_produk_id');
    }

    public function penerimaanProduk()
    {
        return $this->belongsTo(PenerimaanProduk::class, 'penerimaan_produk_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
