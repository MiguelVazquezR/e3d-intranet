<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de venta OV-{{ str_pad($sell_order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="text-xs px-5">
    <!-- header -->
    <div>
        <div class="flex justify-around mb-3 border-b-2">
            <x-jet-application-logo class="h-10" />
            <h1 class="font-bold text-xl text-gray-500">Orden de venta</h1>
            <div class="flex flex-col">
                <span> OC de cliente: {{ $sell_order->oce_name ?? 'N/A' }} </span>
                <span> Folio: OV-{{ str_pad($sell_order->id, 4, "0", STR_PAD_LEFT) }} </span>
                <span> Fecha: {{ date('d-m-Y') }} </span>
            </div>
        </div>
    </div>

    <!-- receipt -->
    <div class="mb-3 text-center w-full mx-auto p-2 bg-gray-100 rounded-md text-gray-500">
        <h1 class="mb-1 text-lg font-semibold border-b">Recepción de mercancía</h1>
        <div>
            <p><i class="fas fa-map-marked-alt font-bold"></i> {{ $sell_order->customer->address }} - C.P.{{ $sell_order->customer->post_code }}</p>
            <p><i class="fas fa-phone-alt font-bold"></i> {{ $sell_order->contact->phone }}</p>
            <p><i class="fas fa-user-circle font-bold"></i> {{ $sell_order->contact->name }}</p>
        </div>
    </div>

    <!-- products table -->
    <table class="rounded-t-lg m-2 w-full mx-auto bg-gray-300 text-gray-800" style="font-size: 10.2px;">
        <thead>
            <tr class="text-left border-b-2 border-gray-400">
                <th class="px-2 py-1">Artículo</th>
                <th class="px-2 py-1">Cantidad</th>
                <th class="px-2 py-1">Precio unitario</th>
                <th class="px-2 py-1">Precio sin IVA</th>
            </tr>
        </thead>
        <tbody>
            @php
            $sub_total = 0;
            @endphp
            @foreach ($sell_order->sellOrderedProducts as $sop)
            @php
            if($sop->productForSell->model_name == App\Models\Product::class) {
            $product = App\Models\Product::find($sop->productForSell->model_id);
            $name = $product->name;
            }
            else {
            $product = App\Models\CompositProduct::find($sop->productForSell->model_id);
            $name = $product->alias;
            }
            $currency = $sop->productForSell->new_price_currency;
            $total_products_cost = $sop->quantity * $sop->productForSell->new_price;
            $sub_total += $total_products_cost;
            @endphp
            <tr class="bg-gray-200 text-gray-700">
                <td class="px-2 py-px">{{ $name }}</td>
                <td class="px-2 py-px">{{ $sop->quantity }} unidades</td>
                <td class="px-2 py-px">{{ number_format($sop->productForSell->new_price, 2) . ' ' . $currency }}</td>
                <td class="px-2 py-px">{{ number_format($total_products_cost, 2) . ' ' . $currency }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- details -->
    <div class="w-full mx-auto">
        <div class="mb-3 w-1/3 ml-auto p-2 bg-gray-100 rounded-md text-gray-500 grid grid-cols-2 gap-x-5">
            <span>LOGÍSTICA</span>
            <span class="text-right">{{ number_format($sell_order->freightCost(), 2) }} {{ $currency }}</span>
            <span>SUBTOTAL</span>
            <span class="text-right">{{ number_format($sub_total, 2) }} {{ $currency }}</span>
            @php
            $iva = ($sub_total + $sell_order->freightCost()) * 0.16;
            $total = $sub_total + $sell_order->freightCost() + $iva;
            @endphp
            <span>IVA</span>
            <span class="text-right">{{ number_format($iva, 2) }} {{ $currency }}</span>
            <span class="font-bold">TOTAL</span>
            <span class="text-right font-bold">{{ number_format($total, 2) }} {{ $currency }}</span>
        </div>
    </div>

    <!-- data table -->
    <div class="mb-3 p-2 bg-gray-100 rounded-md text-gray-500">
        <h1 class="mb-1 text-lg font-semibold text-center border-b">Datos fiscales</h1>
        <div class="grid grid-cols-2">
            <span>Razón social</span> <span>{{ $sell_order->customer->company->bussiness_name }}</span>
            <span>RFC</span> <span>{{ $sell_order->customer->company->rfc }}</span>
            <span>Dirección fiscal</span> <span>{{ $sell_order->customer->company->fiscal_address }}</span>
            <span>Código Postal</span> <span>{{ $sell_order->customer->company->post_code }}</span>
            <span>Correo de contacto de compras</span> <span>{{ $sell_order->contact->email }}</span>
            <span>Correo de contacto de pagos</span> <span> -- </span>
            <span>Método de pago</span> <span>{{ $sell_order->customer->satMethod->key }} - {{ $sell_order->customer->satMethod->description }}</span>
            <span>Medio de pago</span> <span>{{ $sell_order->customer->satWay->key }} - {{ $sell_order->customer->satWay->description }}</span>
            <span>Uso de CFDI</span> <span>{{ $sell_order->customer->satType->key }} - {{ $sell_order->customer->satType->description }}</span>
            <span>Teléfono</span> <span>{{ $sell_order->contact->phone }}</span>
        </div>
    </div>

    <!-- sign -->
    <div class="text-center text-gray-500 mt-12">
        <span class="px-12 border-t border-gray-500 text-md">Nombre y firma</span>
    </div>

</body>

</html>