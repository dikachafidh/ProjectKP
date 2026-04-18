<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Aset — {{ $aset->kode_aset }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }
        .header { border-bottom: 2px solid #0f4c75; padding-bottom: 10px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-start; }
        .logo-area h1 { font-size: 16px; font-weight: bold; color: #0f4c75; }
        .logo-area p { font-size: 10px; color: #64748b; margin-top: 2px; }
        .kode-box { background: #0f4c75; color: white; padding: 8px 14px; border-radius: 8px; text-align: center; }
        .kode-box .kode { font-size: 14px; font-weight: bold; letter-spacing: 1px; }
        .kode-box .lbl { font-size: 9px; opacity: .75; }
        h2 { font-size: 15px; font-weight: bold; color: #0f172a; margin-bottom: 14px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 14px; }
        .row { display: flex; border-bottom: 1px solid #f1f5f9; }
        .row:last-child { border-bottom: none; }
        .row .lbl { background: #f8fafc; padding: 7px 12px; font-weight: bold; color: #475569; width: 38%; font-size: 10px; border-right: 1px solid #f1f5f9; }
        .row .val { padding: 7px 12px; font-size: 11px; }
        .kondisi-baik { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .kondisi-rusak { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .footer { border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 16px; display: flex; justify-content: space-between; }
        .ttd { text-align: center; }
        .ttd .nama-ttd { border-top: 1px solid #1e293b; padding-top: 4px; margin-top: 50px; font-size: 10px; }
        .badge-permanen { background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; font-size: 10px; }
        .badge-nonpermanen { background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 12px; font-size: 10px; }
        @media print { body { padding: 0; } }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-area">
        <h1>🏫 KARTU INVENTARIS ASET</h1>
        <p>SMKN 11 Kota Tangerang — Sistem Informasi Manajemen Aset</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }} oleh: {{ auth()->user()->nama }}</p>
    </div>
    <div class="kode-box">
        <div class="lbl">KODE ASET</div>
        <div class="kode">{{ $aset->kode_aset }}</div>
        <div class="lbl">{{ $aset->serial_number }}</div>
    </div>
</div>

<h2>{{ $aset->nama }}</h2>

<div class="grid">
    <div class="row"><div class="lbl">Merek</div><div class="val">{{ $aset->merek ?: '—' }}</div></div>
    <div class="row"><div class="lbl">Kategori</div><div class="val">{{ $aset->kategori->nama ?? '—' }}</div></div>
    <div class="row"><div class="lbl">Divisi</div><div class="val">{{ $aset->departemen->nama ?? '—' }}</div></div>
    <div class="row"><div class="lbl">Lokasi</div><div class="val">{{ $aset->lokasi }}</div></div>
    <div class="row">
        <div class="lbl">Kondisi</div>
        <div class="val">
            <span class="{{ $aset->kondisi === 'baik' ? 'kondisi-baik' : 'kondisi-rusak' }}">
                {{ ucfirst($aset->kondisi) }}
            </span>
        </div>
    </div>
    <div class="row">
        <div class="lbl">Sifat</div>
        <div class="val">
            <span class="{{ $aset->is_permanen ? 'badge-permanen' : 'badge-nonpermanen' }}">
                {{ $aset->is_permanen ? 'Permanen' : 'Non-Permanen' }}
            </span>
        </div>
    </div>
    <div class="row"><div class="lbl">Tanggal Beli</div><div class="val">{{ $aset->tanggal_beli->format('d/m/Y') }}</div></div>
    <div class="row">
        <div class="lbl">Masa Garansi</div>
        <div class="val">{{ $aset->masa_garansi ? $aset->masa_garansi->format('d/m/Y') : '—' }}</div>
    </div>
    @if($aset->keterangan)
    <div class="row"><div class="lbl">Keterangan</div><div class="val" style="grid-column:span 2">{{ $aset->keterangan }}</div></div>
    @endif
</div>

<div class="footer">
    <div class="ttd">
        <div>Kepala Sekolah</div>
        <div class="nama-ttd">( __________________________ )</div>
        <div style="font-size:9px;color:#64748b">NIP. ____________________</div>
    </div>
    <div class="ttd">
        <div>Kepala Tata Usaha</div>
        <div class="nama-ttd">( __________________________ )</div>
        <div style="font-size:9px;color:#64748b">NIP. ____________________</div>
    </div>
    <div class="ttd">
        <div>Tangerang, {{ now()->isoFormat('D MMMM Y') }}</div>
        <div class="nama-ttd">( __________________________ )</div>
        <div style="font-size:9px;color:#64748b">Pengelola Aset</div>
    </div>
</div>

<script>window.onload = () => window.print();</script>
</body>
</html>
