<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de compra OC-{{ str_pad($purchase_order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="text-xs px-5">
    <!-- header -->
    <div>
        <div class="flex justify-around mb-3 border-b-2">
            <x-jet-application-logo class="h-10" />
            <h1 class="font-bold text-xl text-gray-500">Orden de compra</h1>
            <div class="flex flex-col">
                <span> Folio: OC-{{ str_pad($purchase_order->id, 4, "0", STR_PAD_LEFT) }} </span>
                <span> Fecha: {{ date('d-m-Y') }} </span>
            </div>
        </div>
    </div>

    <!-- receipt -->
    <div class="mb-3 text-center w-full mx-auto p-2 bg-gray-100 rounded-md text-gray-500">
        <h1 class="mb-1 text-lg font-semibold border-b">Información fiscal</h1>
        <div>
            {{ $organization->bussiness_name }} <br>
            {{ $organization->rfc }} <br>
            {{ $organization->phone1 }} <br>
            maribel@emblemas3d.com
        </div>
    </div>

    <!-- products table -->
    <table class="rounded-t-lg m-2 w-full mx-auto bg-gray-300 text-gray-800" style="font-size: 10.2px;">
        <thead>
            <tr class="text-left border-b-2 border-gray-400">
                <th class="px-2 py-1">Artículo</th>
                <th class="px-2 py-1">Cantidad</th>
                <th class="px-2 py-1">Código</th>
                <th class="px-2 py-1">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase_order->purchaseOrderedProducts as $pop)
            <tr class="bg-gray-200 text-gray-700">
                <td class="px-2 py-px">{{ $pop->product->name }}</td>
                <td class="px-2 py-px">{{ number_format($pop->quantity,2) }}</td>
                <td class="px-2 py-px">{{ $pop->code ?? '-' }}</td>
                <td class="px-2 py-px">{{ $pop->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>