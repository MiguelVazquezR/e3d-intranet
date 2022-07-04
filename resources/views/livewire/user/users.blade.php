<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-id-card mr-2"></i>
                Usuarios
            </div>
            @livewire('user.create-user')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search" placeholder="Escribe el ID, nombre o correo" />
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
        <x-table :models="$users" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($users as $item)
                <tr>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $item->id }}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <a class="hover:cursor-pointer" target="_blank" href="{{ $item->profile_photo_url }}">
                                <img class="h-10 w-10 rounded-full object-cover border-2" src="{{ $item->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </a>
                            <p class="text-gray-900 whitespace-no-wrap ml-1">
                                {{ $item->name }}
                            </p>
                        </div>
                        @else
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $item->name }}
                        </p>
                        @endif
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ $item->email }}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            @if ($item->employee)
                            {{ $item->employee->join_date->isoFormat('D MMMM YYYY') }}
                            @else
                            --
                            @endif
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        @if ($item->active)
                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">Activo(a)</span>
                        </span>
                        @else
                        <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                            <span class="relative">Inactivo(a)</span>
                        </span>
                        @endif
                    </td>
                    <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                        @can('ver_usuarios')
                        <i wire:click="show( {{ $item }} )" class="far fa-eye bg-sky-400 text-white p-2 mt-1 rounded-lg ml-px hover:cursor-pointer"></i>
                        @endcan
                        @can('editar_usuarios')
                        <i wire:click="edit( {{ $item }} )" class="far fa-edit bg-blue-500 text-white p-2 mt-1 rounded-lg ml-px hover:cursor-pointer"></i>
                        @endcan
                        @can('eliminar_usuarios')
                        <i wire:click="$emit('confirm', {0:'user.users', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir'})" class="fas fa-trash bg-red-500 text-white p-2 mt-1 rounded-lg ml-px hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b border-gray-200 bg-white text-center">
                        @can('más_acciones_usuarios')
                        <x-jet-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <i class="fas fa-ellipsis-v text-white p-1 rounded-lg hover:cursor-pointer bg-gray-400 ml-px mt-1"></i>
                            </x-slot>

                            <x-slot name="content">
                                <x-jet-dropdown-link href="{{ route('vacations-receipt-format', $item) }}" target="_blank">
                                    Generar recibo de vacaciones
                                </x-jet-dropdown-link>
                                <x-jet-dropdown-link wire:click="resetPassword( {{ $item }} )" :link="false">
                                    Resetear contraseña
                                </x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('user.show-user')

        <!-- edit modal -->
        @livewire('user.edit-user')
        
        <!-- aditional modals -->
        @livewire('user.reset-password')



    </div>

</div>