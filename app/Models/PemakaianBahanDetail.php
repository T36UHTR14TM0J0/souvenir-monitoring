<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianBahanDetail extends Model
{
    use HasFactory;

    protected $table = 'pemakaian_bahan_details';

    protected $fillable = [
        'pemakaian_bahan_id',
        'produk_id',
        'nama_bahan',
        'qty',
        'satuan',
        'keterangan',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function pemakaian()
    {
        return $this->belongsTo(PemakaianBahan::class, 'pemakaian_bahan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
