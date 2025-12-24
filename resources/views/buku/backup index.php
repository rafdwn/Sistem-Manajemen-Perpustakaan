@extends('layouts.app')
@section('title', 'Data Buku')

@section('content')

@php
    $perPage = $perPage ?? 10;
@endphp

<!-- header -->
<h2 class="font-weight-bold mb-4">Kelola Data Buku</h2>

<!-- tombol diatas tabel -->
<!-- ini tambah -->
<div class="d-flex justify-content-start mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBuku">
        + Tambah Buku
    </button>
</div>

<!-- ini buat show sama search soalnya dia harus sejajar -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- show -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Tampilkan:</span>
        <form method="GET" action="{{ route('buku.index') }}" class="d-flex align-items-center">
            <select name="per_page" onchange="this.form.submit()" class="form-control" style="width: 80px;">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <input type="hidden" name="search" value="{{ request('search') }}">
        </form>
    </div>

    <!-- cari -->
    <form method="GET" action="{{ route('buku.index') }}" class="d-flex align-items-center">
        <span class="mr-2">Cari:</span>
        <input type="text" id="searchInput"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Cari buku..."
               style="width: 250px;">
        <input type="hidden" name="per_page" value="{{ $perPage }}">
    </form>
</div>

<!--ni gua gatau buat apa jingg tapi seinget gua ini buat notif suksesnya beda2 warna-->
@if(session('success_add'))
    <div class="alert alert-success">{{ session('success_add') }}</div>
@endif

@if(session('success_edit'))
    <div class="alert alert-primary">{{ session('success_edit') }}</div>
@endif

@if(session('success_delete'))
    <div class="alert alert-warning">{{ session('success_delete') }}</div>
@endif

<!-- tabel buku -->
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th class="th-sortable">
                <a href="?sort=id_buku&order={{ $sort === 'id_buku' && $order === 'asc' ? 'desc' : 'asc' }}"
                    style="color:white; text-decoration:none; display:flex; justify-content:space-between; align-items:center;">
                    <span>ID</span>
                    <span class="sort-icon">
                        @if($sort === 'id_buku')
                            {!! $order === 'asc' ? '&#9650;' : '&#9660;' !!} {{-- ▲ ▼ --}}
                        @else
                            &#9650;
                        @endif
                    </span>
                </a>
            </th>
            <th>ISBN</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Tersedia</th>
            <th style="text-align: center">Aksi</th>
        </tr>
    </thead>
    <tbody id="bukuTable">
        @forelse ($buku as $b)
        <tr>
            <td>{{ $b->id_buku }}</td>
            <td>{{ $b->kode_buku }}</td>
            <td>{{ $b->judul }}</td>
            <td>{{ $b->pengarang }}</td>
            <td>{{ $b->penerbit }}</td>
            <td>{{ $b->tahun }}</td>
            <td>{{ $b->stok }}</td>
            <td>{{ $b->stok_tersedia }}</td>
            <td class="aksi-cell text-center">
                <!-- tombol view cover -->
                <button 
                    class="btn-action view-btn"
                    data-idbuku="{{ $b->id_buku }}"
                    data-judul="{{ $b->judul }}"
                    data-cover="{{ $b->cover }}"
                    data-isbn="{{ $b->kode_buku }}"
                    data-pengarang="{{ $b->pengarang }}"
                    data-penerbit="{{ $b->penerbit }}"
                    data-tahun="{{ $b->tahun }}"
                    data-stok="{{ $b->stok }}"
                    data-tersedia="{{ $b->stok_tersedia }}"
                    style="background:none;border:none;">
                    <i class="fas fa-eye" style="color:#17a2b8"></i>
                </button>

                <!-- edit buku bosku -->
                <button 
                    class="btn-action2 edit-btn"
                    data-idbuku="{{ $b->id_buku }}"
                    data-kode="{{ $b->kode_buku }}"
                    data-isbn="{{ $b->kode_buku }}"
                    data-judul="{{ $b->judul }}"
                    data-pengarang="{{ $b->pengarang }}"
                    data-penerbit="{{ $b->penerbit }}"
                    data-tahun="{{ $b->tahun }}"
                    data-stok="{{ $b->stok }}"
                    data-stoktersedia="{{ $b->stok_tersedia }}"
                    data-toggle="modal"
                    data-target="#modalEdit"
                >
                    <i class="fas fa-edit"></i>
                </button>

                <!-- apus -->
                <form id="deleteForm{{ $b->kode_buku }}" 
                    action="{{ route('buku.destroy', $b->kode_buku) }}" 
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
                            data-id="{{ $b->kode_buku }}"
                            data-nama="{{ $b->judul }}"
                            style="background:none;border:none;">
                        <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-3 text-muted">
                Tidak ada data buku.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- buat loncat halaman -->
<div class="d-flex justify-content-between align-items-center mt-3" id="paginationWrapper">
    <div id="paginationInfo" class="text-muted">
        Menampilkan {{ $buku->firstItem() }} - {{ $buku->lastItem() }} dari {{ $buku->total() }} data.
    </div>
    <div id="paginationLinks">
        {{ $buku->links('pagination::force-bootstrap') }}
    </div>
