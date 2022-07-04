<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-user-tag mr-2"></i>
                Roles
            </div>
            @livewire('role.create-role')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search" placeholder="Escribe el ID o nombre" />
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
        <x-table :models="$roles" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach( $roles as $item )
                <tr>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->id}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            @forelse($item->permissions as $permission)
                            <span class="relative inline-block px-1 py-1 ml-1 mb-1 font-semibold text-blue-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                <span class="relative">{{ $permission->name }}</span>
                            </span>
                            @empty
                            <span class="relative inline-block px-1 py-1 font-semibold text-red-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                <span class="relative">Sin permisos registrados</span>
                            </span>
                            @endforelse
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{$item->created_at->isoFormat('D MMMM YYYY')}}
                        </p>
                    </td>
                    <td class="px-px py-3 border-b border-gray-200 bg-white">
                        @can('editar_roles')
                        <i wire:click="edit( {{$item}} )" class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                        @can('eliminar_roles')
                        <i wire:click="$emit('confirm', {0:'role.roles', 1:'delete' ,2:{{$item->id}}, 3:'Este proceso no se puede revertir'})" class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b border-gray-200 bg-white">
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- edit modal -->
        @livewire('role.edit-role')

        <!-- aditional modals -->

    </div>

</div>