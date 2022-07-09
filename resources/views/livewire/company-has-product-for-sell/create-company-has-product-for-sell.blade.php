<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Registrar producto para venta
        </x-slot>

        <x-slot name="content">
            <x-my-radio :options="['Compuesto', 'Simple']" label="Tipo de producto" model="simple_product" />

            {{-- <div class="flex border rounded-full overflow-hidden m-4 text-xs">
                <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
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
            </div> --}}

            @if ($simple_product)
                @livewire('products.search-products')
            @else
                @livewire('composit-product.search-all-composit-product')
            @endif

            @if ($selected_product)
                @if ($selected_product instanceof App\Models\Product)
                    <x-simple-product-card :simpleProduct="$selected_product" :vertical="false" />
                @else
                    <x-composit-product-card :compositProduct="$selected_product" :vertical="false" />
                @endif
            @endif

            <div class="lg:grid lg:grid-cols-3 lg:gap-2 mb-3">
                <div>
                    <x-jet-label value="Precio anterior" class="mt-3" />
                    <x-jet-input wire:model.defer="old_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="old_price" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="old_price_currency" :options="$currencies" id="name" />
                </div>
                <div>
                    <x-jet-label value="establecido el" class="mt-3" />
                    <x-jet-input wire:model.defer="old_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="old_date" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Precio nuevo" class="mt-3" />
                    <x-jet-input wire:model.defer="new_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="new_price" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="new_price_currency" :options="$currencies" id="name" />
                </div>
                <div>
                    <x-jet-label value="establecido el" class="mt-3" />
                    <x-jet-input wire:model.defer="new_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="new_date" class="text-xs" />
                </div>
            </div>

            @if (is_null($edit_index))
                <div wire:click="addItemToList"
                    class="mb-2 hover:cursor-pointer flex justify-end items-center text-green-600 mt-3">
                    <i class="fas fa-plus-circle"></i>
                    <span class="ml-1 text-xs">Agregar a la lista</span>
                </div>
            @else
                <div class="flex justify-end mb-2">
                    <div wire:click="updateItem" class="hover:cursor-pointer flex items-center text-green-600 mt-3">
                        <i class="fas fa-check-circle"></i>
                        <span class="ml-1 text-xs">Actualizar producto</span>
                    </div>
                    <div wire:click="resetItem" class="hover:cursor-pointer flex items-center text-gray-600 mt-3 ml-3">
                        <i class="fas fa-times"></i>
                        <span class="ml-1 text-xs">Cancelar</span>
                    </div>
                </div>
            @endif

            @foreach ($products_list as $i => $product_for_sell)
                @php
                    if ($product_for_sell['model_name'] == 'App\Models\Product') {
                        $product = App\Models\Product::find($product_for_sell['model_id']);
                        $name = $product->name;
                    } else {
                        $product = App\Models\CompositProduct::find($product_for_sell['model_id']);
                        $name = $product->alias;
                    }
                @endphp
                <x-item-list :index="$i" :active="true" :objectId="null">
                    <x-product-quick-view :image="$product->image" :name="$name">
                        <span class="text-blue-500">precio actual: ${{ $product_for_sell['new_price'] }}</span>
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <x-jet-input-error for="products_list" class="text-xs" />
        </x-slot>

        <x-slot name="footer" class="mt-8">
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
