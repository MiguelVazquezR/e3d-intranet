<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Solicitar autorización para horas adicionales
        </x-slot>

        <x-slot name="content">
            <div class="mt-2">
                <x-jet-label value="Tiempo adicional solicitado" />
                <div class="flex items-center">
                    <x-jet-input type="number" class="mt-2 w-24" wire:model="hours" />&nbsp;:&nbsp;
                    <x-jet-input type="number" class="mt-2 w-24" wire:model="minutes" />
                </div>
                <div class="flex items-center">
                    <x-jet-input-error for="hours" class="text-xs mr-4" />&nbsp;&nbsp;
                    <x-jet-input-error for="minutes" class="text-xs" />
                </div>
            </div>

            <!-- banner -->
            <div x-data="{ open: true }" x-show="open"
                class="w-11/12 flex justify-between mx-auto bg-blue-100 rounded-lg p-4 my-6 text-sm font-medium text-blue-700"
                role="alert">
                <div class="w-11/12 flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Es necesario subir un archivo con los detalles de las actividades que justifiquen
                        el tiempo adicional que estas solicitando.
                    </div>
                </div>

                <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
            </div>

            <div class="mt-3">
                <x-jet-label value="Reporte de actividades por hacer" />
                <input wire:model.defer="report" type="file" class="text-sm mt-2" id="{{ $report_id }}">
                <x-jet-input-error for="report" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button class="mr-2 disabled:opacity-25 disabled:cursor-not-allowed" wire:loading.attr="disabled"
                wire:target="send" wire:click="send">
                Solicitar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
