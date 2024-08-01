<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemakaian</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
            border: 1px solid #000000;
            padding: 8px;
        }
        table th {
            background-color: #3b9fdd;
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

        .no-border-table {
            border-collapse: collapse;
        }
        .no-border-table td {
            border: none;
        }
        .pemakaian {
            margin-bottom: 0; /* Menghapus margin bawah */
        }
        .margin-bottom {
            margin-bottom: 10px; /* Mengatur margin bawah */
        }
        .barang {
            margin-bottom: 10; /* Menghapus margin bawah */
        }

    </style>
</head>
<body>
<table class="no-border-table">
    <tr>
        <td style="width: 100px;">
            <img src="./img/logo-SMKN-1-Cbn.png" alt="Logo SMKN" style="width: 120px; height: auto; margin-left: 20px;">
        </td>
        <td>
            <table class="no-border-table">
                <tr>
                    <td colspan="2" align="center" style="padding-bottom: 0px; padding-right: 60px;">PEMERINTAH DAERAH PROVINSI JAWA BARAT <br>
                    DINAS PENDIDIKAN</td>
                </tr>
                <tr>
                    <td colspan="2" align="center" style="padding-top: 0px; padding-right: 50px;">
                        <b>CABANG DINAS PENDIDIKAN WILAYAH I</b><br>
                        <b>SMK NEGERI 1 CIBINONG</b><br>
                        Jl. Karadenan No. 7 Cibinong, Bogor - telp. +622518663846 Fax. +622518665558<br>
                        e_mail: <a href="mailto:admin@smkn1cibinong.sch.id">admin@smkn1cibinong.sch.id</a>
                        website: <a href="http://www.smkn1.cibinong.sch.id">www.smkn1.cibinong.sch.id</a><br>         
                        CIBINONG-16913
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="border-top: 3pt solid black; margin-bottom: 2px;"></div>
<div style="border-bottom: 1px solid black; margin-bottom: 10px;"></div> 

    <h2 align="center" style="margin-left: 75px; margin-bottom: 20px;">Laporan Data Pemakaian Barang SIJA</h2>
        @if(session('selected_nama_peminjam'))
            @if(!$id_barang)
                <p class="pemakaian margin-bottom"><b>Nama Peminjam:</b> {{ ucwords(session('selected_nama_peminjam')) }}</p>
            @else
                <p class="pemakaian"><b>Nama Peminjam:</b> {{ ucwords(session('selected_nama_peminjam')) }}</p>
            @endif
        @endif
        @if($id_barang)
            <p class="barang"><b>Barang:</b> {{ $nama_barang }}</p>
        @endif        
        @if($start_date && $end_date)
            <p><b>Periode:</b><br>
            Tanggal Awal: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }}<br>
            Tanggal Akhir: {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
        @endif
                  
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kelas Jurusan</th>
                <th>Tanggal Pakai</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
            </tr>
        </thead>
        <tbody>
            @php $nomorUrut = 1; @endphp
                @foreach($pemakaians as $key => $pemakaian)
                    @foreach($dataDetail as $detail)
                        @if(isset($detail['id_pemakaian']) && $detail['id_pemakaian'] == $pemakaian->id_pemakaian)
                            @if(empty($nama_barang) || $detail->inventaris->barang['nama_barang'] == $nama_barang)
                                @if(($pemakaian->id_users !== 1 && $pemakaian->users->name == session('selected_nama_peminjam')) || 
                                    ($pemakaian->id_guru !== 1 && $pemakaian->guru->nama_guru == session('selected_nama_peminjam')) || 
                                    ($pemakaian->id_karyawan !== 1 && $pemakaian->karyawan->nama_karyawan == session('selected_nama_peminjam')) ||
                                    (session('selected_nama_peminjam') == null))
                                    <tr>
                                        <td>{{ $nomorUrut++ }}</td>
                                        <td>
                                            @if($pemakaian->id_users !== 1) 
                                                {{ $pemakaian->users->name}}
                                            @elseif($pemakaian->id_guru !== 1) 
                                                {{ $pemakaian->guru->nama_guru}}
                                            @elseif($pemakaian->id_karyawan !== 1) 
                                                {{ $pemakaian->karyawan->nama_karyawan}}
                                            @endif
                                        </td>
                                        <td>
                                        @if($pemakaian->id_karyawan !== 1) 
                                            <span style="display: block; text-align: center;">-</span>
                                        @else
                                            @if(!empty($pemakaian->users->profile))
                                                {{ $pemakaian->users->profile->kelas }} {{ $pemakaian->users->profile->jurusan }}
                                            @else
                                                <span style="display: block; text-align: center;">-</span>
                                            @endif
                                        @endif
                                        </td>                                        
                                        @endif
                                        <td>{{ \Carbon\Carbon::parse($pemakaian->tgl_pakai)->format('d F Y') }}</td>
                                        <td>{{ $detail->inventaris->barang['nama_barang'] }}</td>
                                        <td>{{ $detail['jumlah_barang'] }}</td>
                                    </tr>
                                @endif
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div> 
<div class="tanda-tangan">
    <p>Cibinong, {{ date('d F Y') }} <br>
        Yang Menyatakan,</p>
            <br>
            <br>
            <br>
            <br>
            <p>(...............................................) <br>
            {{$userName}}</p>
        </div>
    </div>
</body>
</html>
