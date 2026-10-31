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
        'kategori',
        'keterangan',
        'jumlah',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public const KATEGORI = [
        'operasional'  => 'Operasional',
        'gaji'         => 'Gaji Karyawan',
        'listrik'      => 'Listrik & Air',
        'sewa'         => 'Sewa Tempat',
        'transport'    => 'Transportasi',
        'perlengkapan' => 'Perlengkapan',
        'pemeliharaan' => 'Pemeliharaan',
        'pajak'        => 'Pajak & Administrasi',
        'lainnya'      => 'Lainnya',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? ucfirst($this->kategori);
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