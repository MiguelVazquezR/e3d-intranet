<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl dark:text-gray-400 text-gray-800 leading-tight flex justify-between">
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
            <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search"
                placeholder="Escribe el nombre" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm dark:text-gray-400">Mostrar</span>
                <select class="input mt-2" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$bonuses" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($bonuses as $item)
                    <tr>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                ${{ $item->full_time }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                ${{ $item->half_time }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            @if ($item->is_active)
                                <span
                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Activo</span>
                                </span>
                            @else
                                <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Inactivo</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-px py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            @can('editar_bonos')
                                <i wire:click="edit({{ $item }})"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- edit modal -->
        @livewire('bonus.edit-bonus')

    </div>

</div>
