<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    {{-- <x-jet-button wire:click="openModal">
        + Agregar
    </x-jet-button> --}}

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar refacción
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre de la refacción *" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="name" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Cantidad *" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="quantity" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="quantity" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Proveedor *" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="supplier" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="supplier" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Costo unitario ($MXN) *" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="cost" type="number" class="w-full mt-2 input" />
                <x-jet-input-error for="cost" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Descripción *" class="mt-3 dark:text-gray-400" />
                <textarea wire:model.defer="description" rows="2"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full dark:bg-slate-700"></textarea>
                <x-jet-input-error for="description" class="mt-3" />
            </div>
            <div>
                <x-jet-label value="Ubicación *" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="location" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="location" class="mt-3" />
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
