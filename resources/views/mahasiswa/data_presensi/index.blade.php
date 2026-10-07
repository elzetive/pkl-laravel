@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow border-0" style="width: 100%; max-width: 420px;">
        <div class="card-header text-white text-center py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-qrcode mr-2"></i>Scan QR Code Presensi
            </h6>
        </div>
        <div class="card-body text-center p-4">
            <p class="text-muted small mb-3">
                Arahkan kamera ke QR Code yang ditampilkan oleh dosen.
            </p>

            <form id="form-presensi" action="{{ route('mahasiswa.data_presensi.proses') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id_pertemuan" id="input_id_pertemuan">
            </form>

            <div class="container-fluid">
                <div id="reader" style="width: 100%; max-width: 600px; margin: auto;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        html5QrcodeScanner.clear();
        document.getElementById('input_id_pertemuan').value = decodedText;
        document.getElementById('form-presensi').submit();
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 }
    );
    html5QrcodeScanner.render(onScanSuccess);

    let currentStatus = "{{ $pertemuan->status_pertemuan ?? '0' }}";
    const idPertemuan = "{{ $pertemuan->id_pertemuan ?? '' }}";

    if (idPertemuan) {
        setInterval(function() {
            fetch(`/mahasiswa/data_presensi/cek-status/${idPertemuan}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status_pertemuan !== currentStatus) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking status:', error));
        }, 3000);
    }
</script>

@if (session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

@if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

@endsection
