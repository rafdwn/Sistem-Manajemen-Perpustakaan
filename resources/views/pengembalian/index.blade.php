@extends('layouts.app')
@section('title','Pengembalian')

@section('content')

@if(session('success_balik'))
    <div class="alert alert-success">
        {{ session('success_balik') }}
    </div>
@endif

<!-- header sama search box -->
<div class="d-flex justify-content-between">
    <h2 class="font-weight-bold mb-3">Transaksi Pengembalian</h2>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- tombol show -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Tampilkan:</span>
        <form method="GET" action="{{ route('pengembalian.index') }}" class="d-flex align-items-center">
            <select name="per_page" onchange="this.form.submit()" class="form-control"
                    style="width: 80px;">
                <option value="10" {{ $perPageTransaksi == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPageTransaksi == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPageTransaksi == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPageTransaksi == 100 ? 'selected' : '' }}>100</option>
            </select>
            <!-- supaya search tetap kebaca -->
            <input type="hidden" name="search_transaksi" value="{{ $searchTransaksi }}">
        </form>
    </div>
    <!-- tombol search -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Cari:</span>
        <input type="text" id="searchTransaksi" 
       name="search_transaksi"
       class="form-control"
       placeholder="Cari transaksi..." 
       style="width:250px;">
    </div>
</div>

<!-- tabel transaksi -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped rounded">
            <thead class="thead-dark">
                <tr>
                    <th class="th-sortable">
                        <a href="?sort_transaksi=id_peminjaman&order_transaksi={{ request('order_transaksi') === 'asc' ? 'desc' : 'asc' }}"
                        class="sort-link">
                            <span>ID Peminjaman</span>
                            <span class="sort-icon">
                                @if(request('sort_transaksi') === 'id_peminjaman')
                                    {!! request('order_transaksi') === 'asc' ? '&#9650;' : '&#9660;' !!}
                                @else
                                    &#9650;
                                @endif
                            </span>
                        </a>
                    </th>
                    <th>Nama Anggota</th>
                    <th>ID Buku</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th class="aksi-cell">Status</th>
                    <th class="aksi-cell">Aksi</th>
                </tr>
            </thead>
            <tbody id="transaksiTable">
            @forelse($pengembalian as $item)
                <tr>
                    <td>{{ $item->id_peminjaman }}</td>
                    <td>{{ $item->anggota->nama }}</td>
                    <td>{{ $item->buku->id_buku }}</td>
                    <td>{{ $item->buku->judul }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->batas_kembali }}</td>
                    <td class="aksi-cell">
                        <span class="badge badge-info">{{ $item->status }}</span>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm"
                            onclick="loadPengembalian({{ $item->id }})">
                            Pengembalian
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">
                        Tidak ada transaksi pengembalian.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- buat nunjukin transaksi -->
<div class="d-flex justify-content-between align-items-center mt-2">
    <div id="transaksiPaginationInfo" class="text-muted">
        Menampilkan {{ $pengembalian->firstItem() }} - {{ $pengembalian->lastItem() }} dari {{ $pengembalian->total() }} data.
    </div>
    <div id="transaksiPaginationLinks">
        {{ $pengembalian->links('pagination::force-bootstrap') }}
    </div>
</div>

<!-- longkap -->
<br>

<!-- header sama riwayat -->
<div class="d-flex justify-content-between">
    <h2 class="font-weight-bold mb-3">Riwayat Pengembalian</h2>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- tombol show -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Tampilkan:</span>
        <form method="GET" action="{{ route('pengembalian.index') }}" class="d-flex align-items-center">
            <select name="per_page" onchange="this.form.submit()" class="form-control"
                    style="width: 80px;">
                <option value="10" {{ $perPageRiwayat == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPageRiwayat == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPageRiwayat == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPageRiwayat == 100 ? 'selected' : '' }}>100</option>
            </select>
            <!-- supaya search tetap kebaca -->
            <input type="hidden" name="search_riwayat" value="{{ $searchRiwayat }}">
        </form>
    </div>
    <!-- tombol search -->
    <div class="d-flex align-items-center">
        <span class="mr-2">Cari:</span>
        <input type="text" id="searchRiwayat" 
            name="search_riwayat"
            class="form-control"
            placeholder="Cari riwayat..." 
            style="width:250px;">
    </div>
</div>

