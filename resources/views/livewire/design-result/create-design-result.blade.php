<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar resultado
        </x-slot>

        <x-slot name="content">
            <input wire:model="image" type="file" class="text-sm mt-2" id="{{$image_id}}" />
            <div class="mt-3">
                <x-jet-label value="Notas" />
                <textarea wire:model.defer="notes" rows="3" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="notes" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store, image" class="disabled:opacity-25">
                Agregar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>