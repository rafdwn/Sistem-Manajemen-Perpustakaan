<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';
    // Gunakan id sebagai primary key (default Laravel)
    // Hapus/comment baris berikut:
    // protected $primaryKey = 'kode_buku';
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'id_buku',
        'kode_buku',
        'judul',
        'pengarang',
        'penerbit',
        'tahun',
        'stok',
        'stok_tersedia',
        'cover'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}