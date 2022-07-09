<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar producto de la order
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-4 gap-2 mt-2">
                <div>
                    <x-jet-label value="Cantidad" class="mt-3" />
                    <x-jet-input wire:model.defer="sell_ordered_product.quantity" type="number" class="w-full mt-2" />
                    <x-jet-input-error for="sell_ordered_product.quantity" class="text-xs" />
                </div>
                
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-1">
                <x-my-radio :options="['Muestra', 'Venta']" label="Para" model="sell_ordered_product.for_sell" />
                <x-my-radio :options="['Antiguo', 'Nuevo']" label="Diseño" model="sell_ordered_product.new_design" />
            </div>
            <div>
                <x-jet-label value="Notas" />
                <textarea wire:model.defer="sell_ordered_product.notes" rows="3" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update" class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>