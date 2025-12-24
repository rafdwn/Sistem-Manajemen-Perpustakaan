<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #eee; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>LAPORAN {{ strtoupper($jenis) }}</h2>
<p>Periode: {{ $dari }} s/d {{ $sampai }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Anggota</th>
            <th>Buku</th>

            @if($jenis == 'peminjaman')
                <th>Tanggal Pinjam</th>
                <th>Batas Kembali</th>
                <th>Status</th>
            @elseif($jenis == 'pengembalian')
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            @elseif($jenis == 'denda')
                <th>Tanggal Kembali</th>
                <th>Denda</th>
            @endif

        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->anggota->nama }}</td>
            <td>{{ $item->buku->judul }}</td>

            @if($jenis == 'peminjaman')
                <td>{{ $item->tanggal_pinjam }}</td>
                <td>{{ $item->batas_kembali }}</td>
                <td>{{ $item->status }}</td>
            @elseif($jenis == 'pengembalian')
                <td>{{ $item->tanggal_kembali }}</td>
                <td>{{ $item->status }}</td>
                <td>Rp {{ number_format($item->denda,0,',','.') }}</td>
            @elseif($jenis == 'denda')
                <td>{{ $item->tanggal_kembali }}</td>
                <td>Rp {{ number_format($item->denda,0,',','.') }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
