@extends('layouts.app')
@section('title', 'Edit Buku')

@section('content')
<h2 class="font-weight-bold mb-4">Edit Buku</h2>

<form action="{{ route('buku.update', $buku->kode_buku) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Cover Buku</label>
        <input type="file" name="cover" class="form-control" accept="image/png,image/jpeg">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah cover</small>
    </div>

    <div class="form-group">
        <label>ID Buku</label>
        <input type="text" value="{{ $buku->id_buku }}" class="form-control" readonly>
    </div>

    <div class="form-group">
        <label>No. ISBN</label>
        <input type="text" name="kode_buku" value="{{ $buku->kode_buku }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Judul Buku</label>
        <input type="text" name="judul" value="{{ $buku->judul }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Pengarang</label>
        <input type="text" name="pengarang" value="{{ $buku->pengarang }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Penerbit</label>
        <input type="text" name="penerbit" value="{{ $buku->penerbit }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Tahun</label>
        <input type="number" name="tahun" value="{{ $buku->tahun }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" value="{{ $buku->stok }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Stok Tersedia</label>
        <input type="number" name="stok_tersedia" value="{{ $buku->stok_tersedia }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary mt-3">Update Buku</button>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-3">Batal</a>
</form>

@endsection
