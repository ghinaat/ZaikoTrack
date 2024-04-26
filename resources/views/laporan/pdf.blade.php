<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        /* Gaya CSS untuk PDF */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
        }
        .kopsurat {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .tanda-tangan {
            position: absolute;
            bottom: 20px;
            right: 20px;
            text-align: center;
            width: 200px;
        }
    </style>
</head>
<body>
    <h2 align="center">Laporan Data Peminjaman Barang SIJA</h2>
    @if($tglawal && $tglakhir)
    <p>Periode:</p>
    <p>Tanggal Awal: {{ \Carbon\Carbon::parse($tglawal)->format('d F Y') }}</p>
    <p>Tanggal Akhir: {{ \Carbon\Carbon::parse($tglakhir)->format('d F Y') }}</p>
    @endif
    @if($id_barang)
    <p>Barang: {{ $nama_barang }}</p>
    @endif
  
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kelas Jurusan</th>
                <th>Tanggal Pinjam</th>
                <th>Nama Barang</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $nomorUrut = 1; @endphp
            @foreach($peminjamans as $key => $peminjaman)
                @foreach($dataDetail as $detail)
                    @if(isset($detail['id_peminjaman']) && $detail['id_peminjaman'] == $peminjaman->id_peminjaman)
                        <tr>
                            <td>{{ $nomorUrut++ }}</td>
                            <td>
                                @if($peminjaman->id_users !== 1) 
                                    {{ $peminjaman->users->name}}
                                @elseif($peminjaman->id_guru !== 1) 
                                    {{ $peminjaman->guru->nama_guru}}
                                @elseif($peminjaman->id_karyawan !== 1) 
                                    {{ $peminjaman->karyawan->nama_karyawan}}
                                @endif
                            </td>
                            <td>{{ $peminjaman->kelas }} {{ $peminjaman->jurusan }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d F Y') }}</td>
                            <td>{{ $detail->inventaris->barang['nama_barang'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d F Y') }}</td>
                            <td>{{ $detail['status'] }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div>
        <div class="tanda-tangan">
            <p>Cibinong, {{ date('d F Y') }}</p>
            <p>Yang Menyatakan,</p>
            <br>
            <br>
            <br>
            <br>
            <p>{{$userName}}</p>
        </div>
    </div>
</body>
</html>
