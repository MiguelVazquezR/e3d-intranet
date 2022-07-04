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
                    <x-jet-input-error for="sell_ordered_product.quantity" class="mt-3" />
                </div>
                
            </div>
            <div class="grid grid-cols-2 gap-1">
                <div class="flex border rounded-full overflow-hidden m-4 text-xs">
                    <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
                        Para
                    </div>
                    <label class="flex items-center radio p-2 cursor-pointer">
                        <input wire:model="sell_ordered_product.for_sell" value="1" class="my-auto transform" type="radio" name="for" />
                        <div class="px-2">Venta</div>
                    </label>

                    <label class="flex items-center radio p-2 cursor-pointer">
                        <input wire:model="sell_ordered_product.for_sell" value="0" class="my-auto" type="radio" name="for" />
                        <div class="px-2">Muestra</div>
                    </label>
                </div>
                <div class="flex border rounded-full overflow-hidden m-4 text-xs">
                    <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
                        Diseño
                    </div>
                    <label class="flex radio p-2 cursor-pointer">
                        <input wire:model="sell_ordered_product.new_design" value="1" class="my-auto" type="radio" name="design" />
                        <div class="px-2">Nuevo</div>
                    </label>
                    <label class="flex radio p-2 cursor-pointer">
                        <input wire:model="sell_ordered_product.new_design" value="0" class="my-auto" type="radio" name="design" />
                        <div class="px-2">Antiguo</div>
                    </label>
                </div>
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