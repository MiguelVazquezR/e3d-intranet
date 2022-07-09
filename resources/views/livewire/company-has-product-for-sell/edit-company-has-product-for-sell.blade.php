<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Actualizar producto para venta
        </x-slot>

        <x-slot name="content">
            @if ($product_for_sell)
                @php
                    if ($product_for_sell->model instanceof App\Models\Product) {
                        $product = App\Models\Product::find($product_for_sell->model_id);
                        $name = $product->name;
                    } else {
                        $product = App\Models\CompositProduct::find($product_for_sell->model_id);
                        $name = $product->name;
                    }
                @endphp
                @if ($product instanceof App\Models\Product)
                    <x-simple-product-card :simpleProduct="$product" :vertical="false" />
                @else
                    <x-composit-product-card :compositProduct="$product" :vertical="false" />
                @endif

            @endif

            <div class="lg:grid lg:grid-cols-3 lg:gap-2 mb-3">
                <div>
                    <x-jet-label value="Precio anterior" class="mt-3" />
                    <x-jet-input wire:model.defer="product_for_sell.old_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="product_for_sell.old_price" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="product_for_sell.old_price_currency">
                        @forelse($currencies as $currency)
                            <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                        @empty
                            <option value="">No hay ninguna moneda registrada</option>
                        @endforelse
                    </x-select>
                </div>
                <div>
                    <x-jet-label value="establecido el" class="mt-3" />
                    <x-jet-input wire:model.defer="old_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="old_date" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Precio nuevo" class="mt-3" />
                    <x-jet-input wire:model.defer="product_for_sell.new_price" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="product_for_sell.new_price" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Moneda" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="product_for_sell.new_price_currency" :options="$currencies" id="name" />
                </div>
                <div>
                    <x-jet-label value="establecido el" class="mt-3" />
                    <x-jet-input wire:model.defer="new_date" type="date" class="w-full mt-2" />
                    <x-jet-input-error for="new_date" class="text-xs" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer" class="mt-8">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
