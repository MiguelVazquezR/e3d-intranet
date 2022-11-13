@if ($vertical)
    <!-- vertical view -->
    <div class="bg-gray-100 rounded-xl shadow-lg md:mt-2 mt-4 dark:bg-slate-800">
        <img src="{{ Storage::url($simple_product->image) }}">
        <div class="p-3">
            <h1 class="text-lg font-bold"> {{ $simple_product->name }} </h1>
            <p class="mt-1 font-semibold text-gray-500">Detalles</p>

            <div class="grid grid-cols-2 gap-3 my-2">
                <b>Familia</b> <span> {{ $simple_product->family->name }} </span>
                <b>Material</b> <span> {{ $simple_product->material->name }} </span>
            </div>
            {{ $slot }}
        </div>
    </div>
@else
    <!-- horizontal view -->
    <div class="flex shadow-lg mx-auto w-full mt-3 bg-gray-100 dark:bg-slate-800">
        <img class="w-1/4 object-cover rounded-lg rounded-r-none" src="{{ Storage::url($simple_product->image) }}">
        <div class="w-3/4 px-3 py-3 rounded-lg text-sm">
            <div class="text-center text-gray-600 dark:text-gray-400 font-bold border-b-2">
                {{ $simple_product->name }}
            </div>
            <div class="grid grid-cols-2 gap-3 my-2">
                <b>Familia</b> <span> {{ $simple_product->family->name }} </span>
                <b>Material</b> <span> {{ $simple_product->material->name }} </span>
                @if ($simple_product->product_status_id == 1)
                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                        <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                        <span class="relative">{{ $simple_product->status->name }}</span>
                    </span>
                @elseif($simple_product->product_status_id == 2)
                    <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                        <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                        <span class="relative">{{ $simple_product->status->name }}</span>
                    </span>
                @elseif($simple_product->product_status_id == 3)
                    <span class="relative inline-block px-3 py-1 font-semibold text-amber-800 leading-tight">
                        <span aria-hidden class="absolute inset-0 bg-amber-200 dark:bg-amber-300 opacity-50 rounded-full"></span>
                        <span class="relative">{{ $simple_product->status->name }}</span>
                    </span>
                @endif
            </div>
            {{ $slot }}
        </div>
    </div>
@endif
