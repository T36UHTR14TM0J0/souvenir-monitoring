<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pengeluaran', 'like', '%'.$request->search.'%')
                    ->orWhere('keterangan', 'like', '%'.$request->search.'%')
                    ->orWhere('karyawan_nama', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('tipe_alokasi')) {
            $query->where('tipe_alokasi', $request->tipe_alokasi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->filled('status_kasbon')) {
            $query->where('status_kasbon', $request->status_kasbon);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('tanggal')->oldest('id'),
            'jumlah_desc' => $query->orderByDesc('jumlah')->latest('tanggal'),
            'jumlah_asc' => $query->orderBy('jumlah')->latest('tanggal'),
            default => $query->latest('tanggal')->latest('id'),
        };

        $pengeluarans = $query->paginate(10)->withQueryString();

        $hasDateFilter = $request->filled('dari_tanggal') || $request->filled('sampai_tanggal') || $request->filled('bulan') || $request->filled('tahun');

        $statsQuery = Pengeluaran::query();

        if ($hasDateFilter) {
            if ($request->filled('dari_tanggal')) {
                $statsQuery->whereDate('tanggal', '>=', $request->dari_tanggal);
            }
            if ($request->filled('sampai_tanggal')) {
                $statsQuery->whereDate('tanggal', '<=', $request->sampai_tanggal);
            }
            if ($request->filled('bulan')) {
                $statsQuery->whereMonth('tanggal', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $statsQuery->whereYear('tanggal', $request->tahun);
            }

            if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
                $periodeLabel = Carbon::parse($request->dari_tanggal)->translatedFormat('d M Y').' - '.Carbon::parse($request->sampai_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('dari_tanggal')) {
                $periodeLabel = 'Mulai '.Carbon::parse($request->dari_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('sampai_tanggal')) {
                $periodeLabel = 's/d '.Carbon::parse($request->sampai_tanggal)->translatedFormat('d M Y');
            } elseif ($request->filled('bulan') && $request->filled('tahun')) {
                $periodeLabel = Carbon::createFromDate($request->tahun, $request->bulan, 1)->translatedFormat('F Y');
            } elseif ($request->filled('bulan')) {
                $periodeLabel = Carbon::createFromDate(now()->year, $request->bulan, 1)->translatedFormat('F');
            } else {
                $periodeLabel = 'Tahun '.$request->tahun;
            }
        } else {
            // Anchor cerdas: jika bulan saat ini belum memiliki data (misal seeder selesai Okt 2026),
            // otomatis gunakan bulan transaksi terakhir yang tersedia agar card statistik terisi data aktual
            $hasCurrentMonthData = Pengeluaran::whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->exists();

            if ($hasCurrentMonthData) {
                $anchorDate = now();
            } else {
                $latestDate = Pengeluaran::max('tanggal');
                $anchorDate = $latestDate ? Carbon::parse($latestDate) : now();
            }

            $periodeLabel = $anchorDate->translatedFormat('F Y');
            $statsQuery->whereMonth('tanggal', $anchorDate->month)
                ->whereYear('tanggal', $anchorDate->year);
        }

        $totalBulanIni = (float) (clone $statsQuery)->sum('jumlah');
        $totalBisnisBulanIni = (float) (clone $statsQuery)->bisnis()->sum('jumlah');
        $totalModalBahanBulanIni = (float) (clone $statsQuery)->modalBahan()->sum('jumlah');
        $totalOperasionalBulanIni = (float) (clone $statsQuery)->operasionalMurni()->sum('jumlah');
        $totalPribadiBulanIni = (float) (clone $statsQuery)->pribadi()->sum('jumlah');
        $totalKasbonBulanIni = (float) (clone $statsQuery)->kasbon()->sum('jumlah');
        $totalTransaksi = (int) (clone $statsQuery)->count();

        $totalAkumulasiPengeluaran = (float) Pengeluaran::sum('jumlah');
        $totalTransaksiSemua = (int) Pengeluaran::count();

        $kategoris = Pengeluaran::KATEGORI;
        $tipeAlokasis = Pengeluaran::TIPE_ALOKASI;

        return view('pengeluaran.index', compact(
            'pengeluarans',
            'periodeLabel',
            'totalBulanIni',
            'totalBisnisBulanIni',
            'totalModalBahanBulanIni',
            'totalOperasionalBulanIni',
            'totalPribadiBulanIni',
            'totalKasbonBulanIni',
            'totalTransaksi',
            'totalAkumulasiPengeluaran',
            'totalTransaksiSemua',
            'kategoris',
            'tipeAlokasis'
        ));
    }

    public function create()
    {
        $kodePengeluaran = Pengeluaran::generateKodePengeluaran();
        $tipeAlokasis = Pengeluaran::TIPE_ALOKASI;
        $kategoriBisnis = Pengeluaran::KATEGORI_BISNIS;
        $kategoriPribadi = Pengeluaran::KATEGORI_PRIBADI;
        $kategoriKasbon = Pengeluaran::KATEGORI_KASBON;
        $kategoris = Pengeluaran::KATEGORI;

        return view('pengeluaran.create', compact(
            'kodePengeluaran',
            'tipeAlokasis',
            'kategoriBisnis',
            'kategoriPribadi',
            'kategoriKasbon',
            'kategoris'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe_alokasi' => ['required', 'string', 'in:bisnis,pribadi,kasbon'],
            'kategori' => ['required', 'string', 'in:'.implode(',', array_keys(Pengeluaran::KATEGORI))],
            'keterangan' => ['required', 'string', 'max:255'],
            'karyawan_nama' => ['nullable', 'string', 'max:100', 'required_if:tipe_alokasi,kasbon'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_transaksi' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], $this->validationMessages(), $this->validationAttributes());

        $statusKasbon = null;
        if ($validated['tipe_alokasi'] === 'kasbon') {
            $statusKasbon = $request->input('status_kasbon', 'belum_lunas');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_transaksi')) {
            $buktiPath = $request->file('bukti_transaksi')->store('bukti-transaksi/pengeluaran', 'public');
        }

        $pengeluaran = Pengeluaran::create([
            'kode_pengeluaran' => Pengeluaran::generateKodePengeluaran(),
            'tanggal' => $validated['tanggal'],
            'tipe_alokasi' => $validated['tipe_alokasi'],
            'kategori' => $validated['kategori'],
            'keterangan' => $validated['keterangan'],
            'karyawan_nama' => $validated['karyawan_nama'] ?? null,
            'status_kasbon' => $statusKasbon,
            'jumlah' => $validated['jumlah'],
            'catatan' => $validated['catatan'] ?? null,
            'bukti_transaksi' => $buktiPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$pengeluaran->kode_pengeluaran}\" berhasil disimpan.");
    }

    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load('user');

        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $tipeAlokasis = Pengeluaran::TIPE_ALOKASI;
        $kategoriBisnis = Pengeluaran::KATEGORI_BISNIS;
        $kategoriPribadi = Pengeluaran::KATEGORI_PRIBADI;
        $kategoriKasbon = Pengeluaran::KATEGORI_KASBON;
        $kategoris = Pengeluaran::KATEGORI;

        return view('pengeluaran.edit', compact(
            'pengeluaran',
            'tipeAlokasis',
            'kategoriBisnis',
            'kategoriPribadi',
            'kategoriKasbon',
            'kategoris'
        ));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe_alokasi' => ['required', 'string', 'in:bisnis,pribadi,kasbon'],
            'kategori' => ['required', 'string', 'in:'.implode(',', array_keys(Pengeluaran::KATEGORI))],
            'keterangan' => ['required', 'string', 'max:255'],
            'karyawan_nama' => ['nullable', 'string', 'max:100', 'required_if:tipe_alokasi,kasbon'],
            'status_kasbon' => ['nullable', 'string', 'in:belum_lunas,lunas'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'bukti_transaksi' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'hapus_bukti' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], $this->validationMessages(), $this->validationAttributes());

        if ($validated['tipe_alokasi'] !== 'kasbon') {
            $validated['karyawan_nama'] = null;
            $validated['status_kasbon'] = null;
        }

        if ($request->hasFile('bukti_transaksi')) {
            if ($pengeluaran->bukti_transaksi) {
                Storage::disk('public')->delete($pengeluaran->bukti_transaksi);
            }
            $validated['bukti_transaksi'] = $request->file('bukti_transaksi')->store('bukti-transaksi/pengeluaran', 'public');
        } elseif ($request->boolean('hapus_bukti')) {
            if ($pengeluaran->bukti_transaksi) {
                Storage::disk('public')->delete($pengeluaran->bukti_transaksi);
            }
            $validated['bukti_transaksi'] = null;
        }

        $pengeluaran->update($validated);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$pengeluaran->kode_pengeluaran}\" berhasil diupdate.");
    }

    public function toggleKasbon(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->tipe_alokasi !== 'kasbon') {
            return back()->with('error', 'Transaksi ini bukan kasbon.');
        }

        $newStatus = ($pengeluaran->status_kasbon === 'lunas') ? 'belum_lunas' : 'lunas';
        $pengeluaran->update(['status_kasbon' => $newStatus]);

        $pesan = $newStatus === 'lunas' ? 'Kasbon berhasil ditandai LUNAS.' : 'Status kasbon diubah menjadi BELUM LUNAS.';

        return back()->with('success', $pesan);
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $kode = $pengeluaran->kode_pengeluaran;
        if ($pengeluaran->bukti_transaksi) {
            Storage::disk('public')->delete($pengeluaran->bukti_transaksi);
        }
        $pengeluaran->delete();

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', "Pengeluaran \"{$kode}\" berhasil dihapus.");
    }

    private function validationMessages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tipe_alokasi.required' => 'Alokasi wajib dipilih.',
            'tipe_alokasi.in' => 'Pilihan alokasi tidak valid.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            'karyawan_nama.required_if' => 'Nama karyawan/peminjam wajib diisi untuk alokasi Kasbon.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal Rp 1.',
            'catatan.max' => 'Catatan maksimal 500 karakter.',
            'bukti_transaksi.file' => 'Bukti transaksi harus berupa file.',
            'bukti_transaksi.mimes' => 'Format bukti transaksi harus JPG, PNG, WEBP, atau PDF.',
            'bukti_transaksi.max' => 'Ukuran bukti transaksi maksimal 5MB.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'tanggal' => 'Tanggal',
            'tipe_alokasi' => 'Alokasi Pengeluaran',
            'kategori' => 'Kategori',
            'keterangan' => 'Keterangan',
            'karyawan_nama' => 'Nama Karyawan/Peminjam',
            'jumlah' => 'Jumlah',
            'catatan' => 'Catatan',
        ];
    }
}
