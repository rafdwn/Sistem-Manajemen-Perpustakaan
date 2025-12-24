@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
<form action="/buku" method="POST" class="bg-white p-6 rounded shadow w-1/2">
    @csrf

    <div>
        <label>Kode Buku</label>
        <input name="kode_buku" class="w-full border p-2 rounded" required>
    </div>

    <div class="mt-3">
        <label>Judul</label>
        <input name="judul" class="w-full border p-2 rounded" required>
    </div>

    <div class="mt-3">
        <label>Pengarang</label>
        <input name="pengarang" class="w-full border p-2 rounded" required>
    </div>

    <div class="mt-3">
        <label>Penerbit</label>
        <input name="penerbit" class="w-full border p-2 rounded" required>
    </div>

    <div class="mt-3">
        <label>Tahun</label>
        <input type="number" name="tahun" class="w-full border p-2 rounded" required>
    </div>

    <div class="mt-3">
        <label>Stok</label>
        <input type="number" name="stok" class="w-full border p-2 rounded" required>
    </div>

    <button class="mt-4 bg-blue-600 px-4 py-2 text-white rounded">Simpan</button>
</form>
@endsection
