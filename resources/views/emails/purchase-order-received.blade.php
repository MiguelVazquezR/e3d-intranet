<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>OC Recibida</title>
</head>

<body style="font-size: 9px;">
    <div>
        Se ha recibido la siguiente mercanía: <br><br>
        @foreach ($purchase_order->purchaseOrderedProducts as $pop)
            <div>
                - {{ $pop->product->name }}, {{ $pop->quantity }} {{ $pop->product->unit->name }}<br>
                <img src="{{ $message->embed(storage_path() . '/app/' . $pop->product->image) }}"
                    style="width:25%;">
            </div>
        @endforeach
    </div><br><br>
    Esta orden fue emitida al proveedor el {{ $purchase_order->emitted_at->isoFormat('dddd, DD MMMM') }}
</body>

</html>
