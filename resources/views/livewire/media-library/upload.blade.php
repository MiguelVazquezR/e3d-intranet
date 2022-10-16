<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Subir medios
        </x-slot>

        <x-slot name="content">
            <p class="text-sm p-2 text-blue-800 bg-blue-200 my-2 rounded">
                Si requieres que los archivos seleccionados queden dentro de una o
                varias carpetas, ingresa el/los nombre(s) separadas por un "/". Si no, se subiran los archivos en la
                carpeta donde te encuentras actualmente.
            </p>
            <div class="mt-3">
                <x-jet-label value="Sub-carpeta" />
                <x-jet-input wire:model.defer="sub_folder" type="text" class="w-full" />
            </div>
            <div class="mt-3">
                <x-jet-label value="Archivo(s)" />
                <input wire:model.defer="files" type="file" class="text-sm" multiple />
                <x-jet-input-error for="files" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Subir
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
