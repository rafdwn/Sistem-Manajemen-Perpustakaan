<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index()
    {
        // Total denda BELUM dibayar = dari transaksi pengembalian yg masih dipinjam
        $totalBelumDibayar = Peminjaman::where('status', 'Dipinjam')
            ->get()
            ->sum(function ($item) {
                $batas = \Carbon\Carbon::parse($item->batas_kembali)->startOfDay();
                $today = now()->startOfDay();

                if ($today->gt($batas)) {
                    $hariTelat = $batas->diffInDays($today);
                    return $hariTelat * 10000;
                }
                return 0;
            });

        // Total denda SUDAH dibayar = hanya ambil yang terlambat
        $totalSudahDibayar = Pengembalian::where('status', 'Terlambat')->sum('denda');

        // Daftar riwayat yang hanya mengambil data telat
        $riwayat = Pengembalian::with(['anggota', 'buku'])
            ->where('status', 'Terlambat')
            ->get();

        return view('denda.index', compact(
            'totalBelumDibayar',
            'totalSudahDibayar',
            'riwayat'
        ));
    }
}