<!-- tabel riwayat -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="th-sortable">
                        <a href="?sort_riwayat=id_peminjaman&order_riwayat={{ request('order_riwayat') === 'asc' ? 'desc' : 'asc' }}"
                        class="sort-link">
                            <span>ID Peminjaman</span>
                            <span class="sort-icon">
                                @if(request('sort_riwayat') === 'id_peminjaman')
                                    {!! request('order_riwayat') === 'asc' ? '&#9650;' : '&#9660;' !!}
                                @else
                                    &#9650;
                                @endif
                            </span>
                        </a>
                    </th>
                    <th>Nama Anggota</th>
                    <th>ID Buku</th>
                    <th>Nama Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Tgl Kembali</th>
                    <th class="aksi-cell">Status</th>
                    <th class="aksi-cell">Denda</th>
                </tr>
            </thead>
            <tbody id="riwayatTable">
            @forelse ($riwayat as $item)
            <tr>
                <td>{{ $item->id_peminjaman }}</td>
                <td>{{ $item->anggota->nama }}</td>
                <td>{{ $item->buku->id_buku }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td>{{ $item->tanggal_pinjam }}</td>
                <td class="aksi-cell">{{ $item->batas_kembali }}</td>
                <td>{{ $item->tanggal_kembali }}</td>
                <td class="aksi-cell">
                    @if ($item->status == 'Tepat Waktu')
                        <span class="badge badge-success">Tepat Waktu</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
                <td class="aksi-cell text-danger font-weight-bold">
                    Rp{{ number_format($item->denda, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-3">
                    Tidak ada riwayat pengembalian.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- buat nunjukin riwayat -->
<div class="d-flex justify-content-between align-items-center mt-2">
    <div id="riwayatPaginationInfo" class="text-muted">
        Menampilkan {{ $riwayat->firstItem() }} - {{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data.
    </div>
    <div id="riwayatPaginationLinks">
        {{ $riwayat->links('pagination::force-bootstrap') }}
    </div>
</div>

<!-- modal pengembalian -->
<div class="modal fade" id="modalPengembalian" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Status Pengembalian</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body p-4" style="height: 72vh;">
                    <div class="d-flex h-100" style="gap: 20px;">
                        <!-- KIRI -->
                        <div class="d-flex flex-column flex-grow-1" style="gap: 20px;">
                            <!-- BOX 1 -->
                            <div class="p-3 text-white" style="background:#0b57d0; border-radius:12px; flex:1;">
                                <h5 class="d-flex align-items-center" style="gap:10px;">
                                    <i class="fas fa-user"></i> Informasi Anggota
                                </h5>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small>Nomor Anggota</small>
                                        <h6 id="modal_nomor_anggota"></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <small>Nama Anggota</small>
                                        <h6 id="modal_nama_anggota"></h6>
                                    </div>
                                </div>
                            </div>
                            <!-- BOX 2 -->
                            <div class="p-3 text-white" style="background:#623cea; border-radius:12px; flex:1;">
                                <h5 class="d-flex align-items-center" style="gap:10px;">
                                    <i class="fas fa-book"></i> Informasi Buku
                                </h5>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small>Kode Buku</small>
                                        <h6 id="modal_kode_buku"></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <small>Judul Buku</small>
                                        <h6 id="modal_judul_buku"></h6>
                                    </div>
                                </div>
                            </div>
                            <!-- BOX 3 -->
                            <div class="p-3 text-white" style="background:#0284c7; border-radius:12px; flex:1;">
                                <h5 class="d-flex align-items-center" style="gap:10px;">
                                    <i class="fas fa-calendar"></i> Detail Peminjaman
                                </h5>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <small>Tanggal Pinjam</small>
                                        <h6 id="modal_tgl_pinjam"></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Batas Kembali</small>
                                        <h6 id="modal_batas_kembali"></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <small>Tanggal Kembali</small>
                                        <h6 id="modal_tgl_kembali"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- KANAN -->
                        <div class="p-4 flex-grow-1 d-flex flex-column justify-content-center text-center"
                            id="statusBox"
                            style="border-radius:12px; max-width: 33%;">
                        </div>
                    </div>
                </div>
           <div class="modal-footer d-flex justify-content-between align-items-center" style="border-top: 1px solid #ddd; padding-top: 15px; padding-left: 20px; padding-right: 20px;">
                <form id="formKembalikan" method="POST" action="{{ route('pengembalian.proses') }}" class="flex-grow-1 mr-2">
                    @csrf
                    <input type="hidden" name="peminjaman_id" id="peminjaman_id">
                    <button class="btn btn-success btn-aksi w-100">Konfirmasi Pengembalian</button>
                </form>
                <button type="button" class="btn btn-secondary btn-aksi"
                    data-dismiss="modal" style="min-width: 240px;">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')   <!-- sc -->
<!-- pop up sebelum balikin -->
<script>
function loadPengembalian(id) {

    fetch("/pengembalian/preview/" + id)
        .then(res => res.json())
        .then(res => {

            let d = res.data;

            // ============================
            // INFORMASI ANGGOTA
            // ============================
            document.getElementById("modal_nomor_anggota").textContent = d.anggota.nomor_anggota;
            document.getElementById("modal_nama_anggota").textContent  = d.anggota.nama;

            // ============================
            // INFORMASI BUKU
            // ============================
            document.getElementById("modal_kode_buku").textContent = d.buku.id_buku;
            document.getElementById("modal_judul_buku").textContent = d.buku.judul;

            // ============================
            // INFORMASI TANGGAL
            // ============================
            function formatDate(dateString) {
                return new Date(dateString).toISOString().split("T")[0];
            }
            document.getElementById("modal_tgl_pinjam").textContent = formatDate(d.tanggal_pinjam);
            document.getElementById("modal_batas_kembali").textContent = formatDate(d.batas_kembali);


            // tanggal kembali = hari ini
            let today = new Date().toISOString().split("T")[0];
            document.getElementById("modal_tgl_kembali").textContent = today;

            // ============================
            // STATUS & DENDA
            // ============================
            let statusBox = document.getElementById("statusBox");
            statusBox.innerHTML = "";

            if (res.hariTelat > 0) {
                statusBox.style.background = "#ef4444";
                statusBox.style.color = "white";

                statusBox.innerHTML = `
                    <h3><i class='fas fa-exclamation-triangle'></i></h3>
                    <h4>Terlambat</h4>
                    <p>Buku dikembalikan melewati batas waktu.</p>

                    <div class="mt-3">
                        <h6>Keterlambatan</h6>
                        <h2>${res.hariTelat}</h2>
                        <small>Hari</small>
                    </div>

                    <div class="mt-3 p-3" style="background:#fecaca; border-radius:10px;">
                        <h6>Total Denda</h6>
                        <h2>Rp${res.denda.toLocaleString()}</h2>
                    </div>
                `;
            } else {
                statusBox.style.background = "#22c55e";
                statusBox.style.color = "white";

                statusBox.innerHTML = `
                    <h3><i class='fas fa-check-circle'></i></h3>
                    <h4>Tepat Waktu</h4>
                    <p>Buku dikembalikan tepat waktu.</p>

                    <div class="mt-3">
                        <h6>Keterlambatan</h6>
                        <h2>0</h2>
                        <small>Hari</small>
                    </div>

                    <div class="mt-3 p-3" style="background:#BCC4C0; border-radius:10px;">
                        <h6>Total Denda</h6>
                        <h2>Rp0</h2>
                    </div>
                `;
            }

            // SET FORM VALUE
            document.getElementById("peminjaman_id").value = d.id;

            // SHOW MODAL
            $("#modalPengembalian").modal("show");
        });
}
</script>

<!-- open modal pengembalian -->
<script>
function openModal(peminjaman) {
    $('#peminjaman_id').val(data.id);
    $("#modalPengembalian").modal("show");
}
</script>

<!-- notif ilang -->
<script>
    setTimeout(function () {
        $('.alert').slideUp(400);
    }, 3000);
</script>

<script>
$(document).ready(function () {

    // ======================
    // LIVE SEARCH TRANSAKSI
    // ======================
    $('#searchTransaksi').on('keyup', function () {
        let keyword = $(this).val().trim();

        $.ajax({
            url: "{{ route('pengembalian.index') }}",
            type: "GET",
            data: {
                search_transaksi: keyword,
                per_page_transaksi: "{{ $perPageTransaksi }}",
                per_page_riwayat: "{{ $perPageRiwayat }}"
            },
            success: function (html) {

                $('#transaksiTable').html($(html).find('#transaksiTable').html());
                $('#transaksiPaginationInfo').html($(html).find('#transaksiPaginationInfo').html());
                $('#transaksiPaginationLinks').html($(html).find('#transaksiPaginationLinks').html());
            }
        });
    });

    // ======================
    // LIVE SEARCH RIWAYAT
    // ======================
    $('#searchRiwayat').on('keyup', function () {
        let keyword = $(this).val().trim();

        $.ajax({
            url: "{{ route('pengembalian.index') }}",
            type: "GET",
            data: {
                search_riwayat: keyword,
                per_page_transaksi: "{{ $perPageTransaksi }}",
                per_page_riwayat: "{{ $perPageRiwayat }}"
            },
            success: function (html) {

                $('#riwayatTable').html($(html).find('#riwayatTable').html());
                $('#riwayatPaginationInfo').html($(html).find('#riwayatPaginationInfo').html());
                $('#riwayatPaginationLinks').html($(html).find('#riwayatPaginationLinks').html());
            }
        });
    });

});
</script>
@endpush