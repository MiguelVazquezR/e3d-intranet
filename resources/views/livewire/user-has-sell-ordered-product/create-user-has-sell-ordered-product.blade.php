<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            @if ($sell_ordered_product)
                Asignar operadores
            @endif
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-jet-label value="Operador" class="mt-3" />
                    <x-select class="mt-2 w-full" wire:model.defer="user_id" :options="$operators" />
                    <x-jet-input-error for="user_id" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Tiempo estimado" class="mt-3" />
                    <x-jet-input wire:model.defer="estimated_time" type="number" class="w-1/2 mt-2" />
                    <span class="ml-1">Minutos</span>
                    <x-jet-input-error for="estimated_time" class="text-xs" />
                </div>
            </div>
            <div class="mt-2">
                <x-jet-label value="Indicaciones" />
                <textarea wire:model.defer="indications" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="indications" class="text-xs" />
            </div>
            @if (is_null($edit_index))
                <div wire:click="addItemToList"
                    class="hover:cursor-pointer flex justify-end items-center text-green-600 mt-3">
                    <i class="fas fa-plus-circle"></i>
                    <span class="ml-1 text-xs">Agregar operador a la lista</span>
                </div>
            @else
                <div class="flex justify-end">
                    <div wire:click="updateItem" class="hover:cursor-pointer flex items-center text-green-600 mt-3">
                        <i class="fas fa-check-circle"></i>
                        <span class="ml-1 text-xs">Actualizar operador</span>
                    </div>
                    <div wire:click="resetItem" class="hover:cursor-pointer flex items-center text-gray-600 mt-3 ml-3">
                        <i class="fas fa-times"></i>
                        <span class="ml-1 text-xs">Cancelar</span>
                    </div>
                </div>
            @endif

            <h2 class="text-gray-700 font-bold text-lg mt-2">Operadores</h2>
            @foreach ($activities_detail_list as $i => $activities)
                @php
                    $operator = App\Models\User::find($activities['user_id']);
                @endphp
                @if (array_key_exists('id', $activities))
                    @if (in_array($activities['id'], $temporary_deleted_list))
                        <div class="flex justify-between border-b-2 py-3 mx-6 text-gray-500">
                            <span class="text-xs">
                                Operador removido del producto
                            </span>
                            <span wire:click="removeFromTemporaryDeletedList( {{ $activities['id'] }} )"
                                class="text-sm text-blue-500 hover:cursor-pointer">
                                Deshacer
                            </span>
                        </div>
                    @else
                        <div class="flex items-center justify-between text-sm mb-1 py-2 mx-6 border-b-2">
                            <p><i class="fas fa-user-circle mr-1"></i><span
                                    class="mr-2">{{ $operator->name }}</span></p>
                            <div class="flex items-center">
                                <i wire:click="editItem({{ $i }})"
                                    class="fas fa-edit mr-3 text-blue-500 hover:cursor-pointer"></i>
                                <i wire:click="deleteItem({{ $i }})"
                                    class="fas fa-trash-alt text-red-500 hover:cursor-pointer"></i>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex items-center justify-between text-sm mb-1 py-2 mx-6 border-b-2">
                        <p>
                            <i class="fas fa-user-circle mr-1"></i><span class="mr-2">{{ $operator->name }}</span>
                            <span class="bg-blue-500 p-1 ml-2 text-white rounded-lg text-xs">Nuevo</span>
                        </p>
                        <div class="flex items-center">
                            <i wire:click="editItem({{ $i }})"
                                class="fas fa-edit mr-3 text-blue-500 hover:cursor-pointer"></i>
                            <i wire:click="deleteItem({{ $i }})"
                                class="fas fa-trash-alt text-red-500 hover:cursor-pointer"></i>
                        </div>
                    </div>
                @endif
            @endforeach
            <x-jet-input-error for="activities_detail_list" class="text-xs" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Asignar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
