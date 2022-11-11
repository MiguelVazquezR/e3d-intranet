<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Nueva orden de venta desde cotización
        </x-slot>

        <x-slot name="content">
            <!-- Details -->
            <h2 class="font-bold text-lg text-sky-600 mt-2">Cliente</h2>
            @if ($quote)
                @if ($quote->customer_id)
                    @php
                        $_customer = $quote->customer;
                    @endphp
                    <div class="grid grid-cols-2 gap-2 text-xs mt-2 font-bold">
                        <p>Razón social: <span class="font-normal">{{ $_customer->company->bussiness_name }}</span></p>
                        <p>RFC: <span class="font-normal">{{ $_customer->company->rfc }}</span></p>
                        <p>Sucursal: <span class="font-normal">{{ $_customer->name }}</span></p>
                        <p>Método de pago: <span class="font-normal">{{ $_customer->satMethod->key }} -
                                {{ $_customer->satMethod->description }}</span></p>
                        <p>Medio de pago: <span class="font-normal">{{ $_customer->satWay->key }} -
                                {{ $_customer->satWay->description }}</span></p>
                        <p>Uso de factura: <span class="font-normal">{{ $_customer->satType->key }} -
                                {{ $_customer->satType->description }}</span></p>
                        <p class="col-span-2">Dirección: <span class="font-normal">{{ $_customer->address }} -
                                C.P.{{ $_customer->post_code }}</span></p>
                        <div class="col-span-2 flex flex-col">
                            <x-jet-label value="Contacto" class="mt-3 dark:text-gray-400" />
                            @foreach ($_customer->contacts as $contact)
                                <label class="flex items-center radio cursor-pointer">
                                    <input wire:model="contact_id" value="{{ $contact->id }}" type="radio"
                                        name="for" />
                                    <div class="px-2">
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
                                    </div>
                                </label>
                            @endforeach
                            <x-jet-input-error for="contact_id" class="mt-2" />
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-x-2">
                        <div>
                            <x-jet-label value="Cliente" class="mt-3 dark:text-gray-500" />
                            <p class="text-sm">{{ $quote->customer_name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Contacto" class="mt-3 dark:text-gray-500" />
                            <p class="text-sm">{{ $quote->receiver }}</p>
                        </div>
                    </div>
                @endif
            @endif
            <h2 class="font-bold text-lg text-sky-600 mt-2">Logística</h2>
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
                    <x-select class="mt-2 w-full input" wire:model.defer="freight_currency" :options="$currencies" default="No colocar moneda" id="id" />
                </div>
                <div>
                    <x-jet-label value="Guía" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="tracking_guide" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="tracking_guide" class="text-xs" />
                </div>
            </div>

            <!-- order data -->
            <h2 class="font-bold text-lg text-sky-600 mt-3">Datos de la orden</h2>
            <div class="lg:grid lg:grid-cols-3 lg:gap-3">
                <div>
                    <x-jet-label value="Prioridad" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full input" wire:model.defer="priority">
                        <option value="Normal" selected>Normal</option>
                        <option value="Urgente">Urgente</option>
                        <option value="Especial">Especial</option>
                    </select>
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
            <h2 class="font-bold text-lg text-sky-600 mt-3">
                Productos
            </h2>
            @foreach ($products_for_sell_list as $i => $product_for_sell)
                @php
                    if ($product_for_sell['model_name'] == 'App\\Models\\Product') {
                        $current_product_for_sell = App\Models\Product::find($product_for_sell['model_id']);
                        $name = $current_product_for_sell->name;
                    } else {
                        $current_product_for_sell = App\Models\CompositProduct::find($product_for_sell['model_id']);
                        $name = $current_product_for_sell->alias;
                    }
                @endphp
                <x-item-list :index="$i" :active="true" :delete="false" :edit="false" :objectId="null">
                    <x-product-quick-view :image="$current_product_for_sell->image" :name="$name">
                        <span class="text-blue-500">{{ $product_for_sell['quantity'] . ' unidad(es)' }}</span>&nbsp;
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <div class="mt-2">
                <x-jet-label class="dark:text-gray-400" value="Notas" />
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
