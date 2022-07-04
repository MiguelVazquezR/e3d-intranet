Livewire.on('success', function (message) {
    Swal.fire(
        'Éxito',
        message,
        'success'
    )
});

Livewire.on('error', function (message) {
    Swal.fire(
        'Error',
        message,
        'error'
    )
});

Livewire.on('confirm', data => {
    Swal.fire({
        title: '¿Desea continuar?',
        text: data[3],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, continuar'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emitTo(data[0], data[1], data[2]);
        }
    })
});
