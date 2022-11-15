<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Actualizar bono
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del bono" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="bonus.name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="bonus.name" class="text-xs" />
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-jet-label value="Nombre del bono" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="bonus.full_time" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="bonus.full_time" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Nombre del bono" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="bonus.half_time" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="bonus.half_time" class="text-xs" />
                </div>
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