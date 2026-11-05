<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianBahan extends Model
{
    use HasFactory;

    protected $table = 'pemakaian_bahans';

    protected $fillable = [
        'kode_pemakaian',
        'tanggal',
        'nama_operator',
        'keperluan',
        'total_item',
        'total_kuantitas',
        'catatan',
        'bukti_pengambilan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_item' => 'integer',
        'total_kuantitas' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PemakaianBahanDetail::class, 'pemakaian_bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateKodePemakaian(): string
    {
        $prefix = 'PBB-'.now()->format('Ymd').'-';
        $last = self::where('kode_pemakaian', 'like', $prefix.'%')
            ->orderBy('kode_pemakaian', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->kode_pemakaian, -4)) + 1 : 1;

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getBuktiPengambilanUrlAttribute(): ?string
    {
        return $this->bukti_pengambilan ? asset('storage/'.$this->bukti_pengambilan) : null;
    }

    public function getIsPdfBuktiAttribute(): bool
    {
        return $this->bukti_pengambilan ? str_ends_with(strtolower($this->bukti_pengambilan), '.pdf') : false;
    }
}
