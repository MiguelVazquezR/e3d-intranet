<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            <div class="flex items-center">
                <i class="fas fa-user-clock mr-2"></i>
                Solicitudes de tiempo adicional
            </div>
        </h2>
    </x-slot>

    <div class="py-6">

        <div wire:loading wire:target="edit,show">
            <x-loading-indicator />
        </div>

        <!-- inputs -->
        <div class="w-11/12 lg:w-3/4 mx-auto">
            <x-jet-input class="w-full placeholder:text-xs" wire:model="search" type="text" name="search"
                placeholder="Escribe el ID de la solicitud, nombre del empleado o la fecha en que se creo la solicitud" />
        </div>
        <div class="w-3/4 mx-auto flex justify-between pt-8">
            <div>
                <span class="mr-2 text-sm">Mostrar</span>
                <select class="input mt-2" wire:model="elements">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- table -->
        <x-table :models="$additional_time_requests" :sort="$sort" :direction="$direction" :columns="$table_columns">
            <x-slot name="body">
                @foreach ($additional_time_requests as $item)
                    <tr>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $item->user->name }}</p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $item->created_at }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                               ID: {{ $item->pay_roll_id }}
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ substr($item->additional_time, 0, 5) }} hrs.
                            </p>
                        </td>
                        <td class="px-3 py-3 border-b border-gray-200 bg-white">
                            @if ($item->authorized_by)
                                <span
                                    class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Autorizado</span>
                                </span>
                            @else
                                <span
                                    class="relative inline-block px-3 py-1 font-semibold text-amber-800 leading-tight">
                                    <span aria-hidden
                                        class="absolute inset-0 bg-amber-200 opacity-50 rounded-full"></span>
                                    <span class="relative">Sin autorización</span>
                                </span>
                            @endif
                        </td>
                        <td class="w-28 px-px py-3 border-b border-gray-200 bg-white">
                            <i wire:click="show({{ $item }})"
                                class="far fa-eye table-btn hover:text-sky-400"></i>
                            <i wire:click="$emit('confirm', { 0:'pay-roll-more-time.index', 1:'delete' ,2:{{ $item->id }}, 3:'Este proceso no se puede revertir' })"
                                class="fas fa-trash table-btn hover:text-red-500"></i>
                        </td>
                        <td class="py-3 border-b border-gray-200 bg-white">
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>

        <!-- show modal -->
        @livewire('pay-roll-more-time.show')

        {{-- others --}}

    </div>

</div>
