<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear recordatorio
        </x-slot>

        <x-slot name="content">
            <div class="mt-3">
                <x-jet-label value="Título" />
                <x-jet-input wire:model.defer="title" type="text" class="w-full mt-2" />
                <x-jet-input-error for="title" class="text-xs" />
            </div>
            
            <div class="mt-3">
                <x-jet-label value="Fecha de recordatorio" />
                <x-jet-input wire:model.defer="remind_at" type="datetime-local" class="w-full mt-2" />
                <x-jet-input-error for="remind_at" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>