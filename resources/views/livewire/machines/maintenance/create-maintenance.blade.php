<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    {{-- <x-jet-button wire:click="openModal">
        + Agregar
    </x-jet-button> --}}

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar mantenimiento
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Descripción de situación con la máquina *" class="mt-3 dark:text-gray-400" />
                <textarea wire:model.defer="problems" rows="2"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full dark:bg-slate-700"></textarea>
                <x-jet-input-error for="problems" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Descripción de mantenimientos realizados *" class="mt-3 dark:text-gray-400" />
                <textarea wire:model.defer="actions" rows="2"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full dark:bg-slate-700"></textarea>
                <x-jet-input-error for="actions" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Costo ($MXN)" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="cost" type="number" class="w-full mt-2 input" />
                <x-jet-input-error for="cost" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Responsable del mantenimiento" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="responsible" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="responsible" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Tipo de mantenimiento" class="mt-3 dark:text-gray-400" />
                <select class="input mt-2" wire:model="maintenance_type">
                    <option value="0">Preventivo</option>
                    <option value="1">Correctivo</option>
                </select>
            </div>

        </x-slot>

        <x-slot name="footer">
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
