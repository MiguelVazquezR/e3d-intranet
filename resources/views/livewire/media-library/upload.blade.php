<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Subir medios
        </x-slot>

        <x-slot name="content">
            <p class="text-sm p-2 text-blue-800 bg-blue-200 my-2 rounded">
                Si requieres que los archivos seleccionados queden dentro de una carpeta, indica el nombre
                en el campo de abajo. Si no, se
                subiran los archivos en la carpeta donde te encuentras actualmente. <br>
                No uses el caracter "/" en el nombre o no se podrá mostrar la carpeta.
            </p>
            <div class="mt-3">
                <x-jet-label value="Sub-carpeta" />
                <x-jet-input wire:model.defer="sub_folder" type="text" class="w-full" />
            </div>
            <div class="mt-3">
                <x-jet-label value="Archivo(s)" />
                <input wire:model.defer="files" id="{{$files_id}}" type="file" class="text-sm" multiple />
                <div wire:loading wire:target="files" class="p-8 flex justify-center items-center">
                    <i class="fas fa-circle-notch animate-spin text-sm text-gray-400"></i>
                    <span class="text-gray-400">cargando archivo(s)...</span>
                </div>
                <x-jet-input-error for="files" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store,files"
                class="disabled:opacity-25">
                Subir
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
