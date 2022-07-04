<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Document</title>
</head>

<body>
    <h1>Pantilla para ordenes de compra</h1>
    <div class="bg-gray-200">
        Prueba de envio de correos automaticos: <br>
        <div class="grid grid-cols-2 gap-x-2">
            <b>Proveedor:</b> {{ $purchase_order->supplier->name }}
            <b>contacto:</b> {{ $purchase_order->contact->name }} - {{ $purchase_order->contact->email }}
        </div>
        <i class="fas fa-building mr-2"></i>
    </div>
</body>

</html>
