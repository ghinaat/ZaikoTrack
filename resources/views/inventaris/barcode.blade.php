@extends('layouts.demo')
@section('title', 'Tambah Barang')
@section('css')
@endsection
@section('breadcrumb-name')
Tambah Barang
@endsection
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="m-0 text-dark text-center fs-3">Scan Barcode
                    </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.saveChangePassword') }}">
                        @csrf
                        <div class="form-group">
                            <label for="ket_barang">Scan</label>
                            <video id="previewKamera" style="width: 300px;height: 300px;"></video>
                            <br>
                            <select id="pilihKamera" style="max-width:400px">
                            </select>
                            <br>
                            <input type="text" id="hasilscan">

                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
<!-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"> -->
<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="{{ asset('js/barcode-scanner.js') }}"></script>



@push('js')
<script>
//Configure QuaggaJS
// const config = {
//     inputStream: {
//         name: "Live",
//         type: "LiveStream",
//         target: document.querySelector('#reader'),
//         constraints: {
//             width: 480,
//             height: 320,
//             facingMode: "environment", // or "user" for front camera
//         },
//     },
//     decoder: {
//         readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "qr_code_reader"],
//     },
// };

// // Start QuaggaJS
// Quagga.init(config, function(err) {
//     if (err) {
//         console.error(err);
//         return;
//     }
//     Quagga.start();
// });

// // Add event listener for detection
// Quagga.onDetected(function(result) {
//     const code = result.codeResult.code;
//     console.log(`Code detected: ${code}`);

// });
</script>
@endpush