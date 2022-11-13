<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Agregar resultado
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex justify-between mx-auto bg-blue-400 rounded-lg p-4 my-6 text-sm font-medium text-blue-800"
                role="alert">
                <div class="w-full flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Si el archivo es un video, un archivo o imagen muy pesada que no se pudo subir a la biblioteca
                        de medios, se recomienda subirlo en google drive o cualquier alternativa de
                        almacenamiento en nube que se tenga y colocar el link de descarga (Enlace externo).
                        Si no, seleccionar opción "Archivo de biblioteca".
                        Si desea agregar el archivo <a href="{{ route('media-library') }}" target="_blank"
                            class="font-bold underline">click aqui</a>
                    </div>
                </div>
            </div>
            <x-my-radio :options="['Enlace externo', 'Archivo de biblioteca']" label="Tipo de resultado" model="result_in_library" />
            <div wire:loading.remove wire:target="result_in_library,back,openFolder">
                @if ($result_in_library)
                    <div class="my-3 flex justify-between items-center">
                        <div>
                            @if ($current_path !== 'ML-index')
                                <button wire:click="back" class="text-blue-500">
                                    <i class="fas fa-long-arrow-alt-left text-lg mr-2"></i>
                                    atrás
                                </button>
                            @endif
                            <span class="ml-6 font-semibold text-sm"><i class="fas fa-home" title="Inicio"></i>
                                {{ $this->currentPath }}</span>
                        </div>
                        <button wire:click="refresh" wire:loading.remove wire:target="refresh"
                            class="border border-blue-400 dark:border-gray-200 px-2 py-1 text-sm rounded text-blue-500 dark:text-gray-200">
                            <i class="fas fa-sync-alt"></i>
                            Refrescar
                        </button>
                        <span wire:loading wire:target="refresh">Refrescando...</span>
                    </div>
                    <div class="lg:grid grid-cols-2 gap-3 border-b pb-2">
                        @foreach ($resources as $resource)
                            @php
                                $next_folder = $resource->nextFolder($current_path);
                                $all_media = $next_folder ? $resource->getFirstMedia($current_path . '/' . $next_folder) : $resource->getMedia($current_path);
                            @endphp
                            @if ($next_folder)
                                <div>
                                    <i wire:click="openFolder('{{ $next_folder }}')"
                                        class="fas fa-folder mr-2 text-xl text-blue-300 cursor-pointer"></i>
                                    <span wire:click="openFolder('{{ $next_folder }}')"
                                        class="text-gray-700 text-xs cursor-pointer">{{ $next_folder }}</span>
                                </div>
                            @else
                                @foreach ($all_media as $media)
                                    <div
                                        class="{{ $media->id == $media_id ? 'border dark:border-gray-400 border-blue-400 rounded-md px-3 py-1' : '' }}">
                                        <div wire:click="$set('media_id', {{ $media->id }})" class="cursor-pointer">
                                            @if (in_array($media->mime_type, $images_types))
                                                <figure class="w-1/2 rounded-lg">
                                                    <img src="{{ $media->getUrl() }}" class="rounded-lg">
                                                </figure>
                                            @else
                                                <i class="fas fa-file text-gray-400 text-xl mr-2"></i>
                                            @endif
                                            <span class="text-gray-700 text-xs">{{ $media->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                        @if (!$resources->count())
                            <div class="text-sm text-gray-500 text-center p-8 col-span-full">No hay recursos para
                                mostrar</div>
                        @endif
                    </div>
                    <div wire:loading wire:target="back,openFolder" class="p-8 flex justify-center items-center"><i
                            class="fas fa-circle-notch animate-spin text-4xl text-gray-500"></i></div>

                    <x-jet-input-error for="media_id" class="text-xs" />
                @else
                    <div class="mt-3">
                        <x-jet-label value="Link externo (google dirve, microsoft, Amazon, zoho drive, etc.)" />
                        <input wire:model="external_link" type="text" class="input w-full text-sm mt-2" />
                        <x-jet-input-error for="external_link" class="text-xs" />
                    </div>
                @endif
                <div class="mt-3">
                    <x-jet-label value="Notas" />
                    <textarea wire:model.defer="notes" rows="3" class="input !h-[6rem] w-full"></textarea>
                    <x-jet-input-error for="notes" class="text-xs" />
                </div>
            </div>
            <div wire:loading.block wire:target="result_in_library,back,openFolder" class="text-center mt-10">
                <i class="fas fa-circle-notch text-4xl text-gray-500 animate-spin"></i>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Agregar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
