@if ($vertical)
    <!-- vertical view -->
    <div class="bg-gray-100 rounded-xl shadow-lg md:mt-2 mt-4">
        <img src="{{ Storage::url($composit_product->image) }}">
        <div class="p-3">
            <h1 class="text-lg font-bold"> {{ $composit_product->alias }} </h1>
            <p class="mt-1 font-semibold text-gray-500">Compuesto por</p>
            @foreach ($composit_product->compositProductDetails as $c_p_d)
                <div class="flex items-center justify-between my-2">
                    <x-product-quick-view :image="$c_p_d->product->image" :name="$c_p_d->product->name">
                        <span class="text-gray-500 ml-1 text-xs"> x{{ $c_p_d->quantity }}
                            {{ $c_p_d->product->unit->name }} </span>
                    </x-product-quick-view>
                    <div x-data="{ open_tooltip: false }" class="flex items-center">
                        @if ($c_p_d->notes)
                            <span @click="open_tooltip = !open_tooltip"
                                class="far fa-comment hover:cursor-pointer relative">
                                <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                    class="absolute w-32 right-full top-0 m-1 cursor-default">
                                    <div class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                        {{ $c_p_d->notes }}
                                    </div>
                                </div>
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
            {{ $slot }}
        </div>
    </div>
@else
    <!-- horizontal view -->
    <div class="flex shadow-lg mx-auto w-full mt-3 bg-gray-100">
        <img class="w-1/4 object-cover rounded-lg rounded-r-none" src="{{ Storage::url($composit_product->image) }}">
        <div class="w-3/4 px-3 py-3 rounded-lg grid grid-cols-2 gap-x-2 text-sm">
            <div class="text-center col-span-2 text-gray-600 font-bold border-b-2">
                {{ $composit_product->alias }}
            </div>
            @foreach ($composit_product->compositProductDetails as $c_p_d)
                <div class="flex items-center justify-between my-2 border-r-2 pr-1">
                    <div class="flex items-center">
                        <a href="{{ Storage::url($c_p_d->product->image) }}" target="_blank"
                            class="mr-1 hover:cursor-pointer">
                            <img class="h-8 w-8 md:h-10 md:w-10 rounded-full"
                                src="{{ Storage::url($c_p_d->product->image) }}">
                        </a>
                        <div>
                            <p>{{ $c_p_d->product->name }}</p>
                        </div>
                        <p class="mt-1 text-gray-500 ml-3 text-xs"> x{{ $c_p_d->quantity }}
                            {{ $c_p_d->product->unit->name }} </p>
                    </div>
                    <div x-data="{ open_tooltip: false }" class="flex items-center">
                        @if ($c_p_d->notes)
                            <span @click="open_tooltip = !open_tooltip"
                                class="far fa-comment hover:cursor-pointer relative">
                                <div @click.away="open_tooltip=false" x-show="open_tooltip" x-transition
                                    class="absolute w-32 right-full top-0 m-1 cursor-default">
                                    <div class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                        {{ $c_p_d->notes }}
                                    </div>
                                </div>
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
            {{ $slot }}
        </div>
    </div>
@endif
