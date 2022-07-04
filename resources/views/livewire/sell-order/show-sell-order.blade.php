<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            <!--tab component start-->
            <ul class="flex justify-center items-center pb-4" x-data="setup()">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2"
                        :class="activeTab === index ? 'text-black border-black' : ''"
                        @click="activeTab = index; $dispatch('change-tab', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->

            <script>
                function setup() {
                    return {
                        activeTab: 0,
                        tabs: [
                            "Datos de orden",
                            "Productos",
                        ],
                        activeTab: @entangle('active_tab'), //current tab
                    };
                };
            </script>
        </x-slot>

        <x-slot name="content">
            @if ($sell_order->id)
                <div x-data="{ activeTab: @entangle('active_tab') }" @change-tab.window="activeTab = $event.detail">
                    <!-- Details -->
                    <div x-show="activeTab == 0">
                        <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Logística</h3>
                        <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                            <p>Paquetería: <span class="text-gray-500">{{ $sell_order->shipping_company }}</span></p>
                            <p>Costo logística: <span class="text-gray-500">{{ $sell_order->freight_cost }}</span></p>
                            <p>Guía: <span class="text-gray-500">{{ $sell_order->tracking_guide }}</span></p>
                            @if ($sell_order->received_at)
                                <p>fecha de recibido: <span
                                        class="text-gray-500">{{ $sell_order->received_at }}</span></p>
                            @endif
                        </div>
                        @if ($sell_order->customer)
                            <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Datos del clinte
                            </h3>
                            <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                                <p>Razón social: <span
                                        class="text-gray-500">{{ $sell_order->customer->company->bussiness_name }}</span>
                                </p>
                                <p>RFC: <span class="text-gray-500">{{ $sell_order->customer->company->rfc }}</span>
                                </p>
                                <p>Método de pago: <span
                                        class="text-gray-500">{{ $sell_order->customer->satMethod->key }} -
                                        {{ $sell_order->customer->satMethod->description }}</span></p>
                                <p>Medio de pago: <span
                                        class="text-gray-500">{{ $sell_order->customer->satWay->key }} -
                                        {{ $sell_order->customer->satWay->description }}</span></p>
                                <p>Uso de factura: <span
                                        class="text-gray-500">{{ $sell_order->customer->satType->key }} -
                                        {{ $sell_order->customer->satType->description }}</span></p>
                                <p>Cliente (sucursal): <span
                                        class="text-gray-500">{{ $sell_order->customer->name }}</span></p>
                                <p class="col-span-2">Dirección de entrega: <span
                                        class="text-gray-500">{{ $sell_order->customer->address }} -
                                        C.P.{{ $sell_order->customer->post_code }}</span></p>
                                <p>Contacto:</p>
                                <div
                                    class="col-span-2 flex flex-col lg:flex-row items-center text-sm mb-1 lg:justify-center">
                                    <div>
                                        <i class="fas fa-user-circle mr-1"></i><span
                                            class="mr-2">{{ $sell_order->contact->name }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-envelope mr-1"></i><span
                                            class="mr-2">{{ $sell_order->contact->email }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-phone-alt mr-1"></i><span
                                            class="mr-2">{{ $sell_order->contact->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Datos de la orden
                        </h3>
                        <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                            <p>Solicitada por: <span class="text-gray-500">{{ $sell_order->creator->name }}</span>
                            </p>
                            <p>Solicitada el: <span
                                    class="text-gray-500">{{ $sell_order->created_at->isoFormat('DD MMMM YYYY') }}</span>
                            </p>
                            <p>Prioridad: <span class="text-gray-500">{{ $sell_order->priority }}</span></p>
                            <p>Medio de petición de la orden: <span
                                    class="text-gray-500">{{ $sell_order->order_via }}</span></p>
                            <p>OCE: <a href="{{ Storage::url($sell_order->oce) }}" target="_blank"
                                    class="text-blue-400 hover:underline">{{ $sell_order->oce_name }}</a></p>
                            <p>Factura: <span class="text-gray-500">{{ $sell_order->invoice }}</span></p>
                            <p>Status: <span class="text-gray-500">{{ $sell_order->status }}</span></p>
                            <p>Notas: <span class="text-gray-500">{{ $sell_order->notes }}</span></p>
                        </div>
                    </div>

                    <!-- Products -->
                    <div x-show="activeTab == 1">
                        <h2 class="text-lg text-gray-800"> {{ $sell_order->sellOrderedProducts->count() }} productos
                        </h2>
                        <div class="grid-cols-1 md:grid md:grid-cols-2 md:gap-3 mt-2 text-sm">
                            @foreach ($sell_order->sellOrderedProducts as $s_o_p)
                                @if ($s_o_p->productForSell->model_name == 'App\\Models\\' . Product::class)
                                    @php
                                        $product = App\Models\Product::find($s_o_p->productForSell->model_id);
                                    @endphp
                                    <x-simple-product-card :simpleProduct="$product">
                                        {{-- operators --}}
                                        <div class="mt-1 text-gray-700 border-t-2 pt-2">Operadores asignados:
                                            <ul>
                                                @forelse($s_o_p->activityDetails as $activity_details)
                                                    <li class="text-gray-500 flex justify-between items-center">
                                                        - {{ $activity_details->operator->name }}
                                                        <div x-data="{ open_tooltip: false }" class="flex items-center">
                                                            <span @click="open_tooltip = !open_tooltip"
                                                                class="fas fa-tasks hover:cursor-pointer relative">
                                                                <div @click.away="open_tooltip=false"
                                                                    x-show="open_tooltip" x-transition
                                                                    class="absolute w-60 z-10 right-full top-0 m-1 cursor-default">
                                                                    <div
                                                                        class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                                                        <span class="text-yellow-400">Indicaciones:</span>
                                                                        {{ mb_strtolower($activity_details->indications) }} <br><br>
                                                                        <span class="text-yellow-400">tiempo estimado:</span>
                                                                        {{ $activity_details->estimated_time }}
                                                                        minutos <br><br>
                                                                        <span class="text-yellow-400">Status:</span>
                                                                        {{ $activity_details->status() }}
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <span class="text-red-600">No hay operadores asignados</span>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <!-- prices and dates -->
                                        <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                            @if ($s_o_p->productForSell->old_price)
                                                <p class="mt-1 text-gray-500">Precio anterior: <span
                                                        class="text-green-600">{{ $s_o_p->productForSell->old_price . ' ' . $s_o_p->productForSell->old_price_currency }}</span>
                                                </p>
                                                <p class="mt-1 text-gray-500">Establecido:
                                                    <span class="text-sky-600">
                                                        @if ($s_o_p->productForSell->old_date->diffForHumans() == 'hace 12 horas')
                                                            {{ 'hoy a las ' . $s_o_p->productForSell->old_date->isoFormat('h:mm a') }}
                                                        @else
                                                            {{ $s_o_p->productForSell->old_date->diffForHumans() }}
                                                        @endif
                                                    </span>
                                                </p>
                                            @endif
                                            <p class="mt-1 text-gray-500">Precio actual: <span
                                                    class="text-green-600">{{ $s_o_p->productForSell->new_price . ' ' . $s_o_p->productForSell->new_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($s_o_p->productForSell->new_date->diffForHumans() == 'hace 12 horas')
                                                        {{ 'hoy a las ' . $s_o_p->productForSell->new_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $s_o_p->productForSell->new_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        </div>

                                        <div class="flex justify-between mt-1 text-gray-700">
                                            <span>{{ $s_o_p->quantity }} unidades ordenadas</span>
                                            <span
                                                class="text-xs p-1 bg-blue-100 rounded-full">{{ $s_o_p->status }}</span>
                                        </div>
                                    </x-simple-product-card>
                                @else
                                    @php
                                        $product = App\Models\CompositProduct::find($s_o_p->productForSell->model_id);
                                    @endphp
                                    <x-composit-product-card :compositProduct="$product">
                                        {{-- operators --}}
                                        <div class="mt-1 text-gray-700 border-t-2 pt-2">Operadores asignados:
                                            <ul>
                                                @forelse($s_o_p->activityDetails as $activity_details)
                                                    <li class="text-gray-500 flex justify-between items-center">
                                                        - {{ $activity_details->operator->name }}
                                                        <div x-data="{ open_tooltip: false }" class="flex items-center">
                                                            <span @click="open_tooltip = !open_tooltip"
                                                                class="fas fa-tasks hover:cursor-pointer relative">
                                                                <div @click.away="open_tooltip=false"
                                                                    x-show="open_tooltip" x-transition
                                                                    class="absolute w-60 z-10 right-full top-0 m-1 cursor-default">
                                                                    <div
                                                                        class="bg-black text-white text-xs rounded py-1 px-2 font-sans">
                                                                        <span class="text-yellow-400">Indicaciones:</span>
                                                                        {{ mb_strtolower($activity_details->indications) }} <br><br>
                                                                        <span class="text-yellow-400">tiempo estimado:</span>
                                                                        {{ $activity_details->estimated_time }}
                                                                        minutos <br><br>
                                                                        <span class="text-yellow-400">Status:</span>
                                                                        {{ $activity_details->status() }}
                                                                    </div>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <span class="text-red-600">No hay operadores asignados</span>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <!-- prices and dates -->
                                        <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                            @if ($s_o_p->productForSell->old_price)
                                                <p class="mt-1 text-gray-500">Precio anterior: <span
                                                        class="text-green-600">{{ $s_o_p->productForSell->old_price . ' ' . $s_o_p->productForSell->old_price_currency }}</span>
                                                </p>
                                                <p class="mt-1 text-gray-500">Establecido:
                                                    <span class="text-sky-600">
                                                        @if ($s_o_p->productForSell->old_date->diffForHumans() == 'hace 12 horas')
                                                            {{ 'hoy a las ' . $s_o_p->productForSell->old_date->isoFormat('h:mm a') }}
                                                        @else
                                                            {{ $s_o_p->productForSell->old_date->diffForHumans() }}
                                                        @endif
                                                    </span>
                                                </p>
                                            @endif
                                            <p class="mt-1 text-gray-500">Precio actual: <span
                                                    class="text-green-600">{{ $s_o_p->productForSell->new_price . ' ' . $s_o_p->productForSell->new_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($s_o_p->productForSell->new_date->diffForHumans() == 'hace 12 horas')
                                                        {{ 'hoy a las ' . $s_o_p->productForSell->new_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $s_o_p->productForSell->new_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                        <div class="flex justify-between mt-1 text-gray-700">
                                            <span>{{ $s_o_p->quantity }} unidades ordenadas</span>
                                            <span
                                                class="text-xs p-1 bg-blue-100 rounded-full">{{ $s_o_p->status }}</span>
                                        </div>
                                    </x-composit-product-card>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
