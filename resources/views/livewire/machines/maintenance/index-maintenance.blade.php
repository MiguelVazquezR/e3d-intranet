<div>
    <x-jet-dialog-modal wire:model="open">

        <div wire:loading wire:target="openModal">
            <x-loading-indicator />
        </div>

        <x-slot name="title">
            @if ($machine->id)
                Mantenimientos de máquina <span class="text-sky-600 font-semibold">{{ $machine->name }}</span>
            @endif
        </x-slot>


        <x-slot name="content">

            @if ($machine->id)
                <!-- component -->
                <div class="text-right">
                    <x-jet-button class="mr-2" wire:click="openCreateMaintenanceModal">
                        + Agregar
                    </x-jet-button>
                </div>
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
                                            Situación
                                        </th>
                                        <th scope="col"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                            Costo
                                        </th>
                                        <th scope="col"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-400 px-6 py-4 text-left">
                                            Fecha
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($machine->maintenances as $maintenance)
                                        <tr wire:click="openEditModal({{ $maintenance }})"
                                            class="bg-white dark:bg-slate-300 border-b transition duration-300 ease-in-out hover:bg-slate-500 cursor-pointer">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-600">
                                                {{ $maintenance->id }}
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                {{ substr($maintenance->problems, 0, 40) }}...
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                ${{ number_format($maintenance->cost, 2) }} MXN
                                            </td>
                                            <td class="text-sm text-gray-900 dark:text-gray-600 font-light px-6 py-4 whitespace-nowrap">
                                                {{ $maintenance->created_at->isoFormat('DD MMMM, YYYY') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <p class="text-sm text-center">No hay mantenimientos registrados</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
