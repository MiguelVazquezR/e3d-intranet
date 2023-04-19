<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    {{-- @can('editar_maquinas')
    <x-jet-button wire:click="openModal">
       + agregar
    </x-jet-button>
    @endcan --}}

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Bitácora de Mantenimiento
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre de la máquina" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="machine.name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="machine.name" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Número de serie" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="machine.serial_number" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="machine.serial_number" class="mt-3" />
            </div>
            <div class="lg:grid grid-cols-4 gap-2">
                <div>
                    <x-jet-label value="Peso (kg)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.weight" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.weight" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Ancho (mts)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.width" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.width" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Largo (mts)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.large" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.large" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Alto (mts)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.height" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.height" class="mt-3" />
                </div>
            </div>
            <div>
                <x-jet-label value="Costo ($MXN)" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="machine.cost" type="number" class="w-full mt-2 input" />
                <x-jet-input-error for="machine.cost" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Fecha de adquisición" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="aquisition_date" type="date" class="w-full mt-2 input" />
                <x-jet-input-error for="aquisition_date" class="mt-3" />
            </div>
           
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>