</div>

<!-- tambah buku -->
<div class="modal fade" id="modalTambahBuku" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Tambah Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- COVER BUKU -->
                    <div class="form-group">
                        <label class="font-weight-bold">Cover Buku</label>
                        <input type="file" name="cover" class="form-control"
                               accept="image/png,image/jpeg" required
                               style="height: 45px; border-radius: .25rem;">
                        <small class="text-muted">Format JPG/PNG, Maksimal 2MB</small>
                    </div>
                    <!-- ID BUKU -->
                    <div class="form-group">
                        <label class="font-weight-bold">ID Buku</label>
                        <input type="text" class="form-control" readonly>
                    </div>
                    <!-- ISBN -->
                    <div class="form-group">
                        <label class="font-weight-bold">No. ISBN</label>
                        <input type="text" name="kode_buku" class="form-control" 
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>
                    <!-- JUDUL -->
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Buku</label>
                        <input type="text" name="judul" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>
                    <!-- PENGARANG -->
                    <div class="form-group">
                        <label class="font-weight-bold">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>
                    <!-- PENERBIT -->
                    <div class="form-group">
                        <label class="font-weight-bold">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>
                    <!-- TAHUN -->
                    <div class="form-group">
                        <label class="font-weight-bold">Tahun</label>
                        <input type="number" name="tahun" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>
                    <!-- STOK -->
                    <div class="form-group">
                        <label class="font-weight-bold">Stok Buku</label>
                        <input type="number" name="stok" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>

                    <!-- STOK TERSEDIA -->
                    <div class="form-group">
                        <label class="font-weight-bold">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" class="form-control"
                               style="height: 45px; border-radius: .25rem;" required>
                    </div>

                </div>
                <!--tombol bawah wajib justify cok-->
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-aksi"
                            style="background-color: #007bff; color:#fff; width:47%; height:40px;">
                        Tambah Buku
                    </button>
                    <button type="button" class="btn btn-aksi" data-dismiss="modal"
                            style="background-color: #949494; color:#fff; width:47%; height:40px;">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit buku -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Edit Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cover Buku</label>
                        <input type="file" name="cover" class="form-control" accept="image/png,image/jpeg">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah cover</small>
                    </div>
                    <div class="form-group">
                        <label>ID Buku</label>
                        <input type="text" id="edit_idbuku" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>No. ISBN</label>
                        <input type="text" id="edit_isbn" name="kode_buku" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" id="edit_judul" name="judul" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" id="edit_pengarang" name="pengarang" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" id="edit_penerbit" name="penerbit" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" id="edit_tahun" name="tahun" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" id="edit_stok" name="stok" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Stok Tersedia</label>
                        <input type="number" id="edit_tersedia" name="stok_tersedia" class="form-control">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary btn-aksi" style="width:47%">Update Buku</button>
                    <button type="button" class="btn btn-secondary btn-aksi" data-dismiss="modal" style="width:47%">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal view buku -->
<div class="modal fade" id="modalView" tabindex="-1">
    <div class="modal-dialog modal-custom modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="viewTitle" class="modal-title font-weight-bold"></h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div style="display:flex; align-items:flex-start; gap:35px;">
                    <!-- cover -->
                    <div style="margin-top: 25px; margin-left: 20px; align-items-center">
                        <img id="imgCover" src="" 
                            style="height:300px; width:auto; border-radius:10px; object-fit:cover;">
                    </div>
                    <!-- detail -->
                    <div style="align-items-center">
                        <table class="table table-borderless" style="font-size:17px;">
                            <tbody>
                                <tr>
                                    <th>ID Buku</th>
                                    <td>:</td>
                                    <td id="viewIdBuku"></td>
                                </tr>
                                <tr>
                                    <th style="width:10px;">ISBN</th>
                                    <td style="width:10px;">:</td>
                                    <td id="viewIsbn"></td>
                                </tr>
                                <tr>
                                    <th>Pengarang</th>
                                    <td>:</td>
                                    <td id="viewPengarang"></td>
                                </tr>
                                <tr>
                                    <th>Penerbit</th>
                                    <td>:</td>
                                    <td id="viewPenerbit"></td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td>:</td>
                                    <td id="viewTahun"></td>
                                </tr>
                                <tr>
                                    <th>Stok</th>
                                    <td>:</td>
                                    <td id="viewStok"></td>
                                </tr>
                                <tr>
                                    <th>Tersedia</th>
                                    <td>:</td>
                                    <td id="viewTersedia"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!--buat masukin skrip-->
@push('scripts')
<!--buat cover ada-->
<script>
$('#viewCover').attr('src', '/storage/' + cover);
</script>

