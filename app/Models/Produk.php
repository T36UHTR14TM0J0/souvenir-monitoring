<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    public const JENIS = [
        'souvenir_jadi' => 'Souvenir Jadi',
        'bahan_baku' => 'Bahan Baku',
    ];

    protected $fillable = [
        'kategori_id',
        'satuan_id',
        'jenis',
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'tipe_diskon',
        'diskon',
        'stok',
        'stok_minimal',
        'deskripsi',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'diskon' => 'decimal:2',
    ];

    protected $appends = [
        'harga_setelah_diskon',
        'nilai_potongan',
        'has_diskon',
        'label_diskon',
    ];

    public function getHargaSetelahDiskonAttribute(): float
    {
        $hargaJual = (float) $this->harga_jual;
        $diskon = (float) $this->diskon;

        if ($this->tipe_diskon === 'persen' && $diskon > 0) {
            return max(0, round($hargaJual - ($hargaJual * ($diskon / 100)), 2));
        }

        if ($this->tipe_diskon === 'nominal' && $diskon > 0) {
            return max(0, round($hargaJual - $diskon, 2));
        }

        return $hargaJual;
    }

    public function getNilaiPotonganAttribute(): float
    {
        return max(0, round((float) $this->harga_jual - $this->harga_setelah_diskon, 2));
    }

    public function getHasDiskonAttribute(): bool
    {
        return in_array($this->tipe_diskon, ['persen', 'nominal'], true) && (float) $this->diskon > 0;
    }

    public function getLabelDiskonAttribute(): string
    {
        if ($this->tipe_diskon === 'persen' && (float) $this->diskon > 0) {
            $val = (float) $this->diskon;

            return (floor($val) == $val ? (int) $val : number_format($val, 2, ',', '.')).'%';
        }

        if ($this->tipe_diskon === 'nominal' && (float) $this->diskon > 0) {
            return 'Rp '.number_format((float) $this->diskon, 0, ',', '.');
        }

        return '-';
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function scopeSouvenirJadi($query)
    {
        return $query->where('jenis', 'souvenir_jadi');
    }

    public function scopeBahanBaku($query)
    {
        return $query->where('jenis', 'bahan_baku');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? 'Souvenir Jadi';
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
            $number = ((int) $numOnly) + 1;
        } else {
            $number = 1;
        }

        return 'PRD'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
