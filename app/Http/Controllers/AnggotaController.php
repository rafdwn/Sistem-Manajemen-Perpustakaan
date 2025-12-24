<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sort = $request->input('sort', 'nomor_anggota'); 
        $order = $request->input('order', 'asc');

        $anggota = Anggota::when($search, function ($query) use ($search) {
                $query->where('nomor_anggota', 'LIKE', "%$search%")
                    ->orWhere('nama', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('no_telepon', 'LIKE', "%$search%")
                    ->orWhere('alamat', 'LIKE', "%$search%");
            })
            ->orderByRaw(
                $sort === 'nomor_anggota'
                    ? "CAST(SUBSTRING(nomor_anggota, 3) AS UNSIGNED) $order"
                    : "$sort $order"
            )
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage, 'sort' => $sort, 'order' => $order]);

        $lastAG = Anggota::select('nomor_anggota')
            ->where('nomor_anggota', 'LIKE', 'AG%')
            ->orderByRaw('CAST(SUBSTRING(nomor_anggota, 3) AS UNSIGNED) DESC')
            ->first();

        $nomorBaru = $lastAG
            ? "AG" . str_pad(intval(substr($lastAG->nomor_anggota, 2)) + 1, 3, '0', STR_PAD_LEFT)
            : "AG001";

        return view('anggota.index', compact('anggota','search','perPage','nomorBaru','sort','order'));
    }

    public function create()
    {
        return response()->json([
            'nomorBaru' => $this->generateNomorAnggota()
        ]);
    }

    public function store(Request $request)
    {
        Anggota::create([
            'nomor_anggota' => $this->generateNomorAnggota(),
            'nama'          => $request->nama,
            'email'         => $request->email,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
        ]);
        return redirect()->route('anggota.index')
                        ->with('success_add', 'Anggota berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return response()->json($anggota);
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->update([
            'nomor_anggota' => $request->nomor_anggota,
            'nama'          => $request->nama,
            'email'         => $request->email,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
        ]);
        return redirect()->route('anggota.index')->with('success_edit', 'Data anggota berhasil diperbarui!');

    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success_delete', 'Anggota berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $anggota = Anggota::where('nama', 'like', "%$keyword%")
                    ->orWhere('nomor_anggota', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('no_telepon', 'like', "%$keyword%")
                    ->orWhere('alamat', 'like', "%$keyword%")
                    ->get();
        return response()->json($anggota);
    }

    private function generateNomorAnggota()
    {
        $anggota = Anggota::select('nomor_anggota')
            ->where('nomor_anggota', 'LIKE', 'AG%')
            ->orderByRaw('CAST(SUBSTRING(nomor_anggota, 3) AS UNSIGNED) ASC')
            ->get();

        $usedNumbers = [];

        foreach ($anggota as $a) {
            $usedNumbers[] = intval(substr($a->nomor_anggota, 2));
        }

        $n = 1;
        while (in_array($n, $usedNumbers)) {
            $n++;
        }

        return "AG" . str_pad($n, 3, '0', STR_PAD_LEFT);
    }
}
