<?php

require __DIR__.'/vendor/autoload.php'; // Ruta a autoload.php de tu proyecto Laravel
$app = require_once __DIR__.'/bootstrap/app.php'; // Ruta a bootstrap/app.php de tu proyecto Laravel

// Obtener una instancia del kernel de la aplicación
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Ejecuta el comando personalizado que desencadena la ejecución de la función searchForMaintenances()
$kernel->call('cron:search-for-maintenances');
