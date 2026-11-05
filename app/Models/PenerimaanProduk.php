<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanProduk extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_produks';

    protected $fillable = [
        'kode_penerimaan',
        'tanggal',
        'nama_petugas',
        'nama_penanggung_jawab',
        'tipe_produksi',
        'no_referensi_pemakaian',
        'total_item',
        'total_qty_bagus',
        'total_bagus',
        'total_qty_reject',
        'total_reject',
        'catatan',
        'bukti_dokumen',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_item' => 'integer',
        'total_qty_bagus' => 'decimal:2',
        'total_qty_reject' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PenerimaanProdukDetail::class, 'penerimaan_produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKodePenerimaan(): string
    {
        $prefix = 'HPD-'.now()->format('Ymd').'-'; // Hasil Produksi Masuk
        $last = self::where('kode_penerimaan', 'like', $prefix.'%')
            ->orderBy('kode_penerimaan', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_penerimaan, -4)) + 1 : 1;

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getBuktiDokumenUrlAttribute(): ?string
    {
        return $this->bukti_dokumen ? asset('storage/'.$this->bukti_dokumen) : null;
    }

    public function getIsPdfBuktiAttribute(): bool
    {
        return $this->bukti_dokumen ? str_ends_with(strtolower($this->bukti_dokumen), '.pdf') : false;
    }

    public function getNamaPenanggungJawabAttribute(): ?string
    {
        return $this->nama_petugas;
    }

    public function setNamaPenanggungJawabAttribute($value): void
    {
        $this->attributes['nama_petugas'] = $value;
    }

    public function getTotalBagusAttribute(): float
    {
        return (float) ($this->total_qty_bagus ?? 0);
    }

    public function setTotalBagusAttribute($value): void
    {
        $this->attributes['total_qty_bagus'] = $value;
    }

    public function getTotalRejectAttribute(): float
    {
        return (float) ($this->total_qty_reject ?? 0);
    }

    public function setTotalRejectAttribute($value): void
    {
        $this->attributes['total_qty_reject'] = $value;
    }
}
