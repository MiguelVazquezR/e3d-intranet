<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de calidad OV-{{ str_pad($sell_order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="text-xs px-5">
    @foreach ($sell_order->sellOrderedProducts as $sop)
    @if ($sop->productForSell->model_name == App\Models\Product::class)
    @php
    $product = App\Models\Product::find($sop->productForSell->model_id);
    @endphp
    @if ($product->product_family_id == 1)
    <div class="min-h-screen">
        <div class="flex justify-around mb-3 border-b-2">
            <x-jet-application-logo class="h-10" />
            <h1 class="font-bold text-xl text-gray-500">Certificado de calidad</h1>
            <div class="flex flex-col">
                <span> Folio: OV-{{ str_pad($sell_order->id, 4, '0', STR_PAD_LEFT) }} </span>
                <span> Fecha: {{ date('d-m-Y') }} </span>
            </div>
        </div>
        <div class="w-2/3 grid grid-cols-5 gap-x-2 mb-3">
            <b>Cliente:</b> <span class="col-span-4">{{ $sell_order->customer->name }}</span>
            <b>Producto:</b> <span class="col-span-4">{{ $product->name }}</span>
            <b>Cantidad:</b> <span class="col-span-4">{{ $sop->quantity }} {{ $product->unit->name }}</span>
            <b>Factura:</b> <span class="col-span-4">{{ $sell_order->invoice }}</span>
            <b>OC de cliente:</b> <span class="col-span-4">{{ $sell_order->oce_name ?? 'N/A' }}</span>
        </div>
        <div>
            <p class="mb-3">
                Este producto ha sido manufacturado bajo lineamientos de un sistema de gestión de calidad de
                Emblemas 3D
                <b>México S.A. de C.V.</b> y certificado bajo el mismo; cumpliendo con cada una de las
                especificaciones
                requeridas para
                su uso.
            </p>
            <p class="mb-1">
                INDICACIONES DE ALMACENAMIENTO:<br>
            <div class="mb-3">
                <li class="list-disc">Apilar <span class="text-red-500">verticalmente</span> los paquetes
                    en un máximo de cuatro
                    cajas.</li>
                <li class="list-disc">No colocar objetos pesados sobre las plantillas de emblemas.</li>
                <li class="list-disc">
                    1 año de estabilidad en almacén bajo las siguientes condiciones:
                    <div>
                <li class="list-dic ml-4">Temperatura entre 15 y 30 °C</li>
                <li class="list-dic ml-4"> 50% humedad relativa</li>
            </div>
            </li>
        </div>
        </p>
        <p class="mb-1">
            INDICACIONES DE APLICACIÓN: <br>
        <div class="mb-3">
            <li class="list-disc">Limpie y desengrase la superficie donde desea aplicar el emblema.
                Asegúrese que ésta se
                encuentre a una
                temperatura no menor a 10 °C.</li>
            <li class="list-disc text-red-500">Desprenda la pelícdiva plástica del respaldo y maneje siempre
                el emblema por los bordes. No
                toque el adhesivo
                con los dedos o este podría perder fuerza de adherencia.</li>
            <li class="list-disc">Aplique el emblema cuidadosamente sobre la superficie y presione
                sobre toda la pieza,
                liberando el aire
                atrapado. Evite la presión excesiva.</li>
        </div>
        </p>
        <p class="my-10">Vo.Bo.</p>
        <span class="border-t border-black pt-1 px-5">
            Ing. César Gómez - Jefe de producción
        </span>
        <p class="text-center text-gray-400 mt-72 pt-20">
            {{ $organization->address }} CP {{ $organization->post_code }} <br>
            TEL: {{ $organization->phone1 }} | {{ explode('http://',$organization->web_site)[1] }}
        </p>
    </div>
    @else
    <div class="min-h-screen">
        <h1 class="text-center text-xl text-blue-500 pt-14 border-t mt-3">
            Este producto es de la familia de <b>{{ $product->family->name }}</b>.
            El certificado de calidad actualmente sólo está habilitado para <b>Emblemas</b>.
        </h1>
    </div>
    @endif
    @else
    <div class="min-h-screen">
        <h1 class="text-center text-xl text-blue-500 pt-14 border-t mt-3">
            Este producto es compuesto.
            El certificado de calidad actualmente sólo está habilitado para <b>Emblemas</b>.
        </h1>
    </div>
    @endif
    @endforeach
</body>

</html>