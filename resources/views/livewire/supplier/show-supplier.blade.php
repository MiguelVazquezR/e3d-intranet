<div>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Ver proveedor
        </x-slot>

        <x-slot name="content">
            <!-- Details -->
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="Nombre" class="mt-3 dark:text-gray-400" />
                    <p>{{ $supplier->name }}</p>
                </div>
                <div>
                    <x-jet-label value="Dirección" class="mt-3 dark:text-gray-400" />
                    <p>{{ $supplier->address }} - C.P.{{ $supplier->post_code }}</p>
                </div>
                <h4 class="text-center text-sm text-sky-500 my-1 col-span-2">Contactos</h4>
                @foreach ($supplier->contacts as $contact)
                    <div
                        class="col-span-2 flex flex-col lg:flex-row items-center text-xs mb-1 py-2 mx-6 border-b-2 lg:justify-center">
                        <div>
                            <i class="fas fa-user-circle mr-1"></i><span class="mr-2">{{ $contact->name }}</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope mr-1"></i><span class="mr-2">{{ $contact->email }}</span>
                        </div>
                        <div>
                            <i class="fas fa-phone-alt mr-1"></i><span class="mr-2">{{ $contact->phone }}</span>
                        </div>
                    </div>
                @endforeach
                <h4 class="text-center text-sm text-sky-500 my-1 col-span-2">Cuenas bancarias</h4>
                @foreach ($supplier->bankAccounts as $bank_data)
                    <div
                        class="col-span-2 flex flex-col lg:flex-row items-center text-xs mb-1 py-2 mx-6 border-b-2 lg:justify-center">
                        <div>
                            <i class="fas fa-user-circle mr-1"></i><span
                                class="mr-2">{{ $bank_data->beneficiary_name }}</span>
                        </div>
                        <div>
                            <i class="fas fa-money-check mr-1"></i><span class="mr-2">{{ $bank_data->account }}
                                CLABE: {{ $bank_data->CLABE }}</span>
                        </div>
                        <div>
                            <i class="fas fa-university mr-1"></i><span class="mr-2">{{ $bank_data->bank }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
