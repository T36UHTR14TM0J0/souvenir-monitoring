<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'kode_pengeluaran',
        'tanggal',
        'tipe_alokasi',
        'kategori',
        'keterangan',
        'karyawan_nama',
        'status_kasbon',
        'jumlah',
        'catatan',
        'bukti_transaksi',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public const TIPE_ALOKASI = [
        'bisnis' => 'Bisnis (Toko)',
        'pribadi' => 'Pribadi (Prive Pemilik)',
        'kasbon' => 'Kasbon Karyawan',
    ];

    public const KATEGORI_BISNIS = [
        'bahan_baku' => 'Bahan Baku & Material (Akrilik, Kain, Silikon, Tumbler, dll)',
        'jasa_produksi' => 'Jasa Luar (Cetak, Sablon & Potong)',
        'packing' => 'Packing & Pengemasan (Bubble wrap, lakban, kardus)',
        'iklan' => 'Iklan Marketplace (Shopee, Lazada, TikTok Ads)',
        'operasional' => 'Operasional Toko & Workshop',
        'konsumsi' => 'Konsumsi & Makan (Lunch Tim)',
        'transport' => 'Transportasi, Bensin & Parkir',
        'gaji' => 'Gaji Karyawan',
        'listrik' => 'Listrik, Air & Internet',
        'sewa' => 'Sewa Tempat / Gudang',
        'pemeliharaan' => 'Pemeliharaan & Sparepart Alat',
        'perlengkapan' => 'Perlengkapan & Bahan Souvenir',
        'pajak' => 'Pajak & Administrasi',
        'lainnya' => 'Lainnya',
    ];

    public const KATEGORI_PRIBADI = [
        'prive_penarikan' => 'Penarikan Prive Pemilik',
        'makan_pribadi' => 'Makan / Lunch Pribadi',
        'kebutuhan_rumah' => 'Kebutuhan Rumah (Gas, Galon, Dapur)',
        'pribadi_lainnya' => 'Pribadi Lainnya',
    ];

    public const KATEGORI_KASBON = [
        'kasbon_karyawan' => 'Kasbon Tim / Karyawan',
    ];

    public const KATEGORI = [
        // Bisnis - Modal Bahan & Produksi
        'bahan_baku' => 'Bahan Baku & Material (Akrilik, Kain, Silikon, Tumbler, dll)',
        'jasa_produksi' => 'Jasa Luar (Cetak, Sablon & Potong)',
        'perlengkapan' => 'Perlengkapan & Bahan Souvenir',
        // Bisnis - Operasional Toko
        'packing' => 'Packing & Pengemasan (Bubble wrap, lakban, kardus)',
        'iklan' => 'Iklan Marketplace (Shopee, Lazada, TikTok Ads)',
        'operasional' => 'Operasional Toko & Workshop',
        'konsumsi' => 'Konsumsi & Makan (Lunch Tim)',
        'transport' => 'Transportasi, Bensin & Parkir',
        'gaji' => 'Gaji Karyawan',
        'listrik' => 'Listrik, Air & Internet',
        'sewa' => 'Sewa Tempat / Gudang',
        'pemeliharaan' => 'Pemeliharaan & Sparepart Alat',
        'pajak' => 'Pajak & Administrasi',
        'lainnya' => 'Lainnya',
        // Pribadi
        'prive_penarikan' => 'Penarikan Prive Pemilik',
        'makan_pribadi' => 'Makan / Lunch Pribadi',
        'kebutuhan_rumah' => 'Kebutuhan Rumah (Gas, Galon, Dapur)',
        'pribadi_lainnya' => 'Pribadi Lainnya',
        // Kasbon
        'kasbon_karyawan' => 'Kasbon Tim / Karyawan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? ucfirst($this->kategori);
    }

    public function getTipeAlokasiLabelAttribute(): string
    {
        return self::TIPE_ALOKASI[$this->tipe_alokasi] ?? ucfirst($this->tipe_alokasi);
    }

    // Scopes
    public function scopeBisnis($query)
    {
        return $query->where('tipe_alokasi', 'bisnis');
    }

    public function scopeModalBahan($query)
    {
        return $query->where('tipe_alokasi', 'bisnis')
            ->whereIn('kategori', ['bahan_baku', 'perlengkapan', 'jasa_produksi']);
    }

    public function scopeOperasionalMurni($query)
    {
        return $query->where('tipe_alokasi', 'bisnis')
            ->whereNotIn('kategori', ['bahan_baku', 'perlengkapan', 'jasa_produksi']);
    }

    public function scopePribadi($query)
    {
        return $query->where('tipe_alokasi', 'pribadi');
    }

    public function scopeKasbon($query)
    {
        return $query->where('tipe_alokasi', 'kasbon');
    }

    public static function generateKodePengeluaran(): string
    {
        $prefix = 'PGL-'.now()->format('Ymd').'-';
        $last = self::where('kode_pengeluaran', 'like', $prefix.'%')
            ->orderBy('kode_pengeluaran', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_pengeluaran, -4)) + 1 : 1;

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
