<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Http\Controllers\Controller;  

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $sedangDipinjam = Peminjaman::where('status', 'Dipinjam')->count();

        return view('dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'sedangDipinjam'
        ));
    }
}
