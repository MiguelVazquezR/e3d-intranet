<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_días_festivos')
    <x-jet-button wire:click="openModal">
       + nuevo
    </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear día festivo
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del día festivo" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="name" class="mt-3" />
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <x-jet-label value="Día" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full input" wire:model.defer="day">
                        <option value="">-- Seleccione --</option>
                        @for ($day = 1; $day <= 31; $day++) 
                        <option value="{{ $day }}">{{ $day }}</option>
                        @endfor
                    </select>
                    <x-jet-input-error for="day" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Mes" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="month">
                        <option value="">-- Seleccione --</option>
                        @foreach($months as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="month" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Estado" class="mt-3 dark:text-gray-400" />
                    <x-select class="mt-2 w-full input" wire:model.defer="active">
                        <option value="">-- Seleccione --</option>
                        <option value="1">Activo</option>
                        <option value="0">No activo</option>
                    </x-select>
                    <x-jet-input-error for="active" class="mt-3" />
                </div>
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