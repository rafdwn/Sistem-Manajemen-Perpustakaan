<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $perPage  = $request->input('per_page', 10);

        $sort  = $request->input('sort', 'id_buku');
        $order = $request->input('order', 'asc');

        $buku = Buku::when($search, function ($query) use ($search) {
                        $query->where('kode_buku', 'LIKE', "%$search%")
                            ->orWhere('judul', 'LIKE', "%$search%")
                            ->orWhere('pengarang', 'LIKE', "%$search%")
                            ->orWhere('penerbit', 'LIKE', "%$search%");
                    })
                    ->orderByRaw('CAST(SUBSTRING(id_buku, 3) AS UNSIGNED) ' . $order)
                    ->paginate($perPage)
                    ->appends([
                        'search' => $search,
                        'per_page' => $perPage,
                        'sort' => $sort,
                        'order' => $order,
                    ]);

        return view('buku.index', compact('buku', 'search', 'perPage', 'sort', 'order'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $lastBook = Buku::orderBy('id_buku', 'desc')->first();
        if ($lastBook) {
            $lastNumber = intval(substr($lastBook->id_buku, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newIdBuku = 'BK' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $validated = $request->validate([
            'kode_buku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|integer',
            'stok' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0',
            'cover' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $coverName = time() . '.' . $request->cover->extension();
        $request->cover->storeAs('public/covers', $coverName);

        Buku::create([
            'id_buku' => $newIdBuku,
            'kode_buku' => $request->kode_buku,
            'judul' => $request->judul,
            'pengarang' => $request->pengarang,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'stok' => $request->stok,
            'stok_tersedia' => $request->stok_tersedia,
            'cover' => $coverName,
        ]);

        return redirect()->route('buku.index')
            ->with('success_add', 'Buku berhasil ditambahkan!')
            ->with(['sort' => 'id_buku', 'order' => 'asc']);
    }

    public function edit($kode_buku) 
    {
        $buku = Buku::where('kode_buku', $kode_buku)->firstOrFail();
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $kode_buku)
    {
        $buku = Buku::where('kode_buku', $kode_buku)->firstOrFail();

        $validated = $request->validate([
            'kode_buku' => 'required',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|integer',
            'stok' => 'required|integer',
            'stok_tersedia' => 'required|integer',
            'cover' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $coverName = time() . '.' . $request->cover->extension();
            $request->cover->storeAs('public/covers', $coverName);

            $validated['cover'] = $coverName;
        }

        $buku->update($validated);

        return redirect()->route('buku.index')
                        ->with('success_edit','Data buku berhasil diperbarui!')
                        ->with(['sort' => 'id_buku', 'order' => 'asc']);
    }


    public function destroy($kode_buku)
    {
        $buku = Buku::where('kode_buku', $kode_buku)->firstOrFail();
        $buku->delete();

        return redirect()->route('buku.index')
                        ->with('success_delete', 'Buku berhasil dihapus!')
                        ->with(['sort' => 'id_buku', 'order' => 'asc']);
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $buku = Buku::where('kode_buku', 'LIKE', "%$keyword%")
                ->orWhere('judul', 'LIKE', "%$keyword%")
                ->orWhere('pengarang', 'LIKE', "%$keyword%")
                ->orWhere('penerbit', 'LIKE', "%$keyword%")
                ->get();

        return response()->json($buku);
    }

    public function show($kode_buku) 
    {
        $buku = Buku::where('kode_buku', $kode_buku)->firstOrFail();
        return view('buku.show', compact('buku'));
    }
}
