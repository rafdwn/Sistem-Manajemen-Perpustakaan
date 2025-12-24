<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Buku;
use App\Models\Anggota;
use Carbon\Carbon;
use TCPDF;

class LaporanController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalDipinjam = Peminjaman::count();
        $totalTransaksi = Peminjaman::count() + Pengembalian::count();
        $totalDikembalikan = Pengembalian::count();

        $totalDendaBelumBayar = Peminjaman::where('status', 'Dipinjam')
            ->get()
            ->sum(function ($item) {
                $batas = Carbon::parse($item->batas_kembali)->startOfDay();
                $today = now()->startOfDay();

                if ($today->gt($batas)) {
                    return $batas->diffInDays($today) * 10000;
                }
                return 0;
            });

        $totalDendaSudahBayar = Pengembalian::where('status', 'Terlambat')->sum('denda');

        return view('laporan.index', compact(
            'totalBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalTransaksi',
            'totalDikembalikan',
            'totalDendaBelumBayar',
            'totalDendaSudahBayar'
        ));
    }
    
public function cetak(Request $request)
{
    $request->validate([
        'jenis'  => 'required',
        'dari'   => 'required|date',
        'sampai' => 'required|date'
    ]);

    $jenis = $request->jenis;

    // ============================
    // AMBIL DATA
    // ============================
    if ($jenis === 'peminjaman') {
        $data = Peminjaman::with(['anggota', 'buku'])
            ->whereBetween('tanggal_pinjam', [$request->dari, $request->sampai])
            ->get();
    } 
    elseif ($jenis === 'pengembalian') {
        $data = Pengembalian::with(['anggota', 'buku'])
            ->whereBetween('tanggal_kembali', [$request->dari, $request->sampai])
            ->get();
    } 
    else { // denda
        $data = Pengembalian::with(['anggota', 'buku'])
            ->where('status', 'Terlambat')
            ->whereBetween('tanggal_kembali', [$request->dari, $request->sampai])
            ->get();
    }

    // ============================
    // SETUP PDF
    // ============================
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();
    $pdf->SetTitle('LAPORAN ' . strtoupper($jenis) . ' - ' . $request->dari . ' s/d ' . $request->sampai);

    // ============================
    // HEADER LOGO + TEKS PROFESIONAL
    // ============================

    // lebar halaman
    $pageWidth = $pdf->GetPageWidth() - 30;

    // posisi Y sedikit turun
    $pdf->SetY(15);

    // LOGO (kiri)
    $logoPath = public_path('logo.png');
    $pdf->Image($logoPath, 15, 15, 22); // x,y,width

    // TEKS HEADER (kanan logo)
    $pdf->SetXY(40, 15);
    $pdf->SetFont('Helvetica','B',14);
    $pdf->Cell(0, 6, 'SISTEM PERPUSTAKAAN', 0, 1, 'L');

    $pdf->SetFont('Helvetica','',10);
    $pdf->SetX(40);
    $pdf->Cell(0, 5, 'Manajemen Perpustakaan Digital Terpadu', 0, 1, 'L');

    $pdf->SetX(40);
    $pdf->Cell(0, 5, 'Jl. Pendidikan No. 123, Jakarta | (021) 1234-5678 | sistemperpustakaanrpl@gmail.com', 0, 1, 'L');

    // GARIS PEMBATAS
    $pdf->Ln(2);
    $pdf->Line(15, 35, $pageWidth + 15, 35);
    $pdf->Ln(8);

    // ============================
    // JUDUL DI TENGAH
    // ============================
    $pdf->SetFont('Helvetica', 'B', 13);
    $pdf->Cell(0, 7, "LAPORAN " . strtoupper($jenis), 0, 1, 'C');

    $periode = "Periode Laporan: " . date('d M Y', strtotime($request->dari)) .
               " s/d " . date('d M Y', strtotime($request->sampai));

    $pdf->SetFont('Helvetica', '', 11);
    $pdf->Cell(0, 6, $periode, 0, 1, 'C');
    $pdf->Ln(3);

    // ============================
    // TABEL DATA
    // ============================
    $tbl = '
        <table border="1" cellpadding="4" cellspacing="0">
            <tr style="background-color:#e8e8e8; text-align:center; font-weight:bold;">
                <th width="25">No</th>
                <th width="120">Nama Anggota</th>
                <th width="120">Nama Buku</th>
                <th width="60">Tgl Pinjam</th>
                <th width="60">Tgl Kembali</th>
                <th width="55">Status</th>
                <th width="55">Denda</th>
            </tr>
    ';

    $no = 1;
    foreach ($data as $item) {

        // ============================
        // FIX TANGGAL KEMBALI BERDASARKAN JENIS LAPORAN
        // ============================
        if ($jenis === 'peminjaman') {
            // LAPORAN PEMINJAMAN = BATAS KEMBALI
            $tglKembali = $item->batas_kembali;
            $denda = 0;
        } else {
            // LAPORAN PENGEMBALIAN / DENDA
            $tglKembali = $item->tanggal_kembali ?? '-';
            $denda = $item->denda ?? 0;
        }

        $tbl .= "
            <tr>
                <td align='center'>{$no}</td>
                <td>{$item->anggota->nama}</td>
                <td>{$item->buku->judul}</td>
                <td>{$item->tanggal_pinjam}</td>
                <td>{$tglKembali}</td>
                <td>{$item->status}</td>
                <td>Rp".number_format($denda, 0, ',', '.')."</td>
            </tr>
        ";

        $no++;
    }

    $tbl .= "</table>";

    $pdf->writeHTML($tbl, true, false, true, false, '');
    $pdf->Ln(8);

    // ============================
    // DISCLAIMER
    // ============================
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->MultiCell(0, 5,
        "Dokumen ini dicetak otomatis oleh Sistem Perpustakaan Digital.\nTidak memerlukan tanda tangan basah.",
        0, 'L'
    );

    $pdf->Ln(10);

    // ============================
    // TTD RATa KANAN
    // ============================
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Cell(0, 5, "Jakarta, " . date('d F Y'), 0, 1, 'R');

    $pdf->Ln(15);

    $pdf->SetFont('Helvetica', 'B', 11);
    $pdf->Cell(0, 5, "Petugas Perpustakaan", 0, 1, 'R');

    return $pdf->Output("laporan-{$jenis}.pdf", 'I');
}

}