@extends('layouts.app')
@section('title', 'Data Anggota')

@section('content')

<h2 class="font-weight-bold mb-4">Kelola Data Anggota</h2>

<!-- tombol diatas tabel -->
<div class="d-flex justify-content-start mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahAnggota">
        + Tambah Anggota
    </button>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- tombol show -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Tampilkan:</span>
        <form method="GET" action="{{ route('anggota.index') }}" class="d-flex align-items-center">
            <select name="per_page" onchange="this.form.submit()" class="form-control"
                    style="width: 80px;">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <!-- supaya search tetap kebaca -->
            <input type="hidden" name="search" value="{{ request('search') }}">
        </form>
    </div>
    <!-- tombol search -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Cari:</span>
        <input type="text" id="searchInput" 
            class="form-control" placeholder="Cari anggota..." style="width:250px;">
    </div>
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

<!--tabel anggotanya-->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="th-sortable">
                        <a href="?sort=nomor_anggota&order={{ $sort === 'nomor_anggota' && $order === 'asc' ? 'desc' : 'asc' }}"
                            style="color:white; text-decoration:none; display:flex; justify-content:space-between; align-items:center;">
                            <span>ID Anggota</span>
                            <span class="sort-icon">
                                @if($sort === 'nomor_anggota')
                                    {!! $order === 'asc' ? '&#9650;' : '&#9660;' !!} {{-- ▲ ▼ --}}
                                @else
                                    &#9650;
                                @endif
                            </span>
                        </a>
                    </th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th style="text-align: center">Aksi</th>
                </tr>
            </thead>
            <tbody id="anggotaTable">
                @forelse ($anggota as $a)
                <tr>
                    <td>{{ $a->nomor_anggota }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->email }}</td>
                    <td>{{ $a->no_telepon }}</td>
                    <td>{{ $a->alamat }}</td>
                    <td class="aksi-cell">
                        <!-- edit -->
                        <button 
                            class="btn-action2 edit-btn"
                            data-id="{{ $a->id }}"
                            data-no="{{ $a->nomor_anggota }}"
                            data-nama="{{ $a->nama }}"
                            data-email="{{ $a->email }}"
                            data-telp="{{ $a->no_telepon }}"
                            data-alamat="{{ $a->alamat }}"
                            data-toggle="modal"
                            data-target="#modalEdit"
                        >
                            <i class="fas fa-edit"></i>
                        </button>

                        <!-- apus -->
                        <form id="deleteForm{{ $a->id }}" 
                            action="{{ route('anggota.destroy', $a->id) }}" 
                            method="POST" 
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @csrf
                        @method('DELETE')
                        </form>
                        <button type="button" 
                                    class="btn-action delete delete-btn" 
                                    data-id="{{ $a->id }}"
                                    data-nama="{{ $a->nama }}"
                                    style="background:none;border:none;">
                                <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-3 text-muted">
                        Tidak ada data anggota.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- buat nunjukin angotta -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <div id="paginationInfo" class="text-muted">
        Menampilkan {{ $anggota->firstItem() }} - {{ $anggota->lastItem() }} dari {{ $anggota->total() }} data.
    </div>
    <div id="paginationLinks">
        {{ $anggota->links('pagination::force-bootstrap') }}
    </div>
</div>

<!-- tambah anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title; font-weight-bold">Tambah Data Anggota</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('anggota.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>ID Anggota</label>
                    <input type="text" class="form-control" value="{{ $nomorBaru }}" disabled>
                    <input type="hidden" name="nomor_anggota" value="{{ $nomorBaru }}">
                </div>
                <div class="form-group">
                    <label>Nama Anggota</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="number" name="no_telepon" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-aksi" style="background-color: #007bff; color:#fff; width:47%; height:40px;">Tambah Anggota</button>
                <button type="button" class="btn btn-secondary btn-aksi" data-dismiss="modal" style="width:47%">Batal</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- edit anggota -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Edit Data Anggota</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID Anggota</label>
                        <input type="text" id="edit_no" name="nomor_anggota" class="form-control" disabled>
                        <input type="hidden" id="edit_no_hidden" name="nomor_anggota">
                    </div>
                    <div class="form-group">
                        <label>Nama Anggota</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="edit_email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="number" id="edit_telp" name="no_telepon" class="form-control" required>
                        </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" id="edit_alamat" name="alamat" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-aksi" style="background-color: #007bff; color:#fff; width:47%; height:40px;">Update Data</button>
                    <button type="button" class="btn btn-secondary btn-aksi" data-dismiss="modal" style="width:47%">Batal</button>
                </div>  
            </form>
        </div>
    </div>
