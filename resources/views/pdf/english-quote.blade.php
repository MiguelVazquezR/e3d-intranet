@php
    $families = ['Emblems', 'ABS plate holder', 'Metalic plate holder', 'Keychains', 'Documents holder', 'Termos', 'Styrene plates', 'Parasol', 'Rough use car mat', 'foamy car mat'];
@endphp

<!DOCTYPE html>
<html lang="en">

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
                San Antonio TX, {{ $quote->created_at->isoFormat('DD/MM/YYYY') }}
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
                Dear {{ $quote->receiver }} - {{ $quote->department }}
            </p>
            <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700">
                By means of this letter receive a cordial greeting and also I provide you with the quote that you
                requested, based on the
                conversation held with you and knowing your conditions of the product to apply:
            </p>
        </div>

        <!-- table -->
        <table class="rounded-t-lg m-2 w-11/12 mx-auto bg-gray-300 text-gray-800" style="font-size: 10.2px;">
            <thead>
                <tr class="text-left border-b-2 border-gray-400">
                    <th class="px-2 py-1">Category</th>
                    <th class="px-2 py-1">Concept</th>
                    <th class="px-2 py-1">Notes</th>
                    <th class="px-2 py-1">$ per unit</th>
                    <th class="px-2 py-1">Units</th>
                    <th class="px-2 py-1">Total without taxes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->quotedProducts as $q_product)
                    <tr class="bg-gray-200 text-gray-700">
                        <td class="px-2 py-px">{{ $families[$q_product->product->product_family_id] }}</td>
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
                        TOTAL without taxes: {{ $quote->total(2, true) . ' ' . $quote->currency->name }}
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
                        <img class="rounded-t-xl max-h-52 mx-auto" src="{{ $aditional_image->getUrl('thumb') }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->product->name }} (Additional
                            images
                            {{ $i + 1 }})</p>
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
                        <img class="rounded-t-xl max-h-52 mx-auto" src="{{ $aditional_image->getUrl('thumb') }}">
                        <p class="py-px px-1 uppercase text-gray-600">{{ $q_product->compositProduct->alias }}
                            (Additional images {{ $i + 1 }})
                        </p>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- goodbyes -->
        <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700">
            Nothing more for the moment and awaiting your preference, I remain at your service for any questions or
            comments. Quotation sheet: <span
                class="font-bold bg-yellow-100">COT-{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }}</span>
        </p>

        <!-- Notes -->
        <div class="w-11/12 mx-auto border border-gray-500 px-3 pb-1 mt-1 rounded-xl text-gray-500 leading-normal uppercase"
            style="font-size: 10.5px;">
            <h2 class="text-center font-extrabold">IMPORTANT <i class="fas fa-exclamation-circle text-amber-500"></i>
            </h2>
            <ol class="list-decimal mx-2 mb-2">
                @if ($quote->notes)
                    <li class="font-bold text-blue-500">{{ $quote->notes }}</li>
                @endif
                <li>PRICES WITHOUT TAXES</li>
                <li>TOOLING COSTS:
                    @if ($quote->strikethrough_tooling_cost)
                        <span class="font-bold text-blue-500 line-through">{{ $quote->tooling_cost }}</span>
                </li>
            @else
                <span class="font-bold text-blue-500">{{ $quote->tooling_cost }}</span></li>
                @endif
                <li>DELIVERY TIME FOR THE FIRST PRODUCTION <span
                        class="font-bold text-blue-500">{{ $quote->first_production_days }}</span>.
                    TIME RUNS ONCE PAYING 100% OF THE TOOLING AND THE 50% OF THE
                    PRODUCTS.</li>
                <li> FREIGHTS AND CARRIAGES ARE PAID BY CUSTOMER: <span
                        class="font-bold text-blue-500">{{ $quote->freight_cost }}</span></li>
                <li> PRICES IN <span class="font-bold text-blue-500">{{ $quote->currency->name }}</span></li>
                <li> QUOTE VALID FOR 21 DAYS. PRODUCT IS SUBJECT TO FINAL DESIGN REVIEW, TESTING AND SUBSEQUENT APPROVAL
                </li>
            </ol>
            PAYMENTS.- BY BANK TRANSFER OR DEPOSIT TO MARIBEL@EMBLEMAS3D.COM O ASISTENTE.DIRECTOR@EMBLEMAS3D.COM CASH
            PAYMENTS ARE NOT ACCEPTED, ALL CHECKS MUST USE THE NAME OF: EMBLEMAS 3D MEXICO SA DE CV. AND STAMP FOR
            PAYMENT IN ACCOUNT OF THE BENEFICIARY
        </div>

        <!-- banks -->
        <div class="grid grid-cols-2 gap-0 text-xs mt-1 font-semibold" style="font-size: 10px;">
            <div class="bg-sky-600 text-white p-1 flex justify-between rounded-l-xl">
                <span>Vantage Bank Texas</span>
                <span>Account number: 107862946</span>
                <span> Bank Phone: 956-664-84000</span>
            </div>
            <div class="bg-red-600 text-white p-1 flex justify-between rounded-r-xl">
                <span>Bank address: San Antonio Texas</span>
                <span>Swift/Aba: ITNBUS44XXX</span>
                <span></span>
            </div>
        </div>

        <!-- Author -->
        <div class="mt-1 text-gray-700 flex justify-around" style="font-size: 11px;">
            <div>
                Created by: {{ $quote->creator->name }} &nbsp;&nbsp;
                Tel: {{ $quote->creator->name }} &nbsp;&nbsp;
                email: {{ $quote->creator->email }} &nbsp;&nbsp;
            </div>
            <div>
                Authorized by:
                @if ($quote->authorized_by)
                    <span class="text-green-600">J. Sherman</span>
                @else
                    <!-- No authorized Banner -->
                    <div class="absolute left-28 top-1/3 text-red-700 text-5xl border-4 border-red-700 p-6">
                        <i class="fas fa-exclamation"></i>
                        <span class="ml-2">NO AUTHORIZED</span>
                    </div>

                    <span class="text-amber-500">No authorized</span>
                @endif
            </div>
        </div>

        <!-- footer -->
        <footer class="text-gray-400 w-11/12 mx-auto mt-3" style="font-size: 11px;">
            <div class="grid grid-cols-3 gap-x-4">
                <div class="text-center text-sm font-bold">
                    Specialists in high quality
                    emblems
                </div>
                <div>
                    <i class="fas fa-map-marker-alt"></i> 5460 Babcock Rd Suite 120-145, San
                    Antonio TX 78240 <br>
                    <i class="fas fa-phone-alt"></i> 210-858-9881
                </div>
                <div>
                    <i class="fas fa-globe"></i> www.emblems<b class="text-sky-600">3</b><b
                        class="text-red-600">d</b>.com <br>
                    <i class="fas fa-envelope"></i> j.sherman@emblems<b class="text-sky-600">3</b><b
                        class="text-red-600">d</b>.com
                </div>
            </div>
            <div class="flex">
                <p class="mt-3 leading-tight mr-1" style="font-size: 10px;">
                    High quality emblems, we are the best manufacturers. Automotive industry, household appliances,
                    electronics, textiles, footwear, furniture and
                    toys. In the electronic division, we are technology developers. Get to know our new custom USB flash
                    drives from the mold, they are exclusive.
                    In the automotive division we are manufacturers specialized in chrome emblems, license plate
                    holders, key rings, document holders, styrene
                    plates. The new, USB KEYCHAIN, original and personalized design, all with a unique mold for your
                    company (total and exclusive
                    customization).
                </p>
                <x-company-shield width="50" />
            </div>
        </footer>

    </div>




</body>

</html>
