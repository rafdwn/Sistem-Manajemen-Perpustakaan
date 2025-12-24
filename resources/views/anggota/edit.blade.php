@extends('layouts.app')
@section('title', 'Edit Buku')

@section('content')

<form action="/buku/{{ $buku->id }}" method="POST" class="bg-white p-6 rounded shadow w-1/2">
    @csrf
    @method('PUT')

    <div>
        <label class="font-semibold">Kode Buku</label>
        <input name="kode_buku" 
               value="{{ $buku->kode_buku }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <div class="mt-3">
        <label class="font-semibold">Judul</label>
        <input name="judul" 
               value="{{ $buku->judul }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <div class="mt-3">
        <label class="font-semibold">Pengarang</label>
        <input name="pengarang" 
               value="{{ $buku->pengarang }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <div class="mt-3">
        <label class="font-semibold">Penerbit</label>
        <input name="penerbit" 
               value="{{ $buku->penerbit }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <div class="mt-3">
        <label class="font-semibold">Tahun</label>
        <input type="number" 
               name="tahun" 
               value="{{ $buku->tahun }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <div class="mt-3">
        <label class="font-semibold">Stok</label>
        <input type="number" 
               name="stok" 
               value="{{ $buku->stok }}" 
               class="w-full border p-2 rounded" 
               required>
    </div>

    <button class="mt-4 bg-blue-600 px-4 py-2 text-white rounded">
        Update
    </button>
</form>

@endsection
