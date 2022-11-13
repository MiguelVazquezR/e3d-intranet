<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_ordenes_venta')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva orden de venta
        </x-slot>

        <x-slot name="content">
            <!-- Details -->
            <div>
                @livewire('customer.search-customer')
                @if ($customer)
                    <x-customer-card :customer="$customer" />
                    <div class="flex flex-col">
                        <x-jet-label value="Contacto" class="mt-3 dark:text-gray-400" />
                        @foreach ($customer->contacts as $contact)
                            <label class="flex items-center radio cursor-pointer dark:text-gray-400">
                                <input wire:model="contact_id" value="{{ $contact->id }}" type="radio"
                                    name="for" />
                                <div class="px-2">
                                    <div
                                        class="flex flex-col lg:flex-row items-center text-sm mb-1 py-2 mx-6 border-b-2 lg:justify-center">
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
                                </div>
                            </label>
                        @endforeach
                        <x-jet-input-error for="contact_id" class="text-xs" />
                    </div>
                @endif
                <x-jet-input-error for="customer" class="mt-2" />
            </div>
            <h2 class="text-center font-bold text-lg text-sky-600 mt-2">Logística</h2>
            <div class="lg:grid lg:grid-cols-3 lg:gap-3">
                <div>
                    <x-jet-label value="Paquetería" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="shipping_company" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="shipping_company" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Costo logística" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="freight_cost" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="freight_cost" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="freight_currency" :options="$currencies" id="name" default="No colocar moneda" />
                </div>
                <div>
                    <x-jet-label value="Guía" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="tracking_guide" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="tracking_guide" class="text-xs" />
                </div>
            </div>

            <!-- order data -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3">Datos de la orden</h2>
            <div class="lg:grid lg:grid-cols-3 lg:gap-3">
                <div>
                    <x-jet-label value="Prioridad" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full input" wire:model.defer="priority">
                        <option value="Normal" selected>Normal</option>
                        <option value="Urgente">Urgente</option>
                        <option value="Especial">Especial</option>
                    </select>
                </div>
                <div>
                    <x-jet-label value="Medio de petición" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="order_via" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="order_via" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Factura" class="mt-3" />
                    <x-jet-input wire:model.defer="invoice" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="invoice" class="text-xs" />
                </div>
            </div>
            <div>
                <x-jet-label value="Nombre/folio OCE" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="oce_name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="oce_name" class="text-xs" />
            </div>
            <div>
                <x-jet-label value="Archivo OCE" class="mt-3 dark:text-gray-400" />
                <input wire:model.defer="oce" type="file" class="text-sm mt-2 input" id="{{ $oce_id }}">
                <x-jet-input-error for="oce" class="text-xs" />
            </div>

            <!-- products -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3 flex items-center justify-center">
                Productos
                <i wire:click="addSellOrderedProducts"
                    class="fas fa-plus-circle text-green-600 hover:cursor-pointer ml-3"></i>
            </h2>
            <x-jet-input-error for="customer" class="text-xs" />
            <x-jet-input-error for="sell_ordered_products_list" class="text-xs" />
            @foreach ($sell_ordered_products_list as $i => $sell_ordered_product)
                @php
                    $for_sell = App\Models\CompanyHasProductForSell::find($sell_ordered_product['company_has_product_for_sell_id']);
                    if ($for_sell['model_name'] == 'App\\Models\\Product') {
                        $current_product_for_sell = App\Models\Product::find($for_sell['model_id']);
                        $name = $current_product_for_sell->name;
                    } else {
                        $current_product_for_sell = App\Models\CompositProduct::find($for_sell['model_id']);
                        $name = $current_product_for_sell->alias;
                    }
                @endphp
                <x-item-list :index="$i" :active="true" :objectId="null">
                    <x-product-quick-view :image="$current_product_for_sell->image" :name="$name">
                        <span
                            class="text-blue-500">{{ $sell_ordered_product['quantity'] . ' unidades' }}</span>&nbsp;
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <div class="mt-2">
                <x-jet-label value="Notas" class="dark:text-gray-400" />
                <textarea wire:model.defer="notes" rows="3"
                    class="dark:bg-slate-700 dark:border-slate-500 dark:text-gray-300 dark:focus:border-indigo-700 dark:focus:ring-opacity-70 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="notes" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
