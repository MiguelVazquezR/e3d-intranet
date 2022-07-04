<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Mostrar todo
        </x-slot>

        <x-slot name="content">
            <ol
                class="ml-2 relative border-l border-gray-200 text-xs mt-3 max-h-80 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 overflow-auto">
                @forelse ($movements as $movement)
                    <x-time-line :event="$movement" />
                @empty
                    <p class="text-center text-sm text-gray-600">
                        No hay registros para mostrar
                    </p>
                @endforelse
            </ol>
        </x-slot>

        <x-slot name="footer" class="mt-8">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>

            @if (count($movements))
                <x-jet-danger-button class="mr-2"
                    wire:click="$emit('confirm', { 0:'common.detail-modal', 1:'deleteAll', 2:'', 3:'Este proceso no se puede revertir' })">
                    Borrar todo
                </x-jet-danger-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
