<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    public const CHANNELS = [
        'shopee' => 'Shopee',
        'lazada' => 'Lazada',
        'tiktok' => 'TikTok Shop',
    ];

    public const STATUS_PESANAN = [
        'siap_packing' => 'Siap Packing',
        'perlu_dikirim' => 'Siap Dikirim',
        'dikirim' => 'Sedang Dikirim',
        'selesai' => 'Selesai',
        'retur' => 'Retur / Komplain',
        'batal' => 'Dibatalkan',
    ];

    protected $fillable = [
        'channel',
        'kode_transaksi',
        'no_pesanan_marketplace',
        'no_resi',
        'kurir',
        'tanggal',
        'nama_pelanggan',
        'total_harga',
        'total_diskon',
        'grand_total',
        'biaya_admin_marketplace',
        'penghasilan_bersih',
        'status_pesanan',
        'metode_bayar',
        'catatan',
        'bukti_transaksi',
        'user_id',
        'bahan_baku_dipotong',
        'waktu_potong_bahan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_harga' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'biaya_admin_marketplace' => 'decimal:2',
        'penghasilan_bersih' => 'decimal:2',
        'bahan_baku_dipotong' => 'boolean',
        'waktu_potong_bahan' => 'datetime',
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

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    // ============================================
    // BACKWARD COMPATIBILITY ACCESSORS / MUTATORS
    // ============================================
    public function getNoPesananShopeeAttribute(): ?string
    {
        return $this->no_pesanan_marketplace;
    }

    public function setNoPesananShopeeAttribute($value): void
    {
        $this->attributes['no_pesanan_marketplace'] = $value;
    }

    public function getBiayaAdminShopeeAttribute(): float
    {
        return (float) ($this->biaya_admin_marketplace ?? 0);
    }

    public function setBiayaAdminShopeeAttribute($value): void
    {
        $this->attributes['biaya_admin_marketplace'] = $value;
    }

    // ============================================
    // HELPER & LABELS
    // ============================================
    public static function generateKodeTransaksi(): string
    {
        $prefix = 'TRX-'.now()->format('Ymd').'-';
        $last = self::where('kode_transaksi', 'like', $prefix.'%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_transaksi, -4)) + 1 : 1;

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getChannelLabelAttribute(): string
    {
        return self::CHANNELS[$this->channel] ?? ucfirst($this->channel ?? 'Shopee');
    }

    public function getChannelBadgeConfigAttribute(): array
    {
        return match ($this->channel) {
            'shopee' => [
                'bg' => '#fff7ed',
                'color' => '#c2410c',
                'border' => '#ffedd5',
                'icon' => 'fa-bag-shopping',
                'label' => 'Shopee',
            ],
            'lazada' => [
                'bg' => '#eff6ff',
                'color' => '#1d4ed8',
                'border' => '#dbeafe',
                'icon' => 'fa-cart-shopping',
                'label' => 'Lazada',
            ],
            'tiktok' => [
                'bg' => '#f8fafc',
                'color' => '#0f172a',
                'border' => '#e2e8f0',
                'icon' => 'fa-store',
                'label' => 'TikTok Shop',
            ],
            default => [
                'bg' => '#f1f5f9',
                'color' => '#475569',
                'border' => '#e2e8f0',
                'icon' => 'fa-shop',
                'label' => ucfirst($this->channel ?? 'Online'),
            ],
        };
    }

    public function getStatusPesananLabelAttribute(): string
    {
        return self::STATUS_PESANAN[$this->status_pesanan] ?? ucfirst($this->status_pesanan ?? 'Selesai');
    }

    public function getStatusPesananBadgeConfigAttribute(): array
    {
        return match ($this->status_pesanan) {
            'siap_packing' => [
                'bg' => '#fef3c7',
                'color' => '#92400e',
                'border' => '#fde68a',
                'dot' => '#f59e0b',
                'label' => 'Siap Packing',
            ],
            'perlu_dikirim' => [
                'bg' => '#fffbeb',
                'color' => '#b45309',
                'border' => '#fde68a',
                'dot' => '#d97706',
                'label' => 'Siap Dikirim',
            ],
            'dikirim' => [
                'bg' => '#eff6ff',
                'color' => '#1e40af',
                'border' => '#bfdbfe',
                'dot' => '#3b82f6',
                'label' => 'Sedang Dikirim',
            ],
            'selesai' => [
                'bg' => '#ecfdf5',
                'color' => '#065f46',
                'border' => '#a7f3d0',
                'dot' => '#10b981',
                'label' => 'Selesai',
            ],
            'retur' => [
                'bg' => '#f5f3ff',
                'color' => '#5b21b6',
                'border' => '#ddd6fe',
                'dot' => '#8b5cf6',
                'label' => 'Retur / Komplain',
            ],
            'batal' => [
                'bg' => '#fef2f2',
                'color' => '#991b1b',
                'border' => '#fecaca',
                'dot' => '#ef4444',
                'label' => 'Dibatalkan',
            ],
            default => [
                'bg' => '#f1f5f9',
                'color' => '#334155',
                'border' => '#cbd5e1',
                'dot' => '#64748b',
                'label' => ucfirst($this->status_pesanan ?? 'Selesai'),
            ],
        };
    }

    public function getMetodeBayarLabelAttribute(): string
    {
        return match ($this->channel) {
            'shopee' => 'Saldo Penjual Shopee',
            'lazada' => 'Saldo Penjual Lazada',
            'tiktok' => 'Saldo TikTok Shop',
            default => 'Saldo Penjual Online',
        };
    }

    public function getBuktiTransaksiUrlAttribute(): ?string
    {
        return $this->bukti_transaksi ? asset('storage/'.$this->bukti_transaksi) : null;
    }

    public function getIsPdfBuktiAttribute(): bool
    {
        return $this->bukti_transaksi ? str_ends_with(strtolower($this->bukti_transaksi), '.pdf') : false;
    }

    /**
     * Hitung total rincian bahan baku yang dibutuhkan transaksi ini berdasarkan Resep BOM.
     */
    public function getRincianKebutuhanBahanAttribute(): array
    {
        $this->loadMissing('details.produk.resepBahanBaku.satuan');
        $bahanList = [];

        foreach ($this->details as $detail) {
            $produk = $detail->produk;
            if (! $produk) {
                continue;
            }

            foreach ($produk->resepBahanBaku as $bahan) {
                $qtyDibutuhkan = (float) $bahan->pivot->jumlah * (int) $detail->qty;
                $key = $bahan->id;

                if (! isset($bahanList[$key])) {
                    $bahanList[$key] = [
                        'bahan_id' => $bahan->id,
                        'nama_bahan' => $bahan->nama_produk,
                        'kode_bahan' => $bahan->kode_produk,
                        'satuan' => $bahan->satuan->singkatan ?? 'pcs',
                        'qty_kebutuhan' => 0,
                        'stok_gudang' => (int) $bahan->stok,
                    ];
                }

                $bahanList[$key]['qty_kebutuhan'] += $qtyDibutuhkan;
            }
        }

        return array_values($bahanList);
    }

    /**
     * Potong stok bahan baku otomatis saat pesanan masuk tahapan produksi workshop.
     */
    public function potongBahanBakuOtomatis(): bool
    {
        if ($this->bahan_baku_dipotong) {
            return false;
        }

        $this->loadMissing('details.produk.resepBahanBaku');

        foreach ($this->details as $detail) {
            $produk = $detail->produk;
            if (! $produk || $produk->resepBahanBaku->isEmpty()) {
                continue;
            }

            foreach ($produk->resepBahanBaku as $bahan) {
                $kebutuhan = (float) $bahan->pivot->jumlah * (int) $detail->qty;
                if ($kebutuhan > 0) {
                    $bahan->decrement('stok', $kebutuhan);
                }
            }
        }

        $this->update([
            'bahan_baku_dipotong' => true,
            'waktu_potong_bahan' => now(),
        ]);

        return true;
    }

    /**
     * Kembalikan stok bahan baku jika pesanan dibatalkan setelah bahan dipotong.
     */
    public function kembalikanBahanBaku(): bool
    {
        if (! $this->bahan_baku_dipotong) {
            return false;
        }

        $this->loadMissing('details.produk.resepBahanBaku');

        foreach ($this->details as $detail) {
            $produk = $detail->produk;
            if (! $produk || $produk->resepBahanBaku->isEmpty()) {
                continue;
            }

            foreach ($produk->resepBahanBaku as $bahan) {
                $kebutuhan = (float) $bahan->pivot->jumlah * (int) $detail->qty;
                if ($kebutuhan > 0) {
                    $bahan->increment('stok', $kebutuhan);
                }
            }
        }

        $this->update([
            'bahan_baku_dipotong' => false,
            'waktu_potong_bahan' => null,
        ]);

        return true;
    }
}
