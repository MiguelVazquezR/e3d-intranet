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
                @forelse ($machine->maintenances as $maintenance)
                    <div class="lg:grid grid-cols-2 gap-3">
                        {{-- @if ($maintenance->image)
                    <a href="{{ Storage::url($maintenance->image) }}" target="_blank"><img
                            class="w-48 h-48 rounded-2xl object-cover object-center"
                            src="{{ Storage::url($maintenance->image) }}"> </a>
                @endif --}}
                        <div>
                            <x-jet-label value="Tipo" class="mt-3" />
                            <p>{{ $maintenance->maintenance_type }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Situación" class="mt-3" />
                            <p>{{ $maintenance->problems ?? '--' }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Responsable del mantenimiento" class="mt-3" />
                            <p>{{ $maintenance->responsable ?? '--' }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Costo" class="mt-3" />
                            <p>{{ number_format($maintenance->cost) ?? '--' }} $MXN</p>
                        </div>
                        <div>
                            <x-jet-label value="Descripción de mantenimientos realizados" class="mt-3" />
                            <p>{{ $maintenance->actions ?? '--' }}</p>
                        </div>
                        {{-- <div>
                        <x-jet-label value="Fecha de " class="mt-3" />
                        <p>{{ $maintenance->created_at->isoFormat('DD MMMM, YYYY') }}</p>
                    </div> --}}
                        {{-- <div class="col-span-full">
                        <h2 class="my-2 dark:text-gray-300 text-gray-700 text-lg">
                            <i class="fas fa-paperclip"></i>
                            Archivos adjuntos
                        </h2>
                        <div class="flex-col flex gap-y-1">
                            @forelse ($maintenance->getMedia('files') as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank"
                                    class="text-blue-400">{{ $media->name }}</a>
                            @empty
                                <p class="text-sm text-center">No hay archivos adjuntos a esta máquina</p>
                            @endforelse
                        </div>
                    </div> --}}
                    </div>
                @empty
                    <p class="text-sm text-center">No hay mantenimientos registrados</p>
                @endforelse
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
