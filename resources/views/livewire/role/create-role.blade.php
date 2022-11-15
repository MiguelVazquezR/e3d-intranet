<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_roles')
    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>
    @endcan
    
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear rol
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del rol" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="name" class="text-xs" />
            </div>
            <x-jet-label value="Permisos" class="mt-3 dark:text-gray-400" />
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mt-1">
                @forelse($permissions as $id => $name)
                <label class="inline-flex items-center mt-3 text-xs">
                    <input wire:model.defer="permissions_selected" type="checkbox" value="{{$id}}" class="rounded">
                    <span class="ml-1 text-gray-700 dark:text-gray-500">{{$name}}</span>
                </label>
                @empty
                <p class="col-span-full text-sm text-red-700 mt-1">No hay permisos registrados</p>
                @endforelse
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