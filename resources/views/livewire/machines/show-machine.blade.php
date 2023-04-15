<div>
    <x-jet-dialog-modal wire:model="open">

        <div wire:loading wire:target="openModal">
            <x-loading-indicator />
        </div>

        <x-slot name="title">
            Máquina {{ $machine->id }}
        </x-slot>

        <x-slot name="content">

            <div class="lg:grid grid-cols-2 gap-3">
                @if ($machine->image)
                    <a href="{{ Storage::url($machine->image) }}" target="_blank"><img
                            class="w-48 h-48 rounded-2xl object-cover object-center"
                            src="{{ Storage::url($machine->image) }}"> </a>
                @endif
                @if ($machine->id)
                    <div>
                        <x-jet-label value="Nombre" class="mt-3" />
                        <p>{{ $machine->name }}</p>
                    </div>
                    <div>
                        <x-jet-label value="Número de serie" class="mt-3" />
                        <p>{{ $machine->serial_number ?? '--' }}</p>
                    </div>
                    <div>
                        <x-jet-label value="Dimensiones (ancho x largo x alto cm)" class="mt-3" />
                        <p>{{ $machine->width ?? '--' }} x {{ $machine->large ?? '--' }} x
                            {{ $machine->height ?? '--' }} (cm)</p>
                    </div>
                    <div>
                        <x-jet-label value="Costo" class="mt-3" />
                        <p>{{ number_format($machine->cost) ?? '--' }} $MXN</p>
                    </div>
                    <div>
                        <x-jet-label value="Proveedor" class="mt-3" />
                        <p>{{ $machine->supplier ?? '--' }}</p>
                    </div>
                    <div>
                        <x-jet-label value="Fecha de adquisición" class="mt-3" />
                        @if ($machine->aquisition_date)
                            <p>{{ $machine->aquisition_date->isoFormat('DD MMMM, YYYY') }}</p>
                        @else
                            <p>--</p>
                        @endif
                    </div>
                    <div class="col-span-full">
                        <h2 class="my-2 dark:text-gray-300 text-gray-700 text-lg">
                            <i class="fas fa-paperclip"></i>
                            Archivos adjuntos
                        </h2>
                        @forelse ($machine->getMedia('files') as $media)
                            {{ $media }}
                        @empty
                            <p class="text-sm text-center">No hay archivos adjuntos a esta máquina</p>
                        @endforelse
                    </div>
                @endif
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
