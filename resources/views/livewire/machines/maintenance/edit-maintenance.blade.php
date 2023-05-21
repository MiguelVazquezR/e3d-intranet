<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar mantenimiento
        </x-slot>

        <x-slot name="content">
            @if ($maintenance->id)
                <div>
                    <x-jet-label value="Descripción de situación" class="mt-3 dark:text-gray-400" />
                    <textarea wire:model.defer="maintenance.problems" rows="2"
                        class="dark:bg-slate-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="maintenance.problems" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Descripción de mantenimiento" class="mt-3 dark:text-gray-400" />
                    <textarea wire:model.defer="maintenance.actions" rows="2"
                        class="dark:bg-slate-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                    <x-jet-input-error for="maintenance.actions" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Costo ($MXN)" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="maintenance.cost" type="number" class="w-full mt-2 input" />
                    <x-jet-input-error for="maintenance.cost" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Responsable de hacer mantenimiento" class="mt-3 dark:text-gray-400" />
                    <x-jet-input wire:model.defer="maintenance.responsible" type="text" class="w-full mt-2 input" />
                    <x-jet-input-error for="maintenance.responsible" class="mt-3" />
                </div>
                <div>
                    <x-jet-label value="Tipo de mantenimiento" class="mt-3 dark:text-gray-400" />
                    <select class="input mt-2" wire:model="maintenance.maintenance_type">
                        <option value="0">Preventivo</option>
                        <option value="1">Correctivo</option>
                    </select>
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
