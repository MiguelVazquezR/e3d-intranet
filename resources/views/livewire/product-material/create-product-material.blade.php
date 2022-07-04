<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo material
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre" class="mt-3" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="name" class="mt-3" />
            </div>

            <div class="mb-4">
                    <x-jet-label value="Material para" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model="product_family_id">
                        <option value="" selected>--- Seleccione ---</option>
                        @forelse($families as $family)
                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                        @empty
                        <option value="">No hay ninguna familia registrada</option>
                        @endforelse
                    </x-select>
                    <x-jet-input-error for="product_family_id" class="mt-2" />
                </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear material
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>