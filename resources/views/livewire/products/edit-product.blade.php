<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar producto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-3" />
                <x-jet-input wire:model.defer="product.name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="product.name" class="mt-1" />
            </div>

            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-2 lg:col-span-1">
                    <x-jet-label value="Stock mínimo" class="mt-3" />
                    <x-jet-input wire:model.defer="product.min_stock" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="product.min_stock" class="mt-1" />
                </div>

                <div class="col-span-2 lg:col-span-3">
                    <div class="mb-4">
                        <x-jet-label value="Unidad de medida" class="mt-3" />
                        <x-select class="mt-2 w-full" wire:model.defer="product.measurement_unit_id">
                            <option value="" selected>--- Seleccione ---</option>
                            @forelse($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @empty
                            <option value="">No hay ninguna unidad de medida registrada</option>
                            @endforelse
                        </x-select>
                        <x-jet-input-error for="product.measurement_unit_id" class="mt-1" />
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <x-jet-label value="Material" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="product.product_material_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                    @empty
                    <option value="">No hay ningún material registrado</option>
                    @endforelse
                </x-select>
                <x-jet-input-error for="product.product_material_id" class="mt-1" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Familia" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model.defer="product.product_family_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($families as $family)
                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @empty
                    <option value="">No hay ninguna familia registrada</option>
                    @endforelse
                </x-select>
                <x-jet-input-error for="product.product_family_id" class="mt-1" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Estado" class="mt-3" />
                <x-select class="mt-2 w-3/4" wire:model.defer="product.product_status_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @empty
                    <option value="">No hay ningún estado registrado</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-status.create-product-status', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product.product_status_id" class="mt-1" />
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