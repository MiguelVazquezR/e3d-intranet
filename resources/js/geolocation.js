var options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

function error(err) {
    Livewire.emit('error', 'ERROR(' + err.code + '): ' + err.message);
}

function analizePosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    if (between(latitude, 20.72009, 20.72329) && between(longitude, -103.38620, -103.37991)) {
        Livewire.emitTo('pay-roll-register.create-pay-roll-register', 'registerTime');
    } else {
        Livewire.emit('error', 'Localización no válida para registrar hora (' + latitude + ', ' + longitude + ')');
    }
}

Livewire.on('getLocation', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(analizePosition, error, options);
    } else {
        Livewire.emit('error', 'Geolocación no soportada por navegador, inténtelo en uno diferentes');
    }
});

function between(x, min, max) {
    return x >= min && x <= max;
}
