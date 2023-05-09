<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de venta {{ $sale_order->id }}</title>
    <!-- <link rel="stylesheet" href="{{ public_path('css/app.css') }}" type="text/css"> -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body class="text-xs">
    <h1 class="text-center font-semibold text-lg">
        OCE: {{ $sale_order->oce_name }} <br>
        Cliente: {{ $sale_order->customer->name }} <br>
        Fecha: {{ $sale_order->created_at->format('Y-m-d') }}
    </h1>
    @foreach ($sale_order->sellOrderedProducts as $ordered_product)
        @php
            if ($ordered_product->productForSell->model_name == 'App\\Models\\Product') {
                $product = App\Models\Product::find($ordered_product->productForSell->model_id);
                if ($product) {
                    $product_name = $product->name;
                } else {
                    $product_name = '??? - simple';
                }
            } else {
                $product = App\Models\CompositProduct::find($ordered_product->productForSell->model_id);
                if ($product) {
                    $product_name = $product->alias;
                } else {
                    $product_name = '??? - compuesto';
                }
            }
        @endphp
        <section>
            <section class="text-gray-600 body-font">
                <div class="container px-2 py-1 mx-auto">
                    <div class="p-2 bg-white flex items-center mx-auto border-b mb-2 border-gray-200 rounded-lg">
                        @if ($product)
                            <div class="w-32 h-32 mr-8 inline-flex items-center justify-center flex-shrink-0">
                                <img src="{{ Storage::url($product->image) }}" />
                            </div>
                        @else
                            <div
                                class="bg-gray-200 rounded-md w-32 h-32 mr-8 inline-flex items-center justify-center flex-shrink-0">
                            </div>
                        @endif
                        <div class="flex-grow text-left mt-1 sm:mt-0">
                            <h1 class="text-black text-lg title-font font-semibold mb-2">{{ $product_name }}</h1>
                            <p class="leading-relaxed text-base text-green-700">
                                Solicitado por: {{ $sale_order->creator->name }},
                            </p>
                            @if ($ordered_product->activityDetails)
                                <p class="leading-relaxed text-sm text-gray-700 mb-1">
                                    @foreach ($ordered_product->activityDetails as $activity)
                                        <span class="text-yellow-700 font-bold">Asignado a:</span>
                                        {{ $activity->operator->name }} | 
                                        <span class="text-yellow-700 font-bold">Indicaciones:</span>
                                        {{ $activity->indications }} | 
                                        <span class="text-yellow-700 font-bold">Tiempo estimado:</span>
                                        {{ $activity->estimated_time }} minutos
                                    @endforeach
                                </p>
                            @else
                                <p class="leading-relaxed text-base text-red-500">
                                    No hay operador(es) asignado(s)
                                </p>
                            @endif
                            <div class="py-2">
                                <div class=" inline-block mr-2">
                                    <div class="flex pr-2 h-full items-center">
                                        <svg class="text-yellow-500 w-6 h-6 mr-1" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" />
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                        <p class="title-font font-medium">Cantidad: {{ $ordered_product->quantity }}
                                            unidad(es)</p>
                                    </div>
                                </div>
                                <div class="inline-block mr-2">
                                    <div class="flex  pr-2 h-full items-center">
                                        <svg class="text-yellow-500 w-6 h-6 mr-1" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" />
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                        <p class="title-font font-medium">Prioridad: {{ $sale_order->priority }}</p>
                                    </div>
                                </div>
                            </div>
                            <p>Notas: {{ $ordered_product->notes ?? '--' }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    @endforeach
</body>

</html>
