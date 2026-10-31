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
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public const TIPE_ALOKASI = [
        'bisnis'  => 'Bisnis (Toko)',
        'pribadi' => 'Pribadi (Prive Pemilik)',
        'kasbon'  => 'Kasbon Karyawan',
    ];

    public const KATEGORI_BISNIS = [
        'operasional'  => 'Operasional Toko',
        'gaji'         => 'Gaji Karyawan',
        'listrik'      => 'Listrik & Air',
        'sewa'         => 'Sewa Tempat',
        'transport'    => 'Transportasi & Bensin',
        'perlengkapan' => 'Perlengkapan & Bahan',
        'pemeliharaan' => 'Pemeliharaan',
        'pajak'        => 'Pajak & Administrasi',
        'lainnya'      => 'Lainnya',
    ];

    public const KATEGORI_PRIBADI = [
        'makan_pribadi'   => 'Makan / Lunch',
        'kebutuhan_rumah' => 'Kebutuhan Rumah (Gas, Galon, Dapur)',
        'prive_penarikan' => 'Penarikan Prive Pemilik',
        'pribadi_lainnya' => 'Pribadi Lainnya',
    ];

    public const KATEGORI_KASBON = [
        'kasbon_karyawan' => 'Kasbon Tim / Karyawan',
    ];

    public const KATEGORI = [
        // Bisnis
        'operasional'     => 'Operasional Toko',
        'gaji'            => 'Gaji Karyawan',
        'listrik'         => 'Listrik & Air',
        'sewa'            => 'Sewa Tempat',
        'transport'       => 'Transportasi & Bensin',
        'perlengkapan'    => 'Perlengkapan & Bahan',
        'pemeliharaan'    => 'Pemeliharaan',
        'pajak'           => 'Pajak & Administrasi',
        'lainnya'         => 'Lainnya',
        // Pribadi
        'makan_pribadi'   => 'Makan / Lunch',
        'kebutuhan_rumah' => 'Kebutuhan Rumah (Gas, Galon, Dapur)',
        'prive_penarikan' => 'Penarikan Prive Pemilik',
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
        $prefix = 'PGL-' . now()->format('Ymd') . '-';
        $last   = self::where('kode_pengeluaran', 'like', $prefix . '%')
            ->orderBy('kode_pengeluaran', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_pengeluaran, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
