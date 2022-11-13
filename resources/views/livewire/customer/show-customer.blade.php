<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            <!--tab component start-->
            <ul class="flex justify-center items-center pb-4" x-data="setup()">
                <template x-for="(tab, index) in tabs" :key="index">
                    <li class="cursor-pointer text-sm py-2 px-6 text-gray-500 border-b-2"
                        :class="activeTab === index ? 'text-black border-black dark:text-blue-600 dark:border-blue-600' : ''"
                        @click="activeTab = index; $dispatch('change-tab', index);" x-text="tab"></li>
                </template>
            </ul>
            <!--tab component end-->

            <script>
                function setup() {
                    return {
                        activeTab: 0,
                        tabs: [
                            "Detalles",
                            "Productos",
                        ],
                        activeTab: @entangle('active_tab'), //current tab
                    };
                };
            </script>
        </x-slot>

        <x-slot name="content">
            <div x-data="{ activeTab: @entangle('active_tab') }" @change-tab.window="activeTab = $event.detail">
                <!-- Details -->
                <div x-show="activeTab == 0">
                    <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Datos fiscales</h3>
                    <div class="lg:grid lg:grid-cols-2 lg:gap-2 font-bold">
                        <p>Razón social: <span class="text-gray-500">{{ $company->bussiness_name }}</span></p>
                        <p>RFC: <span class="text-gray-600">{{ $company->rfc }}</span></p>
                        <p>Teléfono: <span class="text-gray-600">{{ $company->phone }}</span></p>
                        <p>Dirección: <span class="text-gray-600">{{ $company->fiscal_address }} -
                                C.P.{{ $company->post_code }}</span></p>
                    </div>

                    <h3 class="text-center text-lg text-sky-800 tracking-widest font-bold my-2">Sucursales</h3>

                    @foreach ($company->customers as $customer)
                        <div x-data="{ dropdownOpen: false }" class="text-sm">
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="block text-center rounded-md p-2 focus:outline-none text-lg text-sky-600">
                                <i class="fas fa-building mr-1"></i> {{ $customer->name }}
                            </button>

                            <div x-show="dropdownOpen"
                                class="lg:grid lg:grid-cols-2 lg:gap-2 shadow-xl border-2 rounded-lg p-2 font-bold"
                                x-collapse.duration.500>
                                <p>Nombre o alias: <span class="text-gray-600">{{ $customer->name }}</span></p>
                                <p>Método de pago: <span
                                        class="text-gray-600">{{ $customer->satMethod->description }}</span></p>
                                <p>Medio de pago: <span
                                        class="text-gray-600">{{ $customer->satWay->description }}</span></p>
                                <p>Uso de factura: <span
                                        class="text-gray-600">{{ $customer->satType->description }}</span></p>
                                <p class="col-span-2">Dirección: <span class="text-gray-600">{{ $customer->address }}
                                        -
                                        C.P.{{ $customer->post_code }}</span></p>
                                <h4 class="text-center text-sm text-sky-500 my-1 col-span-2">Contactos</h4>
                                @foreach ($customer->contacts as $contact)
                                    <div
                                        class="col-span-2 flex flex-col lg:flex-row items-center text-sm mb-1 py-2 mx-6 border-b-2 lg:justify-center">
                                        <div>
                                            <i class="fas fa-user-circle mr-1"></i><span
                                                class="mr-2">{{ $contact->name }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-envelope mr-1"></i><span
                                                class="mr-2">{{ $contact->email }}</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-phone-alt mr-1"></i><span
                                                class="mr-2">{{ $contact->phone }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- products -->
                <div x-show="activeTab == 1" class="text-gray-500">
                    <h2 class="pb-4 border-b-2">
                        Productos registrados
                    </h2>

                    <div x-show="activeTab != 1" class="py-8 text-center">
                        Cargando...
                    </div>

                    <div class="grid-cols-1 md:grid md:grid-cols-2 md:gap-3 mt-2 text-sm">
                        @forelse($company->productsForSell as $product_for_sell)
                            @if ($product_for_sell->model_name == 'App\\Models\\' . Product::class)
                                @php
                                    $product = App\Models\Product::find($product_for_sell->model_id);
                                @endphp
                                <x-simple-product-card :simpleProduct="$product">
                                    <!-- prices and dates -->
                                    <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                        @if ($product_for_sell->old_price)
                                            <p class="mt-1 text-gray-500">Precio anterior: <span
                                                    class="text-green-600">{{ $product_for_sell->old_price . ' ' . $product_for_sell->old_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($product_for_sell->old_date->diffForHumans() == 'hace 6 horas')
                                                        {{ 'hoy a las ' . $product_for_sell->old_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $product_for_sell->old_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                        <p class="mt-1 text-gray-500">Precio actual: <span
                                                class="text-green-600">{{ $product_for_sell->new_price . ' ' . $product_for_sell->new_price_currency }}</span>
                                        </p>
                                        <p class="mt-1 text-gray-500">Establecido:
                                            <span class="text-sky-600">
                                                @if ($product_for_sell->new_date->diffForHumans() == 'hace 6 horas')
                                                    {{ 'hoy a las ' . $product_for_sell->new_date->isoFormat('h:mm a') }}
                                                @else
                                                    {{ $product_for_sell->new_date->diffForHumans() }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </x-simple-product-card>
                            @else
                                @php
                                    $product = App\Models\CompositProduct::find($product_for_sell->model_id);
                                @endphp
                                <x-composit-product-card :compositProduct="$product">
                                    <!-- prices and dates -->
                                    <div class="grid grid-cols-2 gap-2 border-t-2 p-2">
                                        @if ($product_for_sell->old_price)
                                            <p class="mt-1 text-gray-500">Precio anterior: <span
                                                    class="text-green-600">{{ $product_for_sell->old_price . ' ' . $product_for_sell->old_price_currency }}</span>
                                            </p>
                                            <p class="mt-1 text-gray-500">Establecido:
                                                <span class="text-sky-600">
                                                    @if ($product_for_sell->old_date->diffForHumans() == 'hace 6 horas')
                                                        {{ 'hoy a las ' . $product_for_sell->old_date->isoFormat('h:mm a') }}
                                                    @else
                                                        {{ $product_for_sell->old_date->diffForHumans() }}
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                        <p class="mt-1 text-gray-500">Precio actual: <span
                                                class="text-green-600">{{ $product_for_sell->new_price . ' ' . $product_for_sell->new_price_currency }}</span>
                                        </p>
                                        <p class="mt-1 text-gray-500">Establecido:
                                            <span class="text-sky-600">
                                                @if ($product_for_sell->new_date->diffForHumans() == 'hace 6 horas')
                                                    {{ 'hoy a las ' . $product_for_sell->new_date->isoFormat('h:mm a') }}
                                                @else
                                                    {{ $product_for_sell->new_date->diffForHumans() }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </x-composit-product-card>
                            @endif
                        @empty
                            <h2 class="text-center py-10">No hay productos registrados</h2>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