<!-- buat modal view-->
<script>
$(document).on('click', '.view-btn', function () {
    let cover = $(this).data('cover');
    let judul = $(this).data('judul');

    // detail
    $('#viewIdBuku').text($(this).data('idbuku'));
    $('#viewIsbn').text($(this).data('isbn'));
    $('#viewPengarang').text($(this).data('pengarang'));
    $('#viewPenerbit').text($(this).data('penerbit'));
    $('#viewTahun').text($(this).data('tahun'));
    $('#viewStok').text($(this).data('stok'));
    $('#viewTersedia').text($(this).data('tersedia'));

    // header & cover
    $('#viewTitle').text(judul);
    $('#imgCover').attr('src', '/storage/covers/' + cover);
    $('#modalView').modal('show');
});
</script>

<!--buat load data pas edit-->
<script>
$(document).on('click', '.edit-btn', function () {
    let kode = $(this).data('kode');  
    $('#formEdit').attr('action', '/buku/' + kode);
    $('#edit_idbuku').val($(this).data('idbuku'));
    $('#edit_isbn').val($(this).data('isbn'));
    $('#edit_judul').val($(this).data('judul'));
    $('#edit_pengarang').val($(this).data('pengarang'));
    $('#edit_penerbit').val($(this).data('penerbit'));
    $('#edit_tahun').val($(this).data('tahun'));
    $('#edit_stok').val($(this).data('stok'));
    $('#edit_tersedia').val($(this).data('stoktersedia'));
});

</script>

<!--buat modal edit sama search-->
<script>
$(document).ready(function () {
    $('#searchInput').on('keyup', function () {
        let keyword = $(this).val().trim();
        if (keyword === "") {
            $.ajax({
                url: "{{ route('buku.index') }}",
                type: "GET",
                data: {
                    per_page: "{{ $perPage ?? 10 }}"
                },
                success: function (html) {
                    let tbody = $(html).find('#bukuTable').html();
                    let pageInfo = $(html).find('#paginationInfo').html();
                    let pageLinks = $(html).find('#paginationLinks').html();
                    $('#bukuTable').html(tbody);
                    $('#paginationInfo').html(pageInfo).show();
                    $('#paginationLinks').html(pageLinks).show();
                }
            });
            return;
        }

        $.ajax({
            url: "{{ route('buku.search') }}",
            type: "GET",
            data: { keyword: keyword },
            success: function (response) {
                let rows = "";
                if (response.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>`;
                } else {
                    response.forEach(b => {
                        rows += `
                        <tr>
                            <td>${b.id_buku}</td>
                            <td>${b.kode_buku}</td>
                            <td>${b.judul}</td>
                            <td>${b.pengarang}</td>
                            <td>${b.penerbit}</td>
                            <td>${b.tahun}</td>
                            <td>${b.stok}</td>
                            <td>${b.stok_tersedia}</td>
                            <td class="text-center">
                                <button 
                                    class="btn-action view-btn"
                                    data-idbuku="{{ $b->id_buku }}"
                                    data-judul="{{ $b->judul }}"
                                    data-cover="{{ $b->cover }}"
                                    data-isbn="{{ $b->kode_buku }}"
                                    data-pengarang="{{ $b->pengarang }}"
                                    data-penerbit="{{ $b->penerbit }}"
                                    data-tahun="{{ $b->tahun }}"
                                    data-stok="{{ $b->stok }}"
                                    data-tersedia="{{ $b->stok_tersedia }}"
                                    style="background:none;border:none;">
                                    <i class="fas fa-eye" style="color:#17a2b8"></i>
                                </button>
                                <button 
                                    class="btn-action2 edit-btn"
                                    data-idbuku="{{ $b->id_buku }}"
                                    data-kode="{{ $b->kode_buku }}"
                                    data-isbn="{{ $b->kode_buku }}"
                                    data-judul="{{ $b->judul }}"
                                    data-pengarang="{{ $b->pengarang }}"
                                    data-penerbit="{{ $b->penerbit }}"
                                    data-tahun="{{ $b->tahun }}"
                                    data-stok="{{ $b->stok }}"
                                    data-stoktersedia="{{ $b->stok_tersedia }}"
                                    data-toggle="modal"
                                    data-target="#modalEdit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form id="deleteForm{{ $b->kode_buku }}" 
                                    action="{{ route('buku.destroy', $b->kode_buku) }}" 
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
                                            data-id="{{ $b->kode_buku }}"
                                            data-nama="{{ $b->judul }}"
                                            style="background:none;border:none;">
                                        <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                }
                $('#bukuTable').html(rows);
                $('#paginationInfo').hide();
                $('#paginationLinks').hide();
            }
        });
    });
});
</script>

<!--animasi fade pas sukses apus/edit/tambah-->
<script>
    setTimeout(function () {
        $('.alert').slideUp(400);
    }, 3000);
</script>

<!--sweetalert ini mah buat animasi apus-->
<script>
$(document).on('click', '.delete-btn', function () {

    let id = $(this).data('id');
    let nama = $(this).data('nama');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Hapus buku <b>${nama}</b>?<br>Data yang dihapus tidak dapat dikembalikan!`,
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
