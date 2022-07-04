<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_productos')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo producto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="mt-1" />
            </div>

            <div class="grid grid-cols-5 gap-4">

                <div class="col-span-2">
                    <x-jet-label value="Stock mínimo" class="mt-3" />
                    <x-jet-input wire:model.defer="min_stock" type="number" min="1" class="w-full mt-2" />
                    <x-jet-input-error for="min_stock" class="mt-1" />
                </div>

                <div class="col-span-3">
                    <x-jet-label value="Unidad de medida" class="mt-3" />
                    <x-select class="mt-2 w-1/2 lg:w-3/4" wire:model.defer="measurement_unit_id">
                        <option value="" selected>--- Seleccione ---</option>
                        @forelse($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @empty
                        <option value="">No hay ninguna unidad de medida registrada</option>
                        @endforelse
                    </x-select>
                    <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('measurement-unit.create-measurement-unit', 'openModal')">
                        <i class="fas fa-plus"></i>
                    </x-jet-secondary-button>
                    <x-jet-input-error for="measurement_unit_id" class="mt-1" />
                </div>
            </div>

            <div class="mb-4">
                <x-jet-label value="Familia" class="mt-3" />
                <x-select class="mt-2 w-3/4" wire:model="product_family_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($families as $family)
                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @empty
                    <option value="">No hay ninguna familia registrada</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-family.create-product-family', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product_family_id" class="mt-1" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Material" class="mt-3" />
                <x-select class="w-3/4 mt-2" wire:model.defer="product_material_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                    @empty
                    <option value="">No hay ningún material registrado para la familia seleccionada</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-material.create-product-material', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product_material_id" class="mt-3" />
            </div>

            <div class="mb-4">
                <x-jet-label value="Estado" class="mt-3" />
                <x-select class="mt-2 w-3/4" wire:model.defer="product_status_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @empty
                    <option value="">No hay ningun estado registrado</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('product-status.create-product-status', 'openModal')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="product_status_id" class="mt-1" />
            </div>

            <x-image-uploader :image="$image" :imageExtensions="$image_extensions" :imageId="$image_id" />

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