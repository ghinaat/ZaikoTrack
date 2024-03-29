<div>

</div>
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Ambil pesan sukses dari session (jika ada) dan tampilkan menggunakan SweetAlert2
    var successMessage = '{{ session('
    success_message ') }}';
    var successChanged = '{{ session('
    success_changed ') }}';
    var successDeleted = '{{ session('
    success_deleted ') }}';
    var errorMessage = '{{ session('
    error ') }}';
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: successMessage,
        });
    }
    if (errorMessage) { // Tambahkan ini
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage,
        });
    }
    // if (successChanged) {
    //     Swal.fire({
    //         icon: 'success',
    //         title: 'Sukses!',
    //         text: successChanged,
    //     });
    // }
    // if (successDeleted) {
    //     Swal.fire({
    //         icon: 'success',
    //         title: 'Deleted!',
    //         text: successDeleted,
    //     });
    // }
});

function notificationBeforeDelete(event, el, dt) {
    event.preventDefault();
    Swal.fire({
        title: 'Apa kamu yakin?',
        text: "Data akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi penghapusan, lakukan penghapusan dengan mengirimkan form
            $("#delete-form").attr('action', $(el).attr('href'));
            $("#delete-form").submit();
        }
    });
}

function notificationBeforeAdd(event, el, dt) {
    event.preventDefault();

    Swal.fire({
        title: 'Pilihan Tambah Data',
        text: 'Pilih cara untuk menambahkan data:',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Dengan Barcode',
        cancelButtonText: 'Tanpa Barcode'

    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna memilih "Dengan Barcode"
            window.location.href = '/inventaris/barcode'; // Ganti dengan URL halaman yang sesuai
        } else {
            // Jika pengguna memilih "Tanpa Barcode", tampilkan add modal
            showAddModal();
        }
        $('#addModal').modal('hide');
    });
}

function showAddModal() {

    $('#addModal').modal('show');
}

function addData() {

    $('#addModal').modal('hide');
}
</script>
@endpush