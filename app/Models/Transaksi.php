<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'nama_pelanggan',
        'total_harga',
        'total_diskon',
        'grand_total',
        'metode_bayar',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'total_harga'  => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'grand_total'  => 'decimal:2',
    ];

    // ============================================
    // RELASI
    // ============================================
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================================
    // HELPER
    // ============================================
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'TRX-' . now()->format('Ymd') . '-';
        $last   = self::where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_transaksi, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getMetodeBayarLabelAttribute(): string
    {
        return match($this->metode_bayar) {
            'tunai'    => 'Tunai',
            'transfer' => 'Transfer',
            'qris'     => 'QRIS',
            default    => ucfirst($this->metode_bayar),
        };
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}