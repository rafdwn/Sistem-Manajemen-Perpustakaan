@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
<h2 class="font-weight-bold mb-4">Tambah Buku</h2>

<form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label class="font-weight-bold">Cover Buku</label>
        <input type="file" name="cover" class="form-control" required accept="image/png,image/jpeg">
        <small class="text-muted">Format JPG/PNG, Maksimal 2MB</small>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">No. ISBN</label>
        <input type="text" name="kode_buku" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Judul Buku</label>
        <input type="text" name="judul" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Pengarang</label>
        <input type="text" name="pengarang" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Penerbit</label>
        <input type="text" name="penerbit" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Tahun</label>
        <input type="number" name="tahun" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Stok Buku</label>
        <input type="number" name="stok" class="form-control" required>
    </div>

    <div class="form-group">
        <label class="font-weight-bold">Stok Tersedia</label>
        <input type="number" name="stok_tersedia" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Tambah Buku</button>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-3">Batal</a>
</form>
@endsection
