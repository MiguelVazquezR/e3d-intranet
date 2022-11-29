<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-400 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-drafting-compass mr-2"></i>
                Departamento de diseño
            </div>
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search" placeholder="Escribe el ID, solicitante, nombre de diseño o diseñador(a)" />
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
        <x-table :models="$design_orders" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach( $design_orders as $item )
                <tr>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 {{$item->isLate() ? 'bg-red-100 dark:bg-red-200' : 'bg-white'}}" title="{{$item->isLate() ? 'Pedido retrasado' : ''}}">
                        <p class="whitespace-no-wrap {{$item->isLate() ? 'text-red-600' : 'dark:text-gray-400 text-gray-900'}}">
                            {{ $item->id }}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->creator->name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->design_name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->designer->name}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->created_at->isoFormat('D MMMM YYYY')}}
                        </p>
                    </td>
                    <td class="px-3 py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                            {{$item->status}}
                        </p>
                    </td>
                    <td class="px-px py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                        @can('ver_departamento_diseño')
                        <i wire:click="show( {{$item}} )" class="far fa-eye bg-sky-400 text-white p-2 rounded-lg hover:cursor-pointer"></i>
                        @endcan
                    </td>
                    <td class="py-3 border-b dark:border-slate-600 dark:bg-slate-700 border-gray-200 bg-white">
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('design-department.show-design-department')

        <!-- aditional modales -->
        @livewire('design-result.create-design-result')

    </div>

</div>