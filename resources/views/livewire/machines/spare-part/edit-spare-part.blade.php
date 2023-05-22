<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar refacción
        </x-slot>

        <x-slot name="content">
            @if ($spare_part->id)
                <div>
                    <x-jet-label value="Nombre de la refacción *" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="spare_part.name" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="spare_part.name" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Cantidad *" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="spare_part.quantity" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="spare_part.quantity" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Proveedor *" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="spare_part.supplier" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="spare_part.supplier" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Costo unitario ($MXN) *" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="spare_part.cost" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="spare_part.cost" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Descripción *" class="mt-3 dark:text-gray-400" />
                    <textarea wire:model.defer="spare_part.description" rows="2"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full dark:bg-slate-700"></textarea>
                    <x-jet-input-error for="spare_part.description" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Ubicación *" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="spare_part.location" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="spare_part.location" class="mt-3" />
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
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
