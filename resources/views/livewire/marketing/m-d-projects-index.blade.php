<div>
    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID o nombre de proyecto" />
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
        <x-table :models="$marketing_projects" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($marketing_projects as $item)
                    <tr>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->creator->name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->created_at->isoFormat('D MMMM YYYY, hh:mm a') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->project_name }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                ${{ number_format($item->project_cost, 2) }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <div class="font-medium text-blue-700 dark:text-blue-400" style="font-size: 10px">
                                <span>Tareas: </span>
                                <span> {{ $item->completedTasks()->count() }} </span>
                                <span> / </span>
                                <span> {{ $item->tasks->count() }} </span>
                            </div>
                            <div class="w-full dark:bg-gray-400 bg-gray-200 rounded-full h-2"
                                title="{{ $item->progressPercentage() }}% completado">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ $item->progressPercentage() }}%"></div>
                            </div>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                @if ($item->authorizedBy)
                                    <x-avatar-with-title-subtitle :user="$item->authorizedBy">
                                        <x-slot name="title">
                                            {{ $item->authorizedBy->name }}
                                        </x-slot>
                                        <x-slot name="subtitle">
                                            <span class="text-xs text-gray-400">
                                                {{ $item->authorized_at->isoFormat('D MMMM YYYY, hh:mm a') }}
                                            </span>
                                        </x-slot>
                                    </x-avatar-with-title-subtitle>
                                @else
                                    <span
                                        class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                        <span aria-hidden
                                            class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                        <span class="relative">Sin autorizar</span>
                                    </span>
                                @endif
                            </p>
                        </td>
                        <td class="px-px py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <i wire:click="show( {{ $item }} )"
                                class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                            @if ($item->creator->id == auth()->user()->id ||
                                auth()->user()->can('autorizar_proyectos_mercadotecnia'))
                                <i wire:click="$emit('confirm', { 0:'marketing.m-d-projects-index', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir' })"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endif
                        </td>
                        <td class="py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('marketing.show-project')

        <!-- aditional modales -->

    </div>

</div>
