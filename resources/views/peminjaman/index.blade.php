@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')

@php
    $perPage = $perPage ?? 10;
    $sort = $sort ?? 'id_peminjaman';
    $order = $order ?? 'asc';
@endphp

<!-- header -->
<h2 class="font-weight-bold mb-4">Transaksi Peminjaman</h2>

<!-- tombol diatas tabel -->
<div class="d-flex justify-content-start mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
        + Tambah Peminjaman
    </button>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    
    <!-- tampil per halaman -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Tampilkan:</span>
        <form method="GET" action="{{ route('peminjaman.index') }}" class="d-flex align-items-center">
            <select name="per_page" onchange="this.form.submit()" class="form-control" style="width: 80px;">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>

            <input type="hidden" name="search" value="{{ $search ?? '' }}">
        </form>
    </div>

    <!-- cari -->
    <form method="GET" action="{{ route('peminjaman.index') }}" class="d-flex align-items-center">
        <span class="mr-2">Cari:</span>
        <input type="text" name="search" value="{{ $search }}"
               class="form-control"
               placeholder="Cari peminjaman..." style="width: 250px;">
        <input type="hidden" name="per_page" value="{{ $perPage }}">
    </form>
</div>

<!--warna sukses beda2-->
@if(session('success_add'))
    <div class="alert alert-success">{{ session('success_add') }}</div>
@endif

@if(session('success_edit'))
    <div class="alert alert-primary">{{ session('success_edit') }}</div>
@endif

@if(session('success_delete'))
    <div class="alert alert-warning">{{ session('success_delete') }}</div>
@endif

