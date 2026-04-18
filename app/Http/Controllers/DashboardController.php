<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\MutasiAset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getData();
        return view('dashboard.index', $data);
    }

    // API endpoint dipanggil AJAX setiap 30 detik
    public function api()
    {
        $data = $this->getData();

        return response()->json([
            'stats' => [
                'totalAset'       => $data['totalAset'],
                'asetBaik'        => $data['asetBaik'],
                'asetRusak'       => $data['asetRusak'],
                'asetTidakAktif'  => $data['asetTidakAktif'],
                'totalNilaiFormat'=> 'Rp ' . number_format($data['totalNilai'] / 1000000, 1, ',', '.') . 'jt',
                'totalKategori'   => $data['totalKategori'],
                'totalKaryawan'   => $data['totalKaryawan'],
                'persenBaik'      => $data['totalAset'] > 0 ? round($data['asetBaik'] / $data['totalAset'] * 100) : 0,
            ],
            'chartKategori' => $data['asetPerKategori'],
            'chartKondisi'  => $data['asetPerKondisi'],
            'maintenance'   => $data['maintenanceMendatang']->map(fn($m) => [
                'nama'    => $m->aset->nama ?? '-',
                'tanggal' => $m->tanggal_jadwal->format('d M Y'),
                'jenis'   => $m->label_jenis,
                'isToday' => $m->tanggal_jadwal->isToday(),
                'diff'    => $m->tanggal_jadwal->diffForHumans(),
            ]),
            'garansi' => $data['garansiHampirHabis']->map(fn($a) => [
                'nama'    => $a->nama,
                'kode'    => $a->kode_aset,
                'tanggal' => $a->masa_garansi->format('d M Y'),
                'diff'    => $a->masa_garansi->diffForHumans(),
                'habis'   => $a->masa_garansi->isPast(),
            ]),
            'mutasi' => $data['mutasiTerbaru']->map(fn($m) => [
                'aset'    => $m->aset->nama ?? '-',
                'kode'    => $m->aset->kode_aset ?? '-',
                'dari'    => \Str::limit($m->dari_lokasi, 25),
                'ke'      => \Str::limit($m->ke_lokasi, 25),
                'tanggal' => $m->tanggal_mutasi->format('d/m/Y'),
                'alasan'  => \Str::limit($m->alasan, 35),
            ]),
            'lastUpdate' => now()->format('H:i:s'),
        ]);
    }

    private function getData(): array
    {
        $totalAset      = Aset::count();
        $asetBaik       = Aset::where('kondisi', 'baik')->count();
        $asetRusak      = Aset::where('kondisi', 'rusak')->count();
        $asetHilang     = Aset::where('kondisi', 'hilang')->count();
        $asetTidakAktif = Aset::where('kondisi', 'tidak_aktif')->count();
        $totalNilai     = Aset::sum('harga_beli');
        $totalKategori  = Kategori::count();
        $totalDepartemen= Departemen::count();
        $totalKaryawan  = Karyawan::count();
        $totalUser      = User::where('aktif', true)->count();

        $maintenanceMendatang = Pemeliharaan::with('aset')
            ->where('status', 'terjadwal')
            ->whereBetween('tanggal_jadwal', [Carbon::today(), Carbon::today()->addDays(30)])
            ->orderBy('tanggal_jadwal')
            ->take(5)->get();

        $garansiHampirHabis = Aset::whereNotNull('masa_garansi')
            ->where('masa_garansi', '<=', Carbon::today()->addDays(30))
            ->orderBy('masa_garansi')
            ->take(5)->get();

        $asetPerKategori = Aset::selectRaw('kategori_id, count(*) as jumlah')
            ->with('kategori')->groupBy('kategori_id')->get()
            ->map(fn($a) => ['label' => $a->kategori->nama ?? '-', 'jumlah' => (int)$a->jumlah]);

        $asetPerKondisi = Aset::selectRaw('kondisi, count(*) as jumlah')
            ->groupBy('kondisi')->pluck('jumlah', 'kondisi');

        $mutasiTerbaru = MutasiAset::with('aset')->latest()->take(5)->get();

        return compact(
            'totalAset','asetBaik','asetRusak','asetHilang','asetTidakAktif',
            'totalNilai','totalKategori','totalDepartemen','totalKaryawan','totalUser',
            'maintenanceMendatang','garansiHampirHabis',
            'asetPerKategori','asetPerKondisi','mutasiTerbaru'
        );
    }
}
