@extends('layouts.app')
@section('title', 'Laporan - Sisten Perpustakaan')

@section('content')

<h2 class="font-weight-bold mb-3">Laporan Perpustakaan</h2>
<!--bagian cetak laporan-->
<div class="laporan-card mb-5">
<form action="{{ route('laporan.cetak') }}" method="POST" target="_blank">
    @csrf

    <label>Jenis Laporan</label>
    <select name="jenis" class="form-control laporan-input" required>
        <option value="" disabled selected>Pilih jenis laporan...</option>
        <option value="peminjaman">Peminjaman</option>
        <option value="pengembalian">Pengembalian</option>
        <option value="denda">Denda</option>
    </select>

    <label class="mt-3">Dari Tanggal</label>
    <input type="date" name="dari" class="form-control laporan-input" required>

    <label class="mt-3">Sampai Tanggal</label>
    <input type="date" name="sampai" class="form-control laporan-input" required>

    <button type="submit" class="btn-laporan mt-3">
        <i class="fas fa-print mr-1"></i> Cetak Laporan
    </button>
</form>
</div>

<!--informasi total totalan-->
<div>
    <div class="laporan-card mb-3" style="padding: 20px">
        <h5>Total Buku</h5>
        <h2 class="font-weight-bold" style="font-size: 40px; color: #2A85EB">
            {{ $totalBuku ?? 0 }}
        </h2>
    </div>
    <div class="laporan-card mb-3" style="padding: 20px">
        <h5>Total Anggota</h5>
        <h2 class="font-weight-bold" style="font-size: 40px; color: #2DE367">
            {{ $totalAnggota ?? 0 }}
        </h2>
    </div>
    <div class="laporan-card mb-3" style="padding: 20px">
        <h5>Sedang Dipinjam</h5>
        <h2 class="font-weight-bold" style="font-size: 40px; color: #FF4242">
            {{ $totalDipinjam ?? 0 }}
        </h2>
    </div>
    <div class="laporan-card mb-5" style="padding: 20px">
        <h5>Total Transaksi</h5>
        <h2 class="font-weight-bold" style="font-size: 40px; color: #245DAD">
            {{ $totalTransaksi ?? 0 }}
        </h2>
    </div>
</div>

<!--ringkasan transaksin-->
<div class="laporan-card mb-3">
    <h5 class="font-weight-bold">Total Transaksi</h5>
    <!--tabel ringkasan-->
    <table class="table-ringkasan">
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Transaksi</td>
                <td>{{ $totalTransaksi ?? 0 }}</td>
            </tr>
            <tr>
                <td>Sedang Dipinjam</td>
                <td>{{ $totalDipinjam ?? 0 }}</td>
            </tr>
            <tr>
                <td>Sudah Dikembalikan</td>
                <td>{{ $totalDikembalikan ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Denda Belum Dibayar</td>
                <td class="text-red">Rp {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Denda Sudah Dibayar</td>
                <td class="text-green">Rp {{ number_format($totalDendaSudahBayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
