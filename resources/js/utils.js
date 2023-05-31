Livewire.on('setCookie', function (tokenId) {
    // Establecer la cookie en el navegador
    document.cookie = 'authorized_device_token=' + tokenId + '; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT';
});
