<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar cotización
        </x-slot>

        <x-slot name="content">
            <x-my-radio :options="['Inglés', 'Español']" label="Idioma de plantilla" model="quote.spanish_template" />
            <x-my-radio :options="['Registrado', 'Nuevo']" label="Cliente" model="new_customer" />
            @if (!$new_customer)
                <div>
                    @livewire('customer.search-customer')
                    @if ($customer)
                        <x-customer-card :customer="$customer" />
                    @endif
                    <x-jet-input-error for="customer" class="text-xs" />
                </div>
            @else
                <div>
                    <div>
                        <x-jet-label value="Cliente" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="quote.customer_name" type="text" class="w-full mt-2 input" />
                        <x-jet-input-error for="quote.customer_name" class="text-xs" />
                    </div>
                </div>
            @endif
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="¿A quien va dirigido?" class="mt-3  dark:text-gray-400" />
                    <x-jet-input wire:model.defer="quote.receiver" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="quote.receiver" class="text-xs" />
                </div>

                <div>
                    <x-jet-label value="Departamento o puesto" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="quote.department" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="quote.department" class="text-xs" />
                </div>
            </div>

            <div>
                <x-jet-label value="Días de primera producción" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="quote.first_production_days" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="quote.first_production_days" class="text-xs" />
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <x-jet-label value="Costo de herramental" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model="quote.tooling_cost" type="text" min="1" class="w-full mt-2 input" />
                    <x-jet-input-error for="quote.tooling_cost" class="text-xs" />
                </div>

                <div>
                    <x-jet-label value="Moneda" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model="tooling_currency" :options="$currencies"
                        default="No colocar moneda" id="name" />
                </div>
                <!-- strikethrough tolling cost -->
                <div class="flex items-start mt-6">
                    <div class="flex items-center h-5">
                        <input id="strikethrough" wire:click="toggleStrikeThrough"
                            wire:model="quote.strikethrough_tooling_cost" type="checkbox"
                            class="dark:bg-gray-500 dark:border-gray-900 bg-gray-50 border-gray-300 focus:ring-3 focus:ring-blue-300 h-4 w-4 rounded">
                    </div>
                    <div class="text-sm ml-3">
                        <label for="strikethrough" class="font-medium text-gray-800 dark:text-gray-400">Tachar costo</label>
                        @if ($quote->strikethrough_tooling_cost)
                            <div class="text-gray-500">
                                <span class="font-normal text-xs line-through">{{ $quote->tooling_cost }}
                                    {{ $tooling_currency }}</span>
                            </div>
                        @else
                            <div class="text-gray-500">
                                <span class="font-normal text-xs">{{ $quote->tooling_cost }}
                                    {{ $tooling_currency }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-span-2">
                    <x-jet-label value="Costo de flete" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="quote.freight_cost" type="text" min="1"
                        class="w-full mt-2 input" />
                    <x-jet-input-error for="quote.freight_cost" class="text-xs" />
                </div>

                <div>
                    <x-jet-label value="Moneda" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="freight_currency" :options="$currencies"
                        default="No colocar moneda" id="name" />
                </div>
            </div>

            <x-jet-label value="Productos cotizados en" class="mt-3 dark:text-gray-400" />
            <x-select class="mt-2 w-1/2 input" wire:model="quote.currency_id" :options="$currencies" />

            <x-jet-secondary-button class="ml-2 rounded-full"
                wire:click="$emitTo('currency.create-currency', 'openModal')">
                <i class="fas fa-plus"></i>
            </x-jet-secondary-button>
            <x-jet-input-error for="quote.currency_id" class="text-xs" />

            <div class="flex border-2 dark:border-blue-900 rounded-full overflow-hidden m-4 text-xs">
                <div class="py-2 my-auto px-5 dark:bg-blue-700 dark:text-gray-300 bg-blue-500 text-white font-semibold mr-3">
                    Tipo de producto
                </div>
                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="simple_product" value="1" class="my-auto" type="radio" name="t_product" />
                    <div class="px-2">Simple</div>
                </label>

                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="simple_product" value="0" class="my-auto" type="radio" name="t_product" />
                    <div class="px-2">Compuesto</div>
                </label>
            </div>

            @if ($simple_product)
                @livewire('products.search-products')
            @else
                @livewire('composit-product.search-all-composit-product')
            @endif

            @if ($selected_product)
                <div class="p-3 bg-sky-100 grid grid-cols-3 gap-x-4 my-2 rounded-2xl">
                    <div>
                        <img class="rounded-2xl" src="{{ Storage::url($selected_product->image) }}">

                        <div class="mt-4 text-sm" x-data="{ toggle: @entangle('show_image') }">
                            <x-jet-label value="Mostrar imagen" class="my-2 dark:text-gray-400" />
                            <div @click="toggle == 0 ? toggle = 1 : toggle = 0"
                                class="relative rounded-full w-10 h-5 transition duration-300 ease-linear"
                                :class="[toggle == 1 ? 'bg-green-400' : 'bg-gray-300']">
                                <label for="toggle"
                                    class="absolute left-0 bg-white border-2 mb-2 w-5 h-5 rounded-full transition transform duration-200 ease-linear cursor-pointer"
                                    :class="[toggle == 1 ? 'translate-x-full border-green-400' :
                                        'translate-x-0 border-gray-300'
                                    ]"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="flex justify-between">
                            <div class="text-xs">
                                @if ($selected_product instanceof App\Models\Product)
                                    <p><b>Nombre: </b>{{ $selected_product->name }}</p>
                                    <p><b>Familia: </b>{{ $selected_product->family->name }}</p>
                                    <p> <b>Material: </b>{{ $selected_product->material->name }}</p>
                                @else
                                    <p> <b>Nombre: </b>{{ $selected_product->alias }} </p>
                                @endif
                            </div>
                            <div>
                                @if (is_null($edit_index))
                                    <i wire:click="addProductToList"
                                        class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                                @else
                                    <i wire:click="updateProductFromList"
                                        class="fas fa-check-circle text-green-600 hover:cursor-pointer"></i>
                                @endif
                                <i wire:click="resetProduct"
                                    class="fas fa-times text-gray-700 hover:cursor-pointer"></i>
                            </div>
                        </div>
                        <div class="md:w-5/12 md:inline-block">
                            <x-jet-label value="Cantidad a cotizar" class="mt-3 dark:text-gray-400" />
                            <x-jet-input wire:model.defer="quantity" type="number" min="1"
                                class="w-full mt-2 input" />
                            <x-jet-input-error for="quantity" class="text-xs" />
                        </div>
                        <div class="md:w-5/12 md:inline-block">
                            <x-jet-label value="Precio por unidad" class="mt-3 dark:text-gray-400" />
                            <x-jet-input wire:model.defer="price" type="number" min="1"
                                class="w-full mt-2 input" />
                            <x-jet-input-error for="price" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="Notas" class="mt-3 dark:text-gray-400" />
                            <textarea wire:model.defer="product_notes" rows="2"
                                class="dark:bg-slate-700 dark:border-slate-500 dark:text-gray-300 dark:focus:border-indigo-700 dark:focus:ring-opacity-70 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                            <x-jet-input-error for="product_notes" class="text-xs" />
                        </div>
                    </div>
                </div>
            @endif
            <x-jet-input-error for="products_list" class="text-xs" />
            @foreach ($products_list as $i => $product)
                @php
                    if (array_key_exists('product_id', $product)) {
                        $current_product = App\Models\Product::find($product['product_id']);
                    } else {
                        $current_product = App\Models\CompositProduct::find($product['composit_product_id']);
                    }
                    $current_currency = App\Models\Currency::find($quote->currency_id);
                @endphp
                <x-item-list :index="$i" :active="array_key_exists('id', $product)
                    ? !in_array(
                        $product['id'],
                        $current_product instanceof App\Models\Product
                            ? $temporary_product_deleted_list
                            : $temporary_composit_deleted_list,
                    )
                    : true" :objectId="array_key_exists('id', $product) ? $product['id'] : null"
                    inactiveMessage="Producto eliminado de la contización" :canUndo="false">
                    <x-product-quick-view :image="$current_product->image" :name="$current_product instanceof App\Models\Product
                        ? $current_product->name
                        : $current_product->alias" :newItem="!array_key_exists('id', $product)">
                        @if ($current_product instanceof App\Models\Product)
                            <span
                                class="text-blue-500">{{ $product['quantity'] . ' ' . $current_product->unit->name }}</span>&nbsp;
                            a &nbsp;
                        @else
                            <span class="text-blue-500">{{ $product['quantity'] }} Piezas</span>&nbsp; a &nbsp;
                        @endif
                        <span class="text-blue-500">{{ $product['price'] . $current_currency->name }}</span> por
                        unidad
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <div>
                <x-jet-label value="Notas" class="mt-3 dark:text-gray-400" />
                <textarea wire:model.defer="quote.notes" rows="5"
                    class="dark:bg-slate-700 dark:border-slate-500 dark:text-gray-300 dark:focus:border-indigo-700 dark:focus:ring-opacity-70 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="quote.notes" class="text-xs" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            @if (Auth::user()->can('autorizar_cotizaciones') && !$quote->authorized_user_id)
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