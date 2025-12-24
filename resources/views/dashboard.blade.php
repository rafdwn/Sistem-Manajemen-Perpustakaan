@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h1 class="font-weight-bold mb-4" style="font-size: 28px;">Dashboard Pustakawan</h1>

<!-- TOTAL BUKU -->
<a href="{{ route('buku.index') }}" style="text-decoration:none; color:inherit;">
<div class="card dashboard-card mb-4 shadow" style="background: #007BFF; border-radius: 15px;">
    <div class="card-body text-white">

        <i class="fas fa-book mb-3" style="font-size: 55px"></i>

        <h2 class="font-weight-bold" style="font-size: 40px; margin-top:-5px;">
            {{ $totalBuku ?? 0 }}
        </h2>

        <p style="font-size: 18px; margin-top:-8px;">Total Buku</p>
    </div>
</div>

<!-- TOTAL ANGGOTA -->
<a href="{{ route('anggota.index') }}" style="text-decoration:none; color:inherit;">
<div class="card dashboard-card mb-4 shadow" style="background: #4CD964; border-radius: 15px;">
    <div class="card-body text-white">

        <i class="fas fa-users mb-3" style="font-size: 55px"></i>

        <h2 class="font-weight-bold" style="font-size: 40px; margin-top:-5px;">
            {{ $totalAnggota ?? 0 }}
        </h2>

        <p style="font-size: 18px; margin-top:-8px;">Total Anggota</p>
    </div>
</div>

<!-- TOTAL DIPINJAM -->
<a href="{{ route('peminjaman.index') }}" style="text-decoration:none; color:inherit;">
<div class="card dashboard-card mb-4 shadow" style="background: #FF9500; border-radius: 15px;">
    <div class="card-body text-white">

        <i class="fas fa-exchange-alt mb-3" style="font-size: 55px"></i>

        <h2 class="font-weight-bold" style="font-size: 40px; margin-top:-5px;">
            {{ $sedangDipinjam ?? 0 }}
        </h2>

        <p style="font-size: 18px; margin-top:-8px;">Sedang Dipinjam</p>
    </div>
</div>

@endsection
