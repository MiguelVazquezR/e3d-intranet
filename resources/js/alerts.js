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

Livewire.on('two-options', data => {
    Swal.fire({
        title: '¿Qué desea hacer?',
        html: data[0],
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: data[1],
        denyButtonText: data[2],
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emitTo(data[3], data[4], data[5], true);
        } else if (result.isDenied) {
            Livewire.emitTo(data[3], data[4], data[5], false);
        }
    })
});