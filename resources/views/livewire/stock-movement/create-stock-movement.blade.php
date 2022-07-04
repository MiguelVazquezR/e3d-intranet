<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Registrar {{ $movement }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label value="Movimiento" class="mt-3" />
                <x-select class="w-3/4 mt-2" wire:model.defer="stock_action_type_id">
                    <option value="" selected>--- Seleccione ---</option>
                    @forelse($stock_action_types as $action)
                    <option value="{{ $action->id }}">{{ $action->name }}</option>
                    @empty
                    <option value="">No hay ningún tipo de movimiento registrado</option>
                    @endforelse
                </x-select>
                <x-jet-secondary-button class="ml-2 rounded-full" wire:click="$emitTo('stock-action-type.create-stock-action-type', 'openModal', 'entradas')">
                    <i class="fas fa-plus"></i>
                </x-jet-secondary-button>
                <x-jet-input-error for="stock_action_type_id" class="mt-3" />
            </div>

            @if($stock_product->product)
            <div>
                <x-jet-label value="Cantidad" class="mt-3" />
                <x-jet-input wire:model.defer="quantity" type="number" min="1" class="w-1/2 mt-2 mr-2" />
                {{ $stock_product->product->unit->name }}
                <x-jet-input-error for="quantity" class="mt-3" />
            </div>
            @endif

            <div>
                <x-jet-label value="Notas" class="mt-3" />
                <textarea wire:model="notes" rows="3" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="notes" class="mt-3" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store" class="disabled:opacity-25">
                Crear registro
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

    @livewire('stock-action-type.create-stock-action-type')

</div>