<div>
    <x-jet-dialog-modal wire:model="open">

        <div wire:loading wire:target="openCreateSparePartModal, openEditModal">
            cargando...
        </div>

        <x-slot name="title">
            @if ($machine->id)
                Refacciones de máquina <span class="text-sky-600 font-semibold">{{ $machine->name }}</span>
            @endif
        </x-slot>


        <x-slot name="content">

            @if ($machine->id)
                <!-- component -->
                <div class="text-right">
                    <x-jet-button class="mr-2" wire:click="openCreateSparePartModal">
                        + Agregar
                    </x-jet-button>
                </div>
                @if ($machine->spareParts->count())
                    <div class="overflow-x-auto">
                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-gray-200 dark:bg-slate-600 border-b">
                                        <tr>
                                            <th scope="col"
                                                class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                                #
                                            </th>
                                            <th scope="col"
                                                class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                                Nombre
                                            </th>
                                            <th scope="col"
                                                class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                                Ubicación
                                            </th>
                                            <th scope="col"
                                                class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                                Proveedor
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($machine->spareParts as $spare_part)
                                            <tr wire:click="openEditModal({{ $spare_part }})"
                                                class="bg-white dark:bg-slate-300 border-b transition duration-300 ease-in-out hover:bg-slate-500 cursor-pointer">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-600">
                                                    {{ $spare_part->id }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ $spare_part->name }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ $spare_part->location }}
                                                </td>
                                                <td
                                                    class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                    {{ $spare_part->supplier }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-center">No hay refacciones registradas</p>
                @endif
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
