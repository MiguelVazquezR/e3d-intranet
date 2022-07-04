<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_inventarios')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
        Agregar producto compuesto a inventario
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    @livewire('composit-product.search-all-composit-product')
                </div>
                @if( !empty($selected_product->id) )
                <x-product-quick-view :image="$selected_product->image" :name="$selected_product->alias" />
                @endif
            </div>
            <x-jet-input-error for="selected_product" class="mt-3" />

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-jet-label value="Cantidad" class="mt-3" />
                    <x-jet-input wire:model.defer="quantity" type="number" min="1" class="w-full mt-2" />
                    <x-jet-input-error for="quantity" class="mt-3" />
                </div>

                <div class="mb-3">
                    <x-jet-label value="Ubicación" class="mt-3" />
                    <x-jet-input wire:model.defer="location" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="location" class="mt-3" />
                </div>
            </div>

            <x-image-uploader :image="$image" :imageExtensions="$image_extensions" :imageId="$image_id" label="Imagen de ubicación" />

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store,image" class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>