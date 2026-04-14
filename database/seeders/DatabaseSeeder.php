<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Aset;
use App\Models\Pemeliharaan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS (3 role) =====
        User::create([
            'nama'=>'Administrator SMKN 11','username'=>'admin',
            'email'=>'admin@smkn11tangerang.sch.id',
            'password'=>Hash::make('admin123'),'role'=>'admin',
            'jabatan'=>'Kepala Tata Usaha','aktif'=>true,
        ]);
        User::create([
            'nama'=>'Drs. H. Ahmad Kepsek, M.Pd','username'=>'kepsek',
            'email'=>'kepsek@smkn11tangerang.sch.id',
            'password'=>Hash::make('kepsek123'),'role'=>'kepsek',
            'jabatan'=>'Kepala Sekolah','aktif'=>true,
        ]);
        User::create([
            'nama'=>'Staff Sarana Prasarana','username'=>'staff',
            'email'=>'staff@smkn11tangerang.sch.id',
            'password'=>Hash::make('staff123'),'role'=>'staff',
            'jabatan'=>'Staff Sarana Prasarana','aktif'=>true,
        ]);

        // ===== KATEGORI =====
        $elektronik = Kategori::create(['nama'=>'Elektronik','jenis'=>'elektronik','keterangan'=>'Perangkat elektronik, komputer, lab']);
        $furniture  = Kategori::create(['nama'=>'Furniture','jenis'=>'furniture','keterangan'=>'Meja, kursi, lemari, rak']);
        $kendaraan  = Kategori::create(['nama'=>'Kendaraan','jenis'=>'kendaraan','keterangan'=>'Kendaraan operasional sekolah']);
        $lainnya    = Kategori::create(['nama'=>'Peralatan Praktik','jenis'=>'lainnya','keterangan'=>'Peralatan lab praktik siswa']);

        // ===== DEPARTEMEN =====
        $it      = Departemen::create(['nama'=>'IT & Teknologi','kode'=>'IT','lokasi'=>'Lab Komputer - Gedung A','kepala'=>'Budi Santoso, S.Kom']);
        $tu      = Departemen::create(['nama'=>'Tata Usaha','kode'=>'TU','lokasi'=>'Ruang TU - Gedung Utama','kepala'=>'Sari Dewi, S.Pd']);
        $kur     = Departemen::create(['nama'=>'Kurikulum','kode'=>'KUR','lokasi'=>'Ruang Kurikulum - Gedung A','kepala'=>'Ahmad Fauzi, M.Pd']);
        $sarpras = Departemen::create(['nama'=>'Sarana Prasarana','kode'=>'SAR','lokasi'=>'Gudang Utama - Gedung B','kepala'=>'Rina Hayati, S.Pd']);
        $kesis   = Departemen::create(['nama'=>'Kesiswaan','kode'=>'KES','lokasi'=>'Ruang BK - Gedung A','kepala'=>'Deni Kurniawan, S.Pd']);

        // ===== KARYAWAN =====
        $k1 = Karyawan::create(['nik'=>'197801001','nama'=>'Budi Santoso, S.Kom','jabatan'=>'Kepala Lab Komputer','departemen_id'=>$it->id,'email'=>'budi@smkn11tangerang.sch.id','telepon'=>'081211110001']);
        $k2 = Karyawan::create(['nik'=>'198502002','nama'=>'Andi Pratama, S.T','jabatan'=>'Teknisi IT','departemen_id'=>$it->id,'email'=>'andi@smkn11tangerang.sch.id','telepon'=>'081211110002']);
        $k3 = Karyawan::create(['nik'=>'199001003','nama'=>'Sari Dewi, S.Pd','jabatan'=>'Kepala Tata Usaha','departemen_id'=>$tu->id,'email'=>'sari@smkn11tangerang.sch.id','telepon'=>'081211110003']);
        $k4 = Karyawan::create(['nik'=>'198803004','nama'=>'Rina Hayati, S.Pd','jabatan'=>'Staf Sarana Prasarana','departemen_id'=>$sarpras->id,'email'=>'rina@smkn11tangerang.sch.id','telepon'=>'081211110004']);
        $k5 = Karyawan::create(['nik'=>'199205005','nama'=>'Deni Kurniawan, S.Pd','jabatan'=>'Staf Kesiswaan','departemen_id'=>$kesis->id,'email'=>'deni@smkn11tangerang.sch.id','telepon'=>'081211110005']);

        // ===== ASET =====
        $a1 = Aset::create(['kode_aset'=>'ELK-2025-0001','nama'=>'Laptop Dell Latitude 5420','merek'=>'Dell','serial_number'=>'DL-LAT-5420-001','harga_beli'=>15000000,'tanggal_beli'=>'2022-01-15','masa_garansi'=>now()->addDays(25)->format('Y-m-d'),'kondisi'=>'baik','lokasi'=>'Lab Komputer 1 - Gedung A Lt.2','kategori_id'=>$elektronik->id,'departemen_id'=>$it->id,'penanggung_jawab_id'=>$k1->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>5,'nilai_sisa'=>1500000,'keterangan'=>'Digunakan untuk KBM Lab Komputer 1']);
        $a2 = Aset::create(['kode_aset'=>'ELK-2025-0002','nama'=>'Proyektor Epson EB-S41','merek'=>'Epson','serial_number'=>'EP-EBS41-002','harga_beli'=>8500000,'tanggal_beli'=>'2021-06-10','masa_garansi'=>now()->addDays(18)->format('Y-m-d'),'kondisi'=>'baik','lokasi'=>'Ruang Kelas XII TKJ - Gedung B','kategori_id'=>$elektronik->id,'departemen_id'=>$kur->id,'penanggung_jawab_id'=>$k3->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>5,'nilai_sisa'=>500000]);
        $a3 = Aset::create(['kode_aset'=>'FRN-2025-0001','nama'=>'Meja Guru Kayu Jati Set','merek'=>'Olympic','harga_beli'=>3200000,'tanggal_beli'=>'2020-03-01','kondisi'=>'baik','lokasi'=>'Ruang Guru - Gedung Utama','kategori_id'=>$furniture->id,'departemen_id'=>$tu->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>10,'nilai_sisa'=>320000]);
        $a4 = Aset::create(['kode_aset'=>'KND-2025-0001','nama'=>'Toyota Avanza 2022','merek'=>'Toyota','serial_number'=>'MHKM5EA3JFK999001','harga_beli'=>220000000,'tanggal_beli'=>'2022-08-20','masa_garansi'=>now()->addDays(38)->format('Y-m-d'),'kondisi'=>'baik','lokasi'=>'Parkir Sekolah - Gedung Utama','kategori_id'=>$kendaraan->id,'departemen_id'=>$tu->id,'penanggung_jawab_id'=>$k4->id,'metode_depresiasi'=>'saldo_menurun','umur_ekonomis'=>8,'nilai_sisa'=>50000000]);
        $a5 = Aset::create(['kode_aset'=>'ELK-2025-0003','nama'=>'Server Dell PowerEdge R740','merek'=>'Dell','serial_number'=>'DL-PE-R740-003','harga_beli'=>85000000,'tanggal_beli'=>'2023-01-10','masa_garansi'=>'2026-01-10','kondisi'=>'rusak','lokasi'=>'Server Room - Lab Komputer - Gedung A','kategori_id'=>$elektronik->id,'departemen_id'=>$it->id,'penanggung_jawab_id'=>$k2->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>5,'nilai_sisa'=>5000000,'keterangan'=>'Kerusakan pada PSU — dalam proses perbaikan']);
        $a6 = Aset::create(['kode_aset'=>'ELK-2025-0004','nama'=>'Printer Canon iR 2625i','merek'=>'Canon','serial_number'=>'CN-IR2625-006','harga_beli'=>8200000,'tanggal_beli'=>'2021-08-01','masa_garansi'=>'2024-08-01','kondisi'=>'baik','lokasi'=>'Ruang Tata Usaha - Gedung Utama','kategori_id'=>$elektronik->id,'departemen_id'=>$tu->id,'penanggung_jawab_id'=>$k3->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>5,'nilai_sisa'=>500000]);
        $a7 = Aset::create(['kode_aset'=>'AST-2025-0001','nama'=>'Mesin Las ESAB Warrior 400i','merek'=>'ESAB','serial_number'=>'ESAB-W400-007','harga_beli'=>22000000,'tanggal_beli'=>'2022-03-15','masa_garansi'=>'2025-03-15','kondisi'=>'baik','lokasi'=>'Lab Teknik Pengelasan - Gedung C','kategori_id'=>$lainnya->id,'departemen_id'=>$kur->id,'penanggung_jawab_id'=>$k5->id,'metode_depresiasi'=>'garis_lurus','umur_ekonomis'=>8,'nilai_sisa'=>2000000]);

        // ===== PEMELIHARAAN =====
        Pemeliharaan::create(['aset_id'=>$a1->id,'jenis'=>'rutin','tanggal_jadwal'=>now()->addDays(5)->format('Y-m-d'),'biaya'=>0,'teknisi'=>'Tim IT Internal SMKN 11','status'=>'terjadwal','deskripsi'=>'Pembersihan hardware, update driver, scan antivirus rutin']);
        Pemeliharaan::create(['aset_id'=>$a5->id,'jenis'=>'perbaikan','tanggal_jadwal'=>now()->addDays(2)->format('Y-m-d'),'biaya'=>2500000,'teknisi'=>'CV. Teknologi Maju Tangerang','status'=>'terjadwal','deskripsi'=>'Penggantian PSU server yang mengalami kerusakan']);
        Pemeliharaan::create(['aset_id'=>$a4->id,'jenis'=>'rutin','tanggal_jadwal'=>now()->addDays(10)->format('Y-m-d'),'biaya'=>500000,'teknisi'=>'Bengkel Toyota Authorized Tangerang','status'=>'terjadwal','deskripsi'=>'Service berkala 30.000 km']);
        Pemeliharaan::create(['aset_id'=>$a6->id,'jenis'=>'rutin','tanggal_jadwal'=>now()->subDays(30)->format('Y-m-d'),'tanggal_selesai'=>now()->subDays(28)->format('Y-m-d'),'biaya'=>350000,'teknisi'=>'Canon Service Center Tangerang','status'=>'selesai','deskripsi'=>'Cleaning drum dan penggantian toner','hasil'=>'Printer normal. Drum diganti baru.']);
        Pemeliharaan::create(['aset_id'=>$a2->id,'jenis'=>'rutin','tanggal_jadwal'=>now()->addDays(15)->format('Y-m-d'),'biaya'=>150000,'teknisi'=>'Tim IT Internal SMKN 11','status'=>'terjadwal','deskripsi'=>'Pembersihan lensa proyektor dan cek kondisi lampu']);
    }
}
