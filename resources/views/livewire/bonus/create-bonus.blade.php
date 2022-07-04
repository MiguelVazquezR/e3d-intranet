<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_bonos')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear bono
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del bono" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="text-xs" />
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-jet-label value="$ por turno completo" class="mt-3" />
                    <x-jet-input wire:model.defer="full_time" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="full_time" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="$ por medio turno" class="mt-3" />
                    <x-jet-input wire:model.defer="half_time" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="half_time" class="text-xs" />
                </div>
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
