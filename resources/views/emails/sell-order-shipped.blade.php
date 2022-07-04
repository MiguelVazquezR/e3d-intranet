<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>OV en camino</title>
</head>

<body style="font-size: 9px;">
    <div>
        Se hizo un envío {{ is_null($packages_shipped) ? '' : 'parcial' }} que incluye los siguientes paquetes: <br><br>
        @if (is_null($packages_shipped))
            @foreach ($sell_order->SellOrderedProducts as $sop)
                @foreach ($sop->shippingPackages as $i => $package)
                    <div>
                        {{ ($i + 1) }}-
                        Dimesiones: {{ $package->large }}x{{ $package->width }}x{{ $package->height }}cm <br>
                        Peso: {{ $package->weight }}kg <br>
                        Contenido: {{ $package->quantity }} unidades <br>
                        <img src="{{ $message->embed(storage_path() . '/app/' . $package->inside_image) }}"
                            style="width:30%; margin-right: 5px;">
                        <img src="{{ $message->embed(storage_path() . '/app/' . $package->outside_image) }}"
                            style="width:30%;">
                    </div>
                @endforeach
            @endforeach
        @else
            @foreach ($packages_shipped as $i => $package)
                <div>
                    {{ ($i + 1) }}-
                    Dimesiones: {{ $package->large }}x{{ $package->width }}x{{ $package->height }}cm <br>
                    Peso: {{ $package->weight }}kg <br>
                    Contenido: {{ $package->quantity }} unidades <br>
                    <img src="{{ $message->embed(storage_path() . '/app/' . $package->inside_image) }}"
                        style="width:30%; margin-right: 5px;">
                    <img src="{{ $message->embed(storage_path() . '/app/' . $package->outside_image) }}"
                        style="width:30%;">
                </div>
            @endforeach
        @endif
    </div> <br>
    <p>
        No responder a este correo. Cualquier duda o aclaración, notificar a
        <b>asistente.director@emblemas3d.com</b> o <b>maribel@emblemas3d.com</b>. <br>
        Gracias, excelente día.
    </p>
</body>

</html>
