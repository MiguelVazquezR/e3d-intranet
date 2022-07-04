<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-handshake mr-2"></i>
                Reuniones
            </div>
            @livewire('meeting.create-meeting')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit, show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID, creador o título" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm">Mostrar</span>
                <x-select class="mt-2" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </x-select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$meetings" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($meetings as $item)
                    <tr>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->creator->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->title }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->start->isoFormat('dddd DD MMMM, YYYY - hh:mm a') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->end->isoFormat('dddd DD MMMM, YYYY - hh:mm a') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            @if ($item->status == 'Iniciada')
                                <span
                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ $item->status }}</span>
                                </span>
                            @elseif($item->status == 'Terminada')
                                <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ $item->status }}</span>
                                </span>
                            @else
                                <span
                                    class="relative inline-block px-3 py-1 font-semibold text-amber-800 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-amber-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ $item->status }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                            @can('ver_reuniones')
                                <i wire:click="show({{ $item }})"
                                    class="far fa-eye bg-sky-400 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                            @can('editar_reuniones')
                                @if ($item->creator->id == Auth::user()->id)
                                    <i wire:click="edit({{ $item }})"
                                        class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                                @endif
                            @endcan
                            @can('eliminar_reuniones')
                                <i wire:click="$emit('confirm', {0:'meeting.meetings', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        {{-- show --}}
        @livewire('meeting.show-meeting')

        <!-- edit -->
        @livewire('meeting.edit-meeting')

        <!-- aditional modals -->

    </div>

</div>
