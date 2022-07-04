<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Ver Producto compuesto
        </x-slot>

        <x-slot name="content">
            <div class="w-50 flex justify-center text-sm">
                <x-composit-product-card :compositProduct="$composit_product" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>