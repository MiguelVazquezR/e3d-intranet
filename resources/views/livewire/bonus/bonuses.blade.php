<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-coins mr-2"></i>
                Bonos
            </div>
            @livewire('bonus.create-bonus')
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el nombre" />
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
        <x-table :models="$bonuses" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($bonuses as $item)
                    <tr>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                ${{ $item->full_time }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                ${{ $item->half_time }}
                            </p>
                        </td>
                        <td class="px-px py-3 border-b border-gray-200 bg-white">
                            @can('editar_bonos')
                                <i wire:click="edit({{ $item }})"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- edit modal -->
        @livewire('bonus.edit-bonus')

    </div>

</div>
