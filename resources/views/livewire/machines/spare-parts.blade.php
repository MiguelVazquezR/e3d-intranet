<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Partes de Repuesto
        </x-slot>

        <x-slot name="content">
            <x-jet-button wire:click="openModal">
                + agregar
             </x-jet-button>
             @if ($machine->id)
                 
             <div class="lg:grid grid-cols-2 gap-2 mt-3">
                 <div>
                     <x-jet-label value="Nombre de la pieza/parte:" class="mt-3 dark:text-gray-400" />
                     <ul>
                         @foreach ($machine->spareParts as $part)
                         <li class="hover:underline dark:text-gray-300"><a href="#"> {{ $part->name }}</a></li>
                         @endforeach
                        </ul>
                    </div>
                    <div>
                        <x-jet-label value="Cantidad de repuestos" class="mt-3 dark:text-gray-400" />
                        <ul>
                            @foreach ($machine->spareParts as $part)
                            <li class="hover:underline dark:text-gray-300"><a href="#"> {{ $part->quantity }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @if (!$machine->spareParts)
                    <p>No hay partes de repuesto de esta máquina</p>
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