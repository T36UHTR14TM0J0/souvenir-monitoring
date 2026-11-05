<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    public const PLATFORMS = [
        'shopee' => 'Shopee',
        'tokopedia' => 'Tokopedia',
        'tiktok' => 'TikTok Shop',
        'lazada' => 'Lazada',
        'website' => 'Website Supplier',
        'offline' => 'Toko Offline / Fisik',
    ];

    public const STATUS_PEMBELIAN = [
        'dipesan' => 'Dipesan (Supplier)',
        'dikirim' => 'Dikirim (Kargo / Perjalanan)',
        'diterima' => 'Diterima di Workshop',
    ];

    protected $fillable = [
        'kode_pembelian',
        'tanggal',
        'supplier',
        'platform',
        'no_invoice_online',
        'total_harga',
        'total_diskon',
        'ongkir',
        'grand_total',
        'status_pembelian',
        'kurir_kargo',
        'no_resi_kargo',
        'catatan',
        'bukti_transaksi',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPlatformLabelAttribute(): string
    {
        return self::PLATFORMS[$this->platform] ?? ucfirst($this->platform ?? 'Shopee');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_PEMBELIAN[$this->status_pembelian] ?? ucfirst($this->status_pembelian ?? 'Diterima');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_pembelian) {
            'dipesan' => 'bg-warning-subtle text-warning border border-warning',
            'dikirim' => 'bg-info-subtle text-info border border-info',
            'diterima' => 'bg-success-subtle text-success border border-success',
            default => 'bg-secondary-subtle text-secondary border border-secondary',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status_pembelian) {
            'dipesan' => 'fas fa-clock',
            'dikirim' => 'fas fa-truck-fast',
            'diterima' => 'fas fa-check-circle',
            default => 'fas fa-box',
        };
    }

    public static function generateKodePembelian(): string
    {
        $prefix = 'PBL-'.now()->format('Ymd').'-';
        $last = self::where('kode_pembelian', 'like', $prefix.'%')
            ->orderBy('kode_pembelian', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_pembelian, -4)) + 1 : 1;

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getBuktiTransaksiUrlAttribute(): ?string
    {
        return $this->bukti_transaksi ? asset('storage/'.$this->bukti_transaksi) : null;
    }

    public function getIsPdfBuktiAttribute(): bool
    {
        return $this->bukti_transaksi ? str_ends_with(strtolower($this->bukti_transaksi), '.pdf') : false;
    }
}
