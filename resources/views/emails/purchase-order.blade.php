<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Nueva orden de compra</title>
</head>

<body style="font-size: 9px;">
    <div>
        Orden de compra OC-{{ str_pad($purchase_order->id, 4, '0', STR_PAD_LEFT) }}
        <br><br>
        @php
            $all_products = $purchase_order->purchaseOrderedProducts;
        @endphp
        @foreach ($all_products as $i => $pop)
            <div>
                {{ $i + 1 }}-
                <b>Artículo:</b> {{ $pop->product->name }} x {{ $pop->quantity }} unidades<br>
                <b>Código:</b> {{ $pop->code ?? '--' }}<br>
                <b>Notas:</b> {{ $pop->notes ?? '--' }}<br>
            </div>
        @endforeach
    </div> <br>
    <p>
        Favor de no responder a este correo. Cualquier duda o aclaración, notificar a
        <b>asistente.director@emblemas3d.com</b> o <b>maribel@emblemas3d.com</b>. <br>
        Gracias, excelente día.
    </p>
</body>

</html>