</div>
@endsection

<!--taro skrip disini-->
@push('scripts')
<script>
$('#modalTambahAnggota').on('show.bs.modal', function () {
    $.ajax({
        url: "{{ route('anggota.create') }}",
        type: "GET",
        success: function(response) {
            // isi field ID otomatis
            $('#modalTambahAnggota input[disabled]').val(response.nomorBaru);
            $('#modalTambahAnggota input[name="nomor_anggota"]').val(response.nomorBaru);
        }
    });
});

</script>
<!--biar pas edit datanya udah ada-->
<script>
    $(document).on('click', '.edit-btn', function () {

        let id = $(this).data('id');

        $('#formEdit').attr('action', '/anggota/' + id);

        // isi input modal
        $('#edit_no').val($(this).data('no'));
        $('#edit_no_hidden').val($(this).data('no'));
        $('#edit_nama').val($(this).data('nama'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_telp').val($(this).data('telp'));
        $('#edit_alamat').val($(this).data('alamat'));
    });
</script>

<!--ini buat modal edit & search ini-->
<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        let keyword = $(this).val().trim();
        if (keyword === "") {
            $.ajax({
                url: "{{ route('anggota.index') }}",
                type: "GET",
                data: { per_page: "{{ $perPage }}" },
                success: function(html) {
                    let tbody = $(html).find('#anggotaTable').html();
                    let pageInfo = $(html).find('#paginationInfo').html();
                    let pageLinks = $(html).find('#paginationLinks').html();

                    $('#anggotaTable').html(tbody);
                    $('#paginationInfo').html(pageInfo).show();
                    $('#paginationLinks').html(pageLinks).show();
                }
            });
            return;
        }

        $.ajax({
            url: "{{ route('anggota.search') }}",
            type: "GET",
            data: { keyword: keyword },
            success: function(response) {
                let rows = "";
                if (response.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Tidak ada anggota ditemukan.
                            </td>
                        </tr>`;
                } else {
                    response.forEach(a => {
                        rows += `
                            <tr>
                                <td>${a.nomor_anggota}</td>
                                <td>${a.nama}</td>
                                <td>${a.email ?? '-'}</td>
                                <td>${a.no_telepon ?? '-'}</td>
                                <td>${a.alamat ?? '-'}</td>
                                <td class="aksi-cell text-center">
                                    <button 
                                        class="btn-action2 edit-btn"
                                        data-id="${a.id}"
                                        data-no="${a.nomor_anggota}"
                                        data-nama="${a.nama}"
                                        data-email="${a.email}"
                                        data-telp="${a.no_telepon}"
                                        data-alamat="${a.alamat}"
                                        data-toggle="modal"
                                        data-target="#modalEdit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        type="button"
                                        class="btn-action delete delete-btn"
                                        data-id="${a.id}"
                                        data-nama="${a.nama}"
                                        style="background:none;border:none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                }
                $('#anggotaTable').html(rows);
                $('#paginationInfo').hide();
                $('#paginationLinks').hide();
            }
        });
    });
});
</script>

<!--biar notif sukesnya ilang jadi ga stray permanen-->
<script>
    setTimeout(function () {
        $('.alert').slideUp(400);
    }, 3000);
</script>

<!--ini sweetalert apus -->
<script>
$(document).on('click', '.delete-btn', function () {

    let id = $(this).data('id');
    let nama = $(this).data('nama');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Hapus anggota <b>${nama}</b>?<br>Data yang dihapus tidak dapat dikembalikan!`,
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