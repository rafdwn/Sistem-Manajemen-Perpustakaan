@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<h2 class="font-weight-bold mb-4">Kelola Denda</h2>

<!-- belom bayar denda -->
<div class="card mb-4 shadow" style="background: #ca1010ff; border-radius: 15px;">
    <div class="card-body text-white">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-dollar-sign mb-1" style="font-size: 55px; margin-left: 6px"></i> 
            <p style="font-size: 26px; margin-top: 6px; margin-left: 29px;"><b>Total Denda Belum Dibayar</b></p>    
        </div>
        <h2 class="mb-0" style="font-size: 30px; margin-left: 65px">
            Rp{{ number_format($totalBelumDibayar, 0, ',', '.') }}
        </h2>
    </div>
</div>

<!-- total denda -->
<div class="card mb-4 shadow" style="background: #10E61E; border-radius: 15px;">
    <div class="card-body text-white">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-file-invoice-dollar mb-1" style="font-size: 55px;"></i> 
            <p style="font-size: 26px; margin-top: 6px; margin-left: 25px;"><b>Total Denda Sudah Dibayar</b></p>    
        </div>
        <h2 class="mb-0" style="font-size: 30px; margin-left: 65px">
            Rp{{ number_format($totalSudahDibayar, 0, ',', '.') }}
        </h2>
    </div>
</div>

<br>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>ID Buku</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th class="aksi-cell">Status</th>
                    <th class="aksi-cell">Denda</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($riwayat as $r)
            <tr>
                <td>{{ $r->anggota->nomor_anggota }}</td>
                <td>{{ $r->anggota->nama }}</td>
                <td>{{ $r->buku->kode_buku }}</td>
                <td>{{ $r->buku->judul }}</td>
                <td>{{ $r->tanggal_pinjam }}</td>
                <td>{{ $r->batas_kembali }}</td>
                <td>{{ $r->tanggal_kembali }}</td>
                <td>
                    <span class="aksi-cell badge bg-success">Sudah Dibayar</span>
                </td>
                <td class="aksi-cell text-danger font-weight-bold">
                    Rp{{ number_format($r->denda, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
    </tbody>
        </table>
    </div>
</div>
@endsection
