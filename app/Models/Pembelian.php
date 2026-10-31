<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'kode_pembelian',
        'tanggal',
        'supplier',
        'total_harga',
        'total_diskon',
        'grand_total',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'total_harga'  => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'grand_total'  => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKodePembelian(): string
    {
        $prefix = 'PBL-' . now()->format('Ymd') . '-';
        $last   = self::where('kode_pembelian', 'like', $prefix . '%')
            ->orderBy('kode_pembelian', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_pembelian, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}