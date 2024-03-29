<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Actualizar día festivo
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del día festivo" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="holyday.name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="holyday.name" class="text-xs" />
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <x-jet-label value="Día" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full" wire:model.defer="day">
                        @for ($day = 1; $day <= 31; $day++)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endfor
                    </select>
                    <x-jet-input-error for="day" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Mes" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full" wire:model.defer="month">
                        @foreach ($months as $key => $month)
                            <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="month" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Estado" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2 w-full" wire:model.defer="holyday.active">
                        <option value="1">Activo</option>
                        <option value="0">No activo</option>
                    </select>
                    <x-jet-input-error for="holyday.active" class="text-xs" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
