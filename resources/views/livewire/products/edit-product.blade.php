<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar producto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-3" />
                <x-jet-input wire:model.defer="product.name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="product.name" class="text-xs" />
            </div>

            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-2 lg:col-span-1">
                    <x-jet-label value="Stock mínimo" class="mt-3" />
                    <x-jet-input wire:model.defer="product.min_stock" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="product.min_stock" class="text-xs" />
                </div>

                <div class="col-span-2 lg:col-span-3">
                    <div class="mb-4">
                        <x-jet-label value="Unidad de medida" class="mt-3" />
                        <x-select class="mt-2 w-full" wire:model.defer="product.measurement_unit_id" :options="$units" />
                        <x-jet-input-error for="product.measurement_unit_id" class="text-xs" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-jet-label value="Familia" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="product.product_family_id" :options="$families" />
                <x-jet-input-error for="product.product_family_id" class="text-xs" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Material" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="product.product_material_id" :options="$materials" />
                <x-jet-input-error for="product.product_material_id" class="text-xs" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Estado" class="mt-3" />
                <x-select class="mt-2 w-3/4" wire:model.defer="product.product_status_id" :options="$statuses" />
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-status.create-product-status', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product.product_status_id" class="text-xs" />
            </div>

            <x-image-uploader :image="$image" :imageExtensions="$image_extensions" :imageId="$image_id" :registeredImage="$product->image" />

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update,image" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>