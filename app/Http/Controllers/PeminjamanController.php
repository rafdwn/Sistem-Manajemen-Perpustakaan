<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Carbon\Carbon;
use App\Models\Buku;
use Illuminate\Http\Request;
use TCPDF;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search', '');
        $sort    = $request->get('sort', 'id_peminjaman');
        $order   = $request->get('order', 'asc');

        $anggota = Anggota::all();
        $buku = Buku::all();

        $query = Peminjaman::with(['anggota','buku']);

        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%");
            });
        }

        if ($sort === 'id_peminjaman') {
            $query->orderByRaw('CAST(SUBSTRING(id_peminjaman, 3) AS UNSIGNED) ' . $order);
        } else {
            $query->orderBy($sort, $order);
        }

        $peminjaman = $query->paginate($perPage);

        return view('peminjaman.index', compact(
            'peminjaman', 'sort', 'order', 'perPage', 'search', 'anggota', 'buku'
        ));
    }

    public function create()
    {
        // Pakai generator ID baru
        $newId = $this->generateIdPeminjaman();

        return response()->json([
            'idBaru' => $newId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'     => 'required',
            'buku_id'        => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        // Pakai generator ID baru
        $nextId = $this->generateIdPeminjaman();

        $tanggalPinjam = Carbon::createFromFormat('Y-m-d', $request->tanggal_pinjam);
        $batasKembali = $tanggalPinjam->copy()->addDays(7);

        // cek stok
        $buku = Buku::findOrFail($request->buku_id);
        if ($buku->stok_tersedia < 1) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Stok buku habis!');
        }

        // simpan data
        Peminjaman::create([
            'id_peminjaman'  => $nextId,
            'anggota_id'     => $request->anggota_id,
            'buku_id'        => $request->buku_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'batas_kembali'  => $batasKembali,
            'status'         => 'Dipinjam',
        ]);

        // kurangi stok
        $buku->stok_tersedia -= 1;
        $buku->save();

        return redirect()->route('peminjaman.index')
            ->with('success_add', 'Peminjaman berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $hapus = Peminjaman::findOrFail($id);

        // Kembalikan stok buku
        $buku = $hapus->buku;
        if ($buku) {
            $buku->stok_tersedia += 1;
            $buku->save();
        }

        // Hapus data peminjaman
        $hapus->delete();

        return redirect()->route('peminjaman.index')
            ->with('success_delete', 'Data berhasil dihapus dan stok buku dikembalikan!');
    }

    private function generateIdPeminjaman()
    {
        // Ambil ID terakhir dari tabel peminjaman
        $last1 = Peminjaman::orderBy('id_peminjaman', 'desc')->first();

        // Ambil ID terakhir dari tabel pengembalian
        $last2 = Pengembalian::orderBy('id_peminjaman', 'desc')->first();

        // Ambil angka setelah huruf PM
        $num1 = $last1 ? intval(substr($last1->id_peminjaman, 2)) : 0;
        $num2 = $last2 ? intval(substr($last2->id_peminjaman, 2)) : 0;

        // Pakai angka terbesar
        $next = max($num1, $num2) + 1;

        return 'PM' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'anggota_id'     => 'required',
            'buku_id'        => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        // update data
        $peminjaman->anggota_id = $request->anggota_id;
        $peminjaman->buku_id = $request->buku_id;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;

        // hitung ulang batas kembali
        $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $peminjaman->batas_kembali = $tanggalPinjam->copy()->addDays(7);

        $peminjaman->save();

        return redirect()->route('peminjaman.index')
            ->with('success_edit', 'Data peminjaman berhasil diperbarui!');
    }

    public function cetakPdf($id)
    {
        $p = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        // Load TCPDF
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Perpustakaan');
        $pdf->SetAuthor('Admin Perpustakaan');
        $pdf->SetTitle('Laporan Peminjaman - ' . $p->id_peminjaman);

        // Hilangkan header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Margin
        $pdf->SetMargins(15, 15, 15);

        // Halaman baru
        $pdf->AddPage();

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

        // JUDUL LAPORAN
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetY(38);
        $pdf->Cell(0, 10, 'BUKTI PEMINJAMAN BUKU', 0, 1, 'C');

        $pdf->Ln(4);

        // === ISI TABEL ===
        $pdf->SetFont('helvetica', '', 11);

        $html = '
        <table cellpadding="5" cellspacing="0" style="border:1px solid #000; width:100%;">
            <tr style="background-color:#f2f2f2;">
                <td width="35%"><b>ID Peminjaman</b></td>
                <td width="65%">'.$p->id_peminjaman.'</td>
            </tr>
            <tr>
                <td><b>Nama Anggota</b></td>
                <td>'.$p->anggota->nama.'</td>
            </tr>
            <tr style="background-color:#f9f9f9;">
                <td><b>ID Buku</b></td>
                <td>'.$p->buku->id_buku.'</td>
            </tr>
            <tr>
                <td><b>Judul Buku</b></td>
                <td>'.$p->buku->judul.'</td>
            </tr>
            <tr style="background-color:#f9f9f9;">
                <td><b>Tanggal Pinjam</b></td>
                <td>'.$p->tanggal_pinjam.'</td>
            </tr>
            <tr>
                <td><b>Batas Kembali</b></td>
                <td>'.$p->batas_kembali.'</td>
            </tr>
            <tr style="background-color:#f9f9f9;">
                <td><b>Status</b></td>
                <td>'.$p->status.'</td>
            </tr>
        </table>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        // FOOTER
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->Cell(0, 10, 'Dicetak pada: '.date('d-m-Y H:i:s'), 0, 1, 'R');

        // Output
        $pdf->Output('peminjaman_'.$p->id_peminjaman.'.pdf', 'I');
    }
}
