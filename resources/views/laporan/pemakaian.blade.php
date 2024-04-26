<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemakaian</title>
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
        .surat{
            padding-top: 50px;
            position: relative;
        }
        img{
            width: 90px;
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
    <main>
        <div class="surat">
            {{-- <div class="header">
                <table>
                    <tr>
                        <td style="width:50%;padding-left:15px;">
                            <img src="{{asset("img/cropped-logo-SMKN-1-Cbn-min.png")}}" alt="">
                                   
                        </td>
                        <td style="width:50%;">
                            <h5 style="margin-top: -40px;">The Southeast Asian Ministers of Education Organization (SEAMEO) <br>Regional Center for Quality Improvement of Teachers and Education Personnel (QITEP) in Language (SEAQIL) </h5>        
                        </td>
                    </tr>
                </table>
                <p style="padding:20px;font-size:12px;margin-top:-35px;">Jalan Gardu, Srengseng Sawah, Jagakarsa, Jakarta Seletan 12640, Indonesia | Telp.: +62 (021) 7888 4160 | Fax.: +62 (021) 7888 4073</p>
                <hr style="margin: -10px 20px 0 20px;height:0.1px;color:#000;padding:0px;">
            </div> --}}
            <div class="body">
                <h2 align="center">Laporan Data Pemakaian Barang SIJA</h2>
                    @if($start_date && $end_date)
                    <p>Periode:</p>
                    <p>Tanggal Awal: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }}</p>
                    <p>Tanggal Akhir: {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
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
                                        <td>{{ $pemakaian->kelas }} {{ $pemakaian->jurusan }}</td>
                                        <td>{{ $pemakaian->tgl_pakai }}</td>
                                        <td>{{ $detail->inventaris->barang['nama_barang'] }}</td>
                                        <td>{{ $detail['jumlah_barang'] }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                        </tbody>
                </table>

            </div>
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
    </main>
    
</body>

</html>
                
    

 