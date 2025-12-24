<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'id_peminjaman',
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'batas_kembali',
        'tanggal_kembali',
        'status'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }


    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function denda()
    {
        return $this->hasOne(Denda::class);
    }
}
