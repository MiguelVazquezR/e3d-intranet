<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Editar orden de venta
        </x-slot>

        <x-slot name="content">
            <div>
                @livewire('customer.search-customer')
                @if ($customer)
                    <x-customer-card :customer="$customer" />
                    <div class="flex flex-col">
                        <x-jet-label value="Contacto" class="mt-3 dark:text-gray-400" />
                        @foreach ($customer->contacts as $contact)
                            <label class="flex items-center radio cursor-pointer dark:text-gray-400">
                                <input wire:model="sell_order.contact_id" value="{{ $contact->id }}" type="radio"
                                    name="contact" />
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
                        <x-jet-input-error for="sell_order.contact_id" class="text-xs" />
                    </div>
                @endif
                <x-jet-input-error for="customer" class="mt-2" />
            </div>
            <h2 class="text-center font-bold text-lg text-sky-600 mt-2">Logística</h2>
            <div class="lg:grid lg:grid-cols-3 lg:gap-3">
                <div>
                    <x-jet-label value="Paquetería" class="mt-dark:text-gray-400" />
                    <x-jet-input wire:model.defer="sell_order.shipping_company" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="sell_order.shipping_company" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Costo logística" class="mt-3  dark:text-gray-400" />
                    <x-jet-input wire:model.defer="sell_order.freight_cost" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="sell_order.freight_cost" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="freight_currency" :options="$currencies" id="name"
                        default="No colocar moneda" />
                </div>
                <div>
                    <x-jet-label value="Guía" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="sell_order.tracking_guide" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="sell_order.tracking_guide" class="text-xs" />
                </div>
            </div>

            <!-- order data -->
            <h2 class="text-center font-bold text-lg text-sky-600 mt-3">Datos de la orden</h2>
            <div class="lg:grid lg:grid-cols-3 lg:gap-3">
                <div>
                    <x-jet-label value="Prioridad" class="mt-3dark:text-gray-400" />
                    <select class="input mt-2 w-full input" wire:model.defer="sell_order.priority">
                        <option value="Normal" selected>Normal</option>
                        <option value="Urgente">Urgente</option>
                        <option value="Especial">Especial</option>
                    </select>
                </div>
                <div>
                    <x-jet-label value="Medio de petición" class="mt-3dark:text-gray-400" />
                    <x-jet-input wire:model.defer="sell_order.order_via" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="sell_order.order_via" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Factura" class="mt-3  dark:text-gray-400" />
                    <x-jet-input wire:model.defer="sell_order.invoice" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="sell_order.invoice" class="text-xs" />
                </div>
            </div>
            <div>
                <x-jet-label value="Nombre/folio OCE" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="sell_order.oce_name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="sell_order.oce_name" class="text-xs" />
            </div>
            <div>
                <x-jet-label value="Archivo OCE" class="mt-3 dark:text-gray-400" />
                <input wire:model.defer="oce" type="file" class="text-sm mt-2 input" id="{{ $oce_id }}">
                @if ($sell_order->oce)
                    <a href="{{ Storage::url($sell_order->oce) }}" target="_blank"
                        class="text-blue-400 hover:underline">Ver OCE subida</a>
                @endif
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
                    $product_for_sell = App\Models\CompanyHasProductForSell::find($sell_ordered_product['company_has_product_for_sell_id']);
                    if ($product_for_sell->model_name == 'App\\Models\\' . Product::class) {
                        $current_product_for_sell = App\Models\Product::find($product_for_sell->model_id);
                        $name = $current_product_for_sell->name;
                    } else {
                        $current_product_for_sell = App\Models\CompositProduct::find($product_for_sell->model_id);
                        $name = $current_product_for_sell->alias;
                    }
                @endphp
                <x-item-list :index="$i" :active="array_key_exists('id', $sell_ordered_product)
                    ? !in_array($sell_ordered_product['id'], $temporary_deleted_list)
                    : true" :objectId="array_key_exists('id', $sell_ordered_product) ? $sell_ordered_product['id'] : null" :inactiveMessage="$deleted_message" :canUndo="true"
                    :edit="false">
                    <x-product-quick-view :image="$current_product_for_sell->image" :name="$name" :newItem="!$product_for_sell">
                        <span
                            class="text-blue-500">{{ $sell_ordered_product['quantity'] . ' unidades' }}</span>&nbsp;
                        @if (array_key_exists('id', $sell_ordered_product))
                            @php
                                $sell_ordered_product_object = App\Models\SellOrderedProduct::find($sell_ordered_product['id']);
                            @endphp
                            <span class="text-orange-500">(Operadores asignados:
                                {{ $sell_ordered_product_object->activityDetails()->count() }})</span>&nbsp;
                        @endif
                    </x-product-quick-view>
                    @can('asignar_operadores_a_ov')
                        <x-slot name="aditional_buttons">
                            <i wire:click="addOperators({{ $i }})"
                                class="fas fa-user-plus mr-3 text-orange-500 hover:cursor-pointer"></i>
                        </x-slot>
                    @endcan
                </x-item-list>
            @endforeach
            <div class="mt-2">
                <x-jet-label value="Notas" class="dark:text-gray-400" />
                <textarea wire:model.defer="sell_order.notes" rows="3"
                    class="dark:bg-slate-700 dark:border-slate-500 dark:text-gray-300 dark:focus:border-indigo-700 dark:focus:ring-opacity-70 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="sell_order.notes" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            @if (Auth::user()->can('autorizar_ordenes_venta') && !$sell_order->authorized_user_id)
                <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize"
                    class="disabled:opacity-25 mr-2">
                    Autorizar
                </x-jet-button>
            @endif
            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
