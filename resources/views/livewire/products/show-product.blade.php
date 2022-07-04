<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Producto {{ $product->id }}
        </x-slot>

        <x-slot name="content">

            <div class="grid grid-cols-3 gap-4">
                @if ($product->image)
                    <a href="{{ Storage::url($product->image) }}" target="_blank"><img class="w-48 h-48 rounded-2xl object-cover object-center" src="{{ Storage::url($product->image) }}"> </a>
                @endif
                @if ($product->id)
                    <div>
                        <div>
                            <x-jet-label value="Nombre" class="mt-3" />
                            <p>{{ $product->name }}</p>
                        </div>
                        <div>
                            <x-jet-label value="Stock mínimo" class="mt-3" />
                            <p>{{ $product->min_stock . ' ' . $product->unit['name'] }}</p>
                        </div>
                        <div class="mb-4">
                            <x-jet-label value="Material" class="mt-3" />
                            <p>{{ $product->material['name'] }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <x-jet-label value="Familia" class="mt-3" />
                            <p>{{ $product->family['name'] }}</p>
                        </div>
                        <div class="mb-4">
                            <x-jet-label value="Estado" class="mt-3" />
                            <p>{{ $product->status['name'] }}</p>
                        </div>
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