@if(session('success_delete'))
    <div class="alert alert-warning">{{ session('success_delete') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- tabel peminjaman -->
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="th-sortable">
                        <a href="?sort=id_peminjaman&order={{ $sort === 'id_peminjaman' && $order === 'asc' ? 'desc' : 'asc' }}"
                            style="color:white; text-decoration:none; display:flex; justify-content:space-between; align-items:center;">
                            <span>ID</span>
                            <span class="sort-icon">
                                @if($sort === 'id_peminjaman')
                                    {!! $order === 'asc' ? '&#9650;' : '&#9660;' !!} {{-- ▲ ▼ --}}
                                @else
                                    &#9650;
                                @endif
                            </span>
                        </a>
                    </th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>ID Buku</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th class="aksi-cell">Status</th>
                    <th class="aksi-cell">Aksi</th>
                </tr>
            </thead>
            <tbody id="peminjamanTable">
                @forelse($peminjaman as $p)
                <tr>
                    <td>{{ $p->id_peminjaman }}</td>
                    <td>{{ $p->anggota->nomor_anggota }}</td>
                    <td>{{ $p->anggota->nama }}</td>
                    <td>{{ $p->buku->id_buku }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $p->batas_kembali }}</td>
                    <td class="aksi-cell">
                        <span class="badge badge-info">{{ $p->status }}</span>
                    </td>
                    <td class="aksi-cell text-center">
                        <!-- tombol cetak PDF -->
                        <button type="button"
                            onclick="window.location='{{ route('peminjaman.cetak', $p->id) }}'"
                            style="background:none;border:none;" class="btn-action2">
                            <i class="fas fa-print" style="color:green;"></i>
                        </button>

                        <!-- tombol edit -->
                        <button 
                            class="btn-action2 edit-btn"
                            data-idpm="{{ $p->id_peminjaman }}"
                            data-id="{{ $p->id }}"
                            data-anggota="{{ $p->anggota_id }}"
                            data-buku="{{ $p->buku_id }}"
                            data-pinjam="{{ $p->tanggal_pinjam }}"
                            data-batas="{{ $p->batas_kembali }}"
                            style="background:none;border:none;"
                            data-toggle="modal"
                            data-target="#modalEdit">
                            <i class="fas fa-edit" style="color:#007bff;"></i>
                        </button>

                        <!-- tombol apus -->
                        <form id="deleteForm{{ $p->id }}" 
                            action="{{ route('peminjaman.destroy', $p->id) }}" 
                            method="POST" 
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" 
                            class="btn-action delete-btn" 
                            data-id="{{ $p->id }}"
                            data-anggota="{{ $p->anggota->nama }}"
                            data-buku="{{ $p->buku->judul }}"
                            style="background:none;border:none;">
                            <i class="fas fa-trash" style="color:red;"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-3 text-muted">
                        Tidak ada data peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- buat pagination -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted">
        Menampilkan {{ $peminjaman->firstItem() }} - {{ $peminjaman->lastItem() }}
        dari {{ $peminjaman->total() }} data.
    </div>
    <div id="paginationWrapper">
        {{ $peminjaman->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- modal edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Edit Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Peminjaman</label>
                        <input type="text" id="edit_idpm_display" class="form-control" disabled>
                        <input type="hidden" id="edit_idpm" name="id_peminjaman">
                    </div>

                    <div class="form-group">
                        <label>Nomor Anggota</label>
                        <select name="anggota_id" id="edit_anggota" class="form-control" required>
                            @foreach($anggota as $a)
                                <option value="{{ $a->id }}">{{ $a->nomor_anggota }} - {{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Buku</label>
                        <select name="buku_id" id="edit_buku" class="form-control" required>
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}">{{ $b->id_buku }} - {{ $b->stok }} - {{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input type="date" id="edit_pinjam" name="tanggal_pinjam" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Batas Kembali</label>
                        <input type="date" id="edit_batas" name="batas_kembali" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary btn-aksi" style="width:47%">Update</button>
                    <button type="button" class="btn btn-secondary btn-aksi" data-dismiss="modal" style="width:47%">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- tambah peminjaman -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Transaksi Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Peminjaman</label>

                        <input type="text" id="tambah_idpm_display" class="form-control" disabled>

                        <input type="hidden" id="tambah_idpm" name="id_peminjaman">
                    </div>
                    <div class="form-group">
                        <label>Nomor Anggota</label>
                        <select name="anggota_id" class="form-control" required>
                            <option value="">Pilih Anggota</option>
                            @foreach($anggota as $a)
                                <option value="{{ $a->id }}">{{ $a->nomor_anggota }} - {{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID Buku</label>
                        <select name="buku_id" class="form-control" required>
                            <option value="">Pilih Buku</option>
                            @foreach($buku as $b)
                                <option value="{{ $b->id }}">{{ $b->id_buku }} - {{ $b->stok_tersedia }} - {{ $b->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-aksi" style="background-color: #007bff; color:#fff; width:47%; height:40px;">Proses Peminjaman</button>
                    <button type="button" class="btn btn-secondary btn-aksi" data-dismiss="modal" style="width:47%; height:40px;">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- mauskin skrip -->
@push('scripts')
<!-- load id -->
<script>
$('#edit_idpinjam').val($(this).data('idpm'));
</script>

<!-- load id tambah -->
<script>
$('#modalTambah').on('show.bs.modal', function () {
    $.ajax({
        url: "{{ route('peminjaman.create') }}",
        type: "GET",
        success: function(response) {
            $('#tambah_idpm_display').val(response.idBaru);
            $('#tambah_idpm').val(response.idBaru);
        }
    });
});
</script>

<!-- load id edit -->
<script>
$('.edit-btn').on('click', function () {
    let idpm = $(this).data('idpm');

    $('#edit_idpm_display').val(idpm);
    $('#edit_idpm').val(idpm);
});

</script>

<!--live search ajax-->
<script>
$(document).ready(function(){

    let typingTimer;
    let delay = 200; // biar smooth ngetiknya

    $('input[name="search"]').on('keyup', function(){

        clearTimeout(typingTimer);

        typingTimer = setTimeout(() => {
            let keyword = $(this).val().trim();
            if(keyword === ""){
                $.ajax({
                    url: "{{ route('peminjaman.index') }}",
                    type: "GET",
                    success: function(response){
                        // Parse HTML response untuk ambil tabel dan pagination
                        let table = $(response).find('#peminjamanTable').html();
                        let pagination = $(response).find('#paginationWrapper').html();
                        
                        $('#peminjamanTable').html(table);
                        $('#paginationWrapper').html(pagination).show();
                    }
                });
                return;
            }

            $.ajax({
                url: "{{ route('peminjaman.search') }}",
                type: "GET",
                data: { keyword: keyword },
                success: function(response){

                    let rows = "";

                    if(response.length === 0){
                        rows = `
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">
                                    Tidak ada data peminjaman ditemukan.
                                </td>
                            </tr>
                        `;
                    } else {
                        response.forEach(p => {
                            rows += `
                                <tr>
                                    <td>${p.id_peminjaman}</td>
                                    <td>${p.anggota.nomor_anggota}</td>
                                    <td>${p.anggota.nama}</td>
                                    <td>${p.buku.id_buku}</td>
                                    <td>${p.buku.judul}</td>
                                    <td>${p.tanggal_pinjam}</td>
                                    <td>${p.batas_kembali}</td>
                                    <td class="aksi-cell"><span class="badge badge-info">${p.status}</span></td>

                                    <td class="aksi-cell text-center">

                                        <button 
                                            class="btn-action2 edit-btn"
                                            data-id="${p.id}"
                                            data-anggota="${p.anggota_id}"
                                            data-buku="${p.buku_id}"
                                            data-pinjam="${p.tanggal_pinjam}"
                                            data-batas="${p.batas_kembali}"
                                            data-toggle="modal"
                                            data-target="#modalEdit"
                                            style="background:none;border:none;">
                                            <i class="fas fa-edit" style="color:#007bff;"></i>
                                        </button>

                                        <button 
                                            class="btn-action delete-btn"
                                            data-id="${p.id}"
                                            data-anggota="${p.anggota.nama}"
                                            data-buku="${p.buku.judul}"
                                            style="background:none;border:none;">
                                            <i class="fas fa-trash" style="color:red;"></i>
                                        </button>

                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#peminjamanTable').html(rows);
                    $('#paginationWrapper').hide(); 
                }
            });
        }, delay);

    });
});
</script>

<!-- notifikasinya fade -->
<script>
    setTimeout(function () {
        $('.alert').slideUp(400);
    }, 3000);
</script>

<script>
// modal edit
$(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');

    $('#formEdit').attr('action', '/peminjaman/' + id);

    $('#edit_anggota').val($(this).data('anggota'));
    $('#edit_buku').val($(this).data('buku'));
    $('#edit_pinjam').val($(this).data('pinjam'));
    $('#edit_batas').val($(this).data('batas'));
});
</script>

<script>
// notif sweetalert
$(document).on('click', '.delete-btn', function () {

    let id = $(this).data('id');
    let anggota = $(this).data('anggota');
    let buku = $(this).data('buku');

    Swal.fire({
        title: 'Yakin hapus?',
        html: `Hapus peminjaman <b>${anggota}</b> (Buku: <b>${buku}</b>)?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });

});
</script>

@endpush
