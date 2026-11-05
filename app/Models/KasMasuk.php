<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasMasuk extends Model
{
    use HasFactory;

    protected $table = 'kas_masuks';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'tipe',
        'sumber',
        'jumlah',
        'keterangan',
        'catatan',
        'bukti_transaksi',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public const TIPE = [
        'modal_pemilik' => 'Suntikan Modal Pemilik (Debit Kas)',
        'pendapatan_lain' => 'Pendapatan Lain-lain',
    ];

    public const SUMBER = [
        'Debit / Transfer' => 'Debit / Transfer Bank Pribadi',
        'Tunai / Cash' => 'Tunai / Uang Cash',
        'Lainnya' => 'Sumber Lainnya',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTipeLabelAttribute(): string
    {
        return self::TIPE[$this->tipe] ?? ucfirst(str_replace('_', ' ', $this->tipe));
    }

    public static function generateKodeTransaksi(): string
    {
        $prefix = 'KM-'.now()->format('Ymd').'-';
        $last = self::where('kode_transaksi', 'like', $prefix.'%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_transaksi, -4)) + 1 : 1;

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
