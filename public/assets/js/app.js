const swalDeleteButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-outline-danger px-3 ms-2',
        cancelButton: 'btn btn-outline-secondary px-3'
    },
    buttonsStyling: false
})

const swalSaveButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-outline-success px-3 ms-2',
        cancelButton: 'btn btn-outline-secondary px-3'
    },
    buttonsStyling: false
})

$(".delete").on('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var href = $(this).attr('href');
    var name = $(this).data('name');

    swalDeleteButtons.fire({
        title: 'Hapus data "'+name+'"?',
        text: "Data yang telah dihapus tidak dapat dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: href,
                data: {},
                type: 'POST',
                success: function (e) {
                    swalDeleteButtons.fire(
                        'Data terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    ).then(function() {
                        window.location.reload(true);
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swalDeleteButtons.fire(
                    'Warning !!',
                    'Terjadi Kesalahan Data',
                    'error'
                    )
                }
            });
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {}
    })
});

$(".delete-img").on('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var href = $(this).attr('href');

    swalDeleteButtons.fire({
        title: 'Anda yakin ingin menghapus gambar?',
        text: "Data yang telah dihapus tidak dapat dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: href,
                data: {},
                type: 'POST',
                success: function (e) {
                    swalSaveButtons.fire(
                        'Gambar terhapus!',
                        'Gambar berhasil dihapus.',
                        'success'
                    ).then(function() {
                        window.location.reload(true);
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    swalSaveButtons.fire(
                    'Warning !!',
                    'Terjadi Kesalahan Data',
                    'error'
                    )
                }
            });
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {}
    })
});