<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | PAGINATION & SEARCH
        |--------------------------------------------------------------------------
        */
        $perPageTransaksi = $request->get('per_page_transaksi', 10);
        $perPageRiwayat   = $request->get('per_page_riwayat', 10);

        $searchTransaksi  = $request->get('search_transaksi', '');
        $searchRiwayat    = $request->get('search_riwayat', '');

        /*
        |--------------------------------------------------------------------------
        | SORTING PER TABEL
        |--------------------------------------------------------------------------
        */
        $sortTransaksi  = $request->get('sort_transaksi', 'id_peminjaman');
        $orderTransaksi = $request->get('order_transaksi', 'asc');

        $sortRiwayat    = $request->get('sort_riwayat', 'id_peminjaman');
        $orderRiwayat   = $request->get('order_riwayat', 'asc');


        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI PENGEMBALIAN (masih dipinjam)
        |--------------------------------------------------------------------------
        */
        $pengembalian = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->when($searchTransaksi, function ($q) use ($searchTransaksi) {
                $q->where(function($x) use ($searchTransaksi) {
                    $x->whereHas('anggota', fn($a) => $a->where('nama', 'like', "%$searchTransaksi%"))
                      ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%$searchTransaksi%"))
                      ->orWhere('id_peminjaman', 'like', "%$searchTransaksi%");
                });
            })
            ->orderBy($sortTransaksi, $orderTransaksi)
            ->paginate($perPageTransaksi, ['*'], 'transaksi_page')
            ->appends($request->all());


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENGEMBALIAN
        |--------------------------------------------------------------------------
        */
        $riwayat = Pengembalian::with(['anggota', 'buku'])
            ->when($searchRiwayat, function($q) use ($searchRiwayat){
                $q->where(function($x) use ($searchRiwayat) {
                    $x->where('id_peminjaman', 'like', "%$searchRiwayat%")
                      ->orWhereHas('anggota', fn($a) => $a->where('nama', 'like', "%$searchRiwayat%"))
                      ->orWhereHas('buku', fn($b) => $b->where('judul', 'like', "%$searchRiwayat%"));
                });
            })
            ->orderBy($sortRiwayat, $orderRiwayat)
            ->paginate($perPageRiwayat, ['*'], 'riwayat_page')
            ->appends($request->all());


        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */
        return view('pengembalian.index', compact(
            'pengembalian',
            'riwayat',
            'perPageTransaksi',
            'perPageRiwayat',
            'searchTransaksi',
            'searchRiwayat',
            'sortTransaksi',
            'orderTransaksi',
            'sortRiwayat',
            'orderRiwayat'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES PENGEMBALIAN
    |--------------------------------------------------------------------------
    */
    public function proses(Request $request)
    {
        $p = Peminjaman::with(['buku', 'anggota'])->findOrFail($request->peminjaman_id);

        $today = Carbon::now()->startOfDay();
        $tanggalKembali = $today;

        $batas = Carbon::parse($p->batas_kembali)->startOfDay();
        $hariTelat = $today->gt($batas) ? $batas->diffInDays($today) : 0;

        $denda  = $hariTelat * 10000;
        $status = $hariTelat > 0 ? 'Terlambat' : 'Tepat Waktu';

        // simpan pengembalian
        Pengembalian::create([
            'id_peminjaman'   => $p->id_peminjaman,
            'peminjaman_id'   => $p->id,
            'anggota_id'      => $p->anggota_id,
            'buku_id'         => $p->buku_id,
            'tanggal_pinjam'  => $p->tanggal_pinjam,
            'batas_kembali'   => $p->batas_kembali,
            'tanggal_kembali' => $tanggalKembali,
            'denda'           => $denda,
            'status'          => $status,
        ]);

        // kembalikan stok
        $p->buku->stok_tersedia += 1;
        $p->buku->save();

        // hapus peminjaman
        $p->delete();

        return back()->with('success_balik', 'Pengembalian berhasil diproses!');
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW (modal)
    |--------------------------------------------------------------------------
    */
    public function preview($id)
    {
        $data  = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        $today = now()->startOfDay();
        $batas = Carbon::parse($data->batas_kembali)->startOfDay();

        $hariTelat = $today->gt($batas) ? $batas->diffInDays($today) : 0;
        $denda = $hariTelat * 10000;

        return response()->json([
            'data'      => $data,
            'hariTelat' => $hariTelat,
            'denda'     => $denda
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX SEARCH TRANSAKSI
    |--------------------------------------------------------------------------
    */
    public function searchTransaksi(Request $request)
    {
        $keyword = $request->keyword;

        return Peminjaman::with(['anggota','buku'])
            ->where('status', 'Dipinjam')
            ->where(function($q) use ($keyword) {
                $q->where('id_peminjaman', 'like', "%$keyword%")
                  ->orWhereHas('anggota', fn($a) =>
                      $a->where('nama','like',"%$keyword%")
                  )
                  ->orWhereHas('buku', fn($b) =>
                      $b->where('judul','like',"%$keyword%")
                  );
            })
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX SEARCH RIWAYAT
    |--------------------------------------------------------------------------
    */
    public function searchRiwayat(Request $request)
    {
        $keyword = $request->keyword;

        return Pengembalian::with(['anggota','buku'])
            ->where(function($q) use ($keyword) {
                $q->where('id_peminjaman','like',"%$keyword%")
                  ->orWhereHas('anggota', fn($a) =>
                      $a->where('nama','like',"%$keyword%")
                  )
                  ->orWhereHas('buku', fn($b) =>
                      $b->where('judul','like',"%$keyword%")
                  );
            })
            ->get();
    }
}
