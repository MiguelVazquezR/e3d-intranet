<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COT-{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }}</title>
    <!-- <link rel="stylesheet" href="{{ public_path('css/app.css') }}" type="text/css"> -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="relative">

    <!-- logo -->
    <div class="text-center">
        <x-jet-application-logo class="h-auto w-3/12 inline-block" />
    </div>

    <!-- content -->
    <div class="text-xs">

        <!-- header -->
        <div>
            <p class="flex justify-end ml-auto font-bold mr-6 text-xs text-gray-700">
                Guadalajara, Jalisco {{ $quote->created_at->isoFormat('DD MMMM YYYY') }}
            </p>
            @if ($quote->customer)
                <p class="w-11/12 text-lg mx-auto font-bold text-gray-700">
                    {{ $quote->customer->name }}
                </p>
            @else
                <p class="w-11/12 text-lg mx-auto font-bold text-gray-700">
                    {{ $quote->customer_name }}
                </p>
            @endif
            <p class="w-11/12 mx-auto font-bold mt-2 text-gray-700">
                Estimado(a) {{ $quote->receiver }} - {{ $quote->department }}
            </p>
            <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700">
                Por medio de la presente reciba un cordial saludo y a su vez le proporciono la cotización que nos
                solicitó,
                con base en la plática sostenida con ustedes y sabiendo de sus condiciones del producto a aplicar:
            </p>
        </div>

        <!-- table -->
        <table class="rounded-t-lg m-2 w-11/12 mx-auto bg-gray-300 text-gray-800" style="font-size: 10.2px;">
            <thead>
                <tr class="text-left border-b-2 border-gray-400">
                    <th class="px-2 py-1">Tipo</th>
                    <th class="px-2 py-1">Concepto</th>
                    <th class="px-2 py-1">Notas</th>
                    <th class="px-2 py-1">$ unitario</th>
                    <th class="px-2 py-1">Unidades</th>
                    <th class="px-2 py-1">Total sin IVA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->quotedProducts as $q_product)
                    <tr class="bg-gray-200 text-gray-700">
                        <td class="px-2 py-px">{{ $q_product->product->family->name }}</td>
                        <td class="px-2 py-px">{{ $q_product->product->name }}</td>
                        <td class="px-2 py-px">
                            @if ($q_product->notes)
                                {{ $q_product->notes }}
                            @else
                                --
                            @endif
                        </td>
                        <td class="px-2 py-px">{{ number_format($q_product->price, 2) . ' ' . $quote->currency->name }}
                        </td>
                        <td class="px-2 py-px">{{ $q_product->quantity . ' ' . $q_product->product->unit->name }}</td>
                        <td class="px-2 py-px">{{ $q_product->total(2, true) . ' ' . $quote->currency->name }}</td>
                    </tr>
                @endforeach
                @foreach ($quote->quotedCompositProducts as $q_product)
                    <tr class="bg-gray-200 text-gray-700">
                        <td class="px-2 py-px">{{ $q_product->compositProduct->family->name }}</td>
                        <td class="px-2 py-px">{{ $q_product->compositProduct->alias }}</td>
                        <td class="px-2 py-px">
                            @if ($q_product->notes)
                                {{ $q_product->notes }}
                            @else
                                --
                            @endif
                        </td>
                        <td class="px-2 py-px">{{ number_format($q_product->price, 2) . ' ' . $quote->currency->name }}
                        </td>
                        <td class="px-2 py-px">{{ $q_product->quantity . ' Piezas' }}</td>
                        <td class="px-2 py-px">{{ $q_product->total(2, true) . ' ' . $quote->currency->name }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center border-t-2 border-gray-400 py-1 font-bold" colspan="6">
                        TOTAL SIN IVA: {{ $quote->total(2, true) . ' ' . $quote->currency->name }}
                    </td>
                </tr>
            </tfoot>

        </table>

        <!-- Images -->
        <div class="w-11/12 mx-auto my-3 grid grid-cols-5 gap-4 ">
             {{-- simple products --}}
            @foreach ($quote->quotedProducts as $q_product)
                @if ($q_product->show_image)
                    <div class="bg-gray-200 rounded-t-xl rounded-b-md border" style="font-size: 8px;">
                        <img class="rounded-t-xl max-h-52 mx-auto"
                            src="{{ Storage::url($q_product->product->image) }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->product->name }}</p>
                    </div>
                @endif
                {{-- aditional images --}}
                @foreach ($q_product->getMedia() as $i => $aditional_image)
                    <div class="bg-gray-200 rounded-t-xl rounded-b-md" style="font-size: 8px;">
                        <img class="rounded-t-xl max-h-52 mx-auto"
                            src="{{ $aditional_image->getUrl('thumb') }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->product->name }} (Imagen adicional {{($i+1)}})</p>
                    </div>
                @endforeach
            @endforeach
            {{-- composite products --}}
            @foreach ($quote->quotedCompositProducts as $q_product)
                @if ($q_product->show_image)
                    <div class="bg-gray-200 rounded-t-xl rounded-b-md" style="font-size: 8px;">
                        <img class="rounded-t-xl max-h-52 mx-auto"
                            src="{{ Storage::url($q_product->compositProduct->image) }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->compositProduct->alias }}</p>
                    </div>
                @endif
                {{-- aditional images --}}
                @foreach ($q_product->getMedia() as $i => $aditional_image)
                    <div class="bg-gray-200 rounded-t-xl rounded-b-md" style="font-size: 8px;">
                        <img class="rounded-t-xl max-h-52 mx-auto"
                            src="{{ $aditional_image->getUrl('thumb') }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->compositProduct->alias }} (Imagen adicional {{($i+1)}})</p>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- goodbyes -->
        <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700">
            Sin más por el momento y en espera de su preferencia,
            quedo a sus órdenes para cualquier duda o comentario.
            Folio de cotización: <span
                class="font-bold bg-yellow-100">COT-{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }}</span>
        </p>

        <!-- Notes -->
        <div class="w-11/12 mx-auto border border-gray-500 px-3 pb-1 mt-1 rounded-xl text-gray-500 leading-normal uppercase"
            style="font-size: 10.5px;">
            <h2 class="text-center font-extrabold">IMPORTANTE <i class="fas fa-exclamation-circle text-amber-500"></i>
            </h2>
            <ol class="list-decimal mx-2 mb-2">
                @if ($quote->notes)
                    <li class="font-bold text-blue-500">{{ $quote->notes }}</li>
                @endif
                <li>PRECIOS ANTES DE IVA</li>
                <li>COSTO DE HERRAMENTAL:
                    @if ($quote->strikethrough_tooling_cost)
                        <span class="font-bold text-blue-500 line-through">{{ $quote->tooling_cost }}</span>
                </li>
            @else
                <span class="font-bold text-blue-500">{{ $quote->tooling_cost }}</span></li>
                @endif
                <li>TIEMPO DE ENTREGA PARA LA PRIMER PRODUCCIÓN <span
                        class="font-bold text-blue-500">{{ $quote->first_production_days }}</span>.
                    EL TIEMPO CORRE UNA VEZ PAGANDO EL 100% DEL HERRAMENTAL Y EL 50% DE LOS PRODUCTOS.</li>
                <li>FLETES Y ACARREOS CORREN POR CUENTA DEL CLIENTE: <span
                        class="font-bold text-blue-500">{{ $quote->freight_cost }}</span></li>
                <li>PRECIOS EN <span class="font-bold text-blue-500">{{ $quote->currency->name }}</span></li>
                <li>COTIZACIÓN VÁLIDA POR 21 DÍAS. EL PRODUCTO ESTÁ SUJETO A LA REVISIÓN DEL DISEÑO FINAL, PRUEBAS Y
                    SUBSECUENTE APROBACIÓN</li>
            </ol>
            PAGOS.- POR TRANSFERENCIA BANCARIA O DEPÓSITO ENVIARSE A MARIBEL@EMBLEMAS3D.COM O
            ASISTENTE.DIRECTOR@EMBLEMAS3D.COM <br>
            NO SE ACEPTAN PAGOS EN EFECTIVO, TODOS LOS CHEQUES DEBEN USAR NOMBRE DE: EMBLEMAS 3D
            MEXICO SA DE CV. Y SELLO PARA ABONO EN CUENTA DEL BENEFICIARIO
        </div>

        <!-- banks -->
        <div class="grid grid-cols-2 gap-0 text-xs mt-1 font-semibold" style="font-size: 10px;">
            <div class="bg-sky-600 text-white p-1 flex justify-between rounded-l-xl">
                <span>BANORTE M.N.</span>
                <span>CUENTA:  1180403344</span>
                <span>CLABE: 072 320 011804033446</span>
            </div>
            <div class="bg-red-600 text-white p-1 flex justify-between rounded-r-xl">
                <span>BANORTE USD</span>
                <span>CUENTA: 1181103856</span>
                <span>CLABE: 072 320 011811038560</span>
            </div>
        </div>

        <!-- Author -->
        <div class="mt-1 text-gray-700 flex justify-around" style="font-size: 11px;">
            <div>
                Creado por: {{ $quote->creator->name }} &nbsp;&nbsp;
                Tel: {{ $quote->creator->name }} &nbsp;&nbsp;
                correo: {{ $quote->creator->email }} &nbsp;&nbsp;
            </div>
            <div>
                Autorizado por:
                @if ($quote->authorized_by)
                    <span class="text-green-600">{{ $quote->authorized_by->name }}</span>
                @else
                    <!-- No authorized Banner -->
                    <div class="absolute left-28 top-1/3 text-red-700 text-5xl border-4 border-red-700 p-6">
                        <i class="fas fa-exclamation"></i>
                        <span class="ml-2">SIN AUTORIZACIÓN</span>
                    </div>

                    <span class="text-amber-500">Sin autorización</span>
                @endif
            </div>
        </div>

        <!-- footer -->
        <footer class="text-gray-400 w-11/12 mx-auto mt-3" style="font-size: 11px;">
            <div class="grid grid-cols-3 gap-x-4">
                <div class="text-center text-sm font-bold">
                    Especialistas en
                    Emblemas de alta calidad
                </div>
                <div>
                    <i class="fas fa-mobile-alt"></i> 333 46 46 485 <br>
                    <i class="fas fa-phone-alt"></i> (33) 38 33 82 09
                </div>
                <div>
                    <i class="fas fa-globe"></i> www.emblemas<b class="text-sky-600">3</b><b
                        class="text-red-600">d</b>.com <br>
                    <i class="fas fa-envelope"></i> j.sherman@emblemas<b class="text-sky-600">3</b><b
                        class="text-red-600">d</b>.com
                </div>
            </div>
            <div class="flex">
                <p class="mt-3 leading-tight mr-1" style="font-size: 10px;">
                    Emblemas de alta calidad, somos los mejores fabricantes. Ramo automotríz,
                    electrodomésticos, electrónica, textíl, calzado, muebles y juguetes.
                    En división electrónica, somos desarrolladores de tecnología. Conoce
                    nuestras nuevas memorias USB personalizadas desde el molde, son exclusivos.
                    En división automotríz somos fabricantes especialistas en emblemas cromados,
                    porta placas, llaveros, porta documentos, placas de estireno. Lo nuevo,
                    LLAVERO USB, diseño original y personalizado, todo con molde único para tu
                    empresa (personalización total y exclusiva).
                </p>
                <x-company-shield width="50" />
            </div>
        </footer>

    </div>




</body>

</html>
