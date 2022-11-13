<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-money-check-alt mr-2"></i>
                Nóminas
            </div>
            <div class="flex">
                @livewire('pay-roll-register.create-pay-roll-register')
            </div>
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID, semana o priodo" />
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
        <x-table :models="$pay_rolls" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($pay_rolls as $item)
                    <tr>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->week }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->start_period->isoFormat('D MMMM YYYY') }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                                {{ $item->end_period->isoFormat('D MMMM YYYY') }}
                            </p>
                        </td>
                        <td class="w-28 px-px py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                            @can('ver_nóminas')
                                @if (Auth::user()->can('editar_nóminas'))
                                    <i wire:click="show( {{ $item }} )"
                                        class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                                @else
                                    <a href="{{ route('pay-roll-pdf', ['item' => json_encode([$item->id, Auth::user()->id])]) }}"
                                        target="_blank"><i
                                            class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i></a>
                                @endif
                            @endcan
                            @can('editar_nóminas')
                                <i wire:click="edit( {{ $item }} )"
                                    class="far fa-edit bg-blue-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                            @can('eliminar_nóminas')
                                <i wire:click="$emit('confirm', {0:'pay-roll.pay-rolls', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir'})"
                                    class="fas fa-trash bg-red-500 text-white p-2 rounded-lg ml-1 hover:cursor-pointer"></i>
                            @endcan
                        </td>
                        <td class="py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('pay-roll.show-pay-roll')

        <!-- edit modal -->
        @livewire('pay-roll-register.edit-pay-roll-register')
        
        <!-- aditional modals -->
        @livewire('pay-roll-more-time.create')
        @livewire('pay-roll-more-time.edit')
        
    </div>

</div>
