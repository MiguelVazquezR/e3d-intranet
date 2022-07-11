<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar producto a la orden
        </x-slot>

        <x-slot name="content">
            @if ($low_stock_messages)
                <!-- alert messages for low stock -->
                <div x-data="{ open: true }" x-show="open"
                    class="flex justify-between mx-auto bg-amber-100 rounded-lg p-4 my-1 text-sm font-medium text-amber-700"
                    role="alert">
                    <div class="w-11/12 flex">
                        <i class="fas fa-exclamation-triangle w-5 h-5 inline mr-3"></i>
                        <div>
                            <span class="font-extrabold">¡Alerta de existencias insuficientes! </span><br>
                            La orden de venta se podrá generar pero asegúrese de reportar la reposición de lo siguiente:
                            <br>
                            <ul>
                                @foreach ($low_stock_messages as $message)
                                    <li class="list-disc">{!! $message !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
                </div>
            @endif
            @if ($no_stock_record_messages)
                <!-- alert messages for no stock recorded -->
                <div x-data="{ open: true }" x-show="open"
                    class="flex justify-between mx-auto bg-amber-100 rounded-lg p-4 my-1 text-sm font-medium text-amber-700"
                    role="alert">
                    <div class="w-11/12 flex">
                        <i class="fas fa-exclamation-triangle w-5 h-5 inline mr-3"></i>
                        <div>
                            <span class="font-extrabold">No hay registro de inventario</span>
                            <br>
                            <ul>
                                @foreach ($no_stock_record_messages as $message)
                                    <li class="list-disc">{!! $message !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
                </div>
            @endif
            @if ($first_production_messages)
                <!-- alert messages for first production products -->
                <div x-data="{ open: true }" x-show="open"
                    class="flex justify-between mx-auto bg-blue-100 rounded-lg p-4 my-1 text-sm font-medium text-blue-700"
                    role="alert">
                    <div class="w-11/12 flex">
                        <i class="fas fa-exclamation-triangle w-5 h-5 inline mr-3"></i>
                        <div>
                            <span class="font-extrabold">Primera producción</span>
                            <br>
                            <ul>
                                @foreach ($first_production_messages as $message)
                                    <li class="list-disc">{!! $message !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
                </div>
            @endif
            <div class="grid grid-cols-4 gap-2 mt-2">
                <div class="col-span-3">
                    @if ($company)
                        <div>
                            <x-jet-label value="Producto (registrados al cliente)" class="mt-3" />
                            <select class="input mt-2 w-full" wire:model="product_for_sell">
                                <option value="" selected>-- Seleccione --</option>
                                @forelse($company->productsForSell as $for_sell)
                                    @php
                                        if ($for_sell->model_name == 'App\\Models\\Product') {
                                            $product = App\Models\Product::find($for_sell->model_id);
                                            $name = $product->name;
                                        } else {
                                            $product = App\Models\CompositProduct::find($for_sell->model_id);
                                            $name = $product->alias;
                                        }
                                    @endphp
                                    <option value="{{ $for_sell->id }}">{{ $name }}</option>
                                @empty
                                    <option value="">No hay productos registrados al cliente</option>
                                @endforelse
                            </select>
                        </div>
                        <x-jet-input-error for="product_for_sell" class="text-xs" />
                    @endif
                </div>
                <div>
                    <x-jet-label value="Cantidad" class="mt-3" />
                    @if (empty($product_for_sell))
                        <x-jet-input wire:model.lazy="quantity" type="number" class="w-full mt-2" disabled />
                    @else
                        <x-jet-input wire:model.lazy="quantity" type="number" class="w-full mt-2" />
                    @endif
                    <x-jet-input-error for="quantity" class="text-xs" />
                </div>
            </div>

            @if ($product_for_sell)
                @php
                    $_product_for_sell = App\Models\CompanyHasProductForSell::find($product_for_sell);
                    if ($_product_for_sell->model_name == 'App\\Models\\Product') {
                        $product = App\Models\Product::find($_product_for_sell->model_id);
                    } else {
                        $product = App\Models\CompositProduct::find($_product_for_sell->model_id);
                    }
                @endphp
                @if ($product instanceof App\Models\Product)
                    <x-simple-product-card :simpleProduct="$product" :vertical="false" />
                @else
                    <x-composit-product-card :compositProduct="$product" :vertical="false" />
                @endif
            @endif

            <div class="lg:grid lg:grid-cols-2 lg:gap-1">
                <x-my-radio :options="['Muestra', 'Venta']" label="Para" model="for_sell" />
                <x-my-radio :options="['Antiguo', 'Nuevo']" label="Diseño" model="new_design" />
            </div>
            <div>
                <x-jet-label value="Notas" />
                <textarea wire:model.defer="notes" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="notes" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Agregar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
