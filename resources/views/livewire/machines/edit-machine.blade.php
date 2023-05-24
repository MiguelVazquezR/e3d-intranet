<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar máquina
        </x-slot>

        <x-slot name="content">
            @if ($machine->id)
                <div>
                    <x-jet-label value="Nombre de la máquina" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.name" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.name" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Número de serie" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.serial_number" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.serial_number" class="mt-3" />
                </div>
                <div class="lg:grid grid-cols-4 gap-2">
                    <div>
                        <x-jet-label value="Peso (kg)" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="machine.weight" type="number" class="w-full mt-2 input" />
                        <x-jet-input-error for="machine.weight" class="mt-3" />
                    </div>
                    <div>
                        <x-jet-label value="Ancho (mts)" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="machine.width" type="number" class="w-full mt-2 input" />
                        <x-jet-input-error for="machine.width" class="mt-3" />
                    </div>
                    <div>
                        <x-jet-label value="Largo (mts)" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="machine.large" type="number" class="w-full mt-2 input" />
                        <x-jet-input-error for="machine.large" class="mt-3" />
                    </div>
                    <div>
                        <x-jet-label value="Alto (mts)" class="mt-3 dark:text-gray-400" />
                        <x-jet-input wire:model.defer="machine.height" type="number" class="w-full mt-2 input" />
                        <x-jet-input-error for="machine.height" class="mt-3" />
                    </div>
                </div>
                <div>
                    <x-jet-label value="Costo ($MXN)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.cost" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.cost" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Fecha de adquisición" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="aquisition_date" type="date" class="w-full mt-2 input" />
                    <x-jet-input-error for="aquisition_date" class="mt-3" />
                </div>
                <div class="my-3">
                    <h2 class="dark:text-gray-300 text-gray-700 text-lg">
                        <i class="fas fa-paperclip"></i>
                        Archivos adjuntos
                    </h2>
                    <div class="flex flex-col">
                        @forelse ($machine->getMedia('files') as $media)
                            <div>
                                <a href="{{ $media->getUrl() }}" target="_blank"
                                    class="text-blue-400">{{ $media->name }}</a>
                                <i wire:click="deleteFile('{{ $media->uuid }}')"
                                    class="far fa-trash-alt text-red-500 cursor-pointer ml-3"></i>
                            </div>
                        @empty
                            <p class="text-sm text-center">No hay archivos adjuntos a esta máquina</p>
                        @endforelse
                    </div>
                </div>
                <hr class="my-2">
                <div>
                    <x-jet-label value="Mantenimiento cada (días)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="machine.days_next_maintenance" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="machine.days_next_maintenance" class="mt-3" />
                </div>
                <div class="mt-3">
                    <x-jet-label value="Archivos (manuales, imagenes, planos, etc)" class="dark:text-gray-400" />
                    <input type="file" id="files" wire:model="files" placeholder="Seleccionar" multiple>
                </div>
            @endif
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
