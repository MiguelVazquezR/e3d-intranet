<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar producto a la orden
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-4 gap-2 mt-2">
                <div class="col-span-2">
                    @livewire('products.search-products')
                </div>
                <div  class="col-span-2">
                    <x-jet-label value="Unidades" class="mt-3" />
                    <x-jet-input wire:model.defer="quantity" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="quantity" class="text-xs" />
                </div>
                <div  class="col-span-full">
                    <x-jet-label value="Código" class="mt-3" />
                    <x-jet-input wire:model.defer="code" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="code" class="text-xs" />
                </div>
            </div>

            @if ($product_for_buy)
                <x-simple-product-card :simpleProduct="$product_for_buy" :vertical="false" />
            @endif
            <div class="mt-2">
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
