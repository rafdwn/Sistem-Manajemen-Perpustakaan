<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use TCPDF;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        $buku = Buku::all(); // ambil semua buku tanpa batas

        return view('landing.index', compact('buku'));
    }

    public function cetakInfo($id)
    {
        $buku = Buku::where('id_buku', $id)->firstOrFail();

        // Ukuran thermal receipt: 75mm x auto height
        $pdf = new TCPDF('P', 'mm', [75, 200], true, 'UTF-8', false);
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(TRUE, 5);
        $pdf->AddPage();

        $tanggal = Carbon::now()->format('d/m/Y H:i');

        $html = <<<HTML
        <style>
            body { font-size: 11px; }
            .center { text-align:center; }
            .line { border-top:1px solid #000; margin:6px 0; }
            table { width: 100%; }
            td { vertical-align: top; padding: 2px 0; }
            .label { width: 37%; font-weight: bold;}
            .colon { width: 5%; }
            .value { width: 67%; }
        </style>

        <div class="center">
            <h3 style="margin: 0;">Sistem Perpustakaan</h3>
            <span>"Membaca adalah jendela dunia"</span>
        </div>

        <div class="line"></div>

        <b>Tanggal Cetak:</b> {$tanggal}

        <div class="line"></div>

        <b>Informasi Buku</b><br>

        <table>
            <tr>
                <td class="label">Judul</td>
                <td class="colon">:</td>
                <td class="value">{$buku->judul}</td>
            </tr>

            <tr>
                <td class="label">Pengarang</td>
                <td class="colon">:</td>
                <td class="value">{$buku->pengarang}</td>
            </tr>

            <tr>
                <td class="label">Penerbit</td>
                <td class="colon">:</td>
                <td class="value">{$buku->penerbit}</td>
            </tr>

            <tr>
                <td class="label">Tahun</td>
                <td class="colon">:</td>
                <td class="value">{$buku->tahun}</td>
            </tr>

            <tr>
                <td class="label">ISBN</td>
                <td class="colon">:</td>
                <td class="value">{$buku->kode_buku}</td>
            </tr>
        </table>

        <div class="line"></div>

        <div class="center">
            Terima kasih telah menggunakan<br>
            layanan kami
        </div>
        HTML;

        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output('info-buku-'.$buku->id_buku.'.pdf', 'I');
    }
        
}
