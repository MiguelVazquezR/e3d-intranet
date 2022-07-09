<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_proveedores')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo proveedor
        </x-slot>

        <x-slot name="content">
            <div class="lg:grid lg:grid-cols-4 lg:gap-x-2">
                <div class="lg:col-span-full">
                    <x-jet-label value="Nombre" class="mt-3" />
                    <x-jet-input wire:model.defer="name" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="name" class="text-xs" />
                </div>
                <div class="lg:col-span-3">
                    <x-jet-label value="Dirección" class="mt-3" />
                    <x-jet-input wire:model.defer="address" type="text" class="w-full mt-2 placeholder:text-xs"
                        placeholder="calle, colonia, # interior y/o exterior, ciudad, estado, país" />
                    <x-jet-input-error for="address" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="C.P." class="mt-3" />
                    <x-jet-input wire:model.defer="post_code" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="post_code" class="text-xs" />
                </div>
            </div>

            <!-- add bank data -->
            <h2 wire:click="$set('add_bank_data', {{ !$add_bank_data }})"
                class="text-center font-bold text-lg text-sky-600 my-2 hover:cursor-pointer">
                Datos bancarios
                @if ($add_bank_data)
                    <i class="fas fa-angle-up ml-1 text-gray-800"></i>
                @else
                    <i class="fas fa-angle-down ml-1 text-gray-800"></i>
                @endif
            </h2>
            @if ($add_bank_data || !is_null($edit_bank_data_index))
                <div class="p-3 bg-sky-200 my-1 rounded-2xl">
                    <div class="flex justify-end">
                        @if (is_null($edit_bank_data_index))
                            <i wire:click="addBankDataToList"
                                class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                        @else
                            <div>
                                <i wire:click="updateBankDataFromList"
                                    class="fas fa-check-circle text-green-600 hover:cursor-pointer mr-1"></i>
                                <i wire:click="closeEditBankData" class="fas fa-times hover:cursor-pointer"></i>
                            </div>
                        @endif
                    </div>

                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                        <div>
                            <x-jet-label value="Nombre de beneficiario" class="mt-3" />
                            <x-jet-input wire:model.defer="beneficiary_name" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="beneficiary_name" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="Número de cuenta" class="mt-3" />
                            <x-jet-input wire:model.defer="account" type="text"
                                class="w-full mt-2 placeholder:text-xs" />
                            <x-jet-input-error for="account" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="CLABE" class="mt-3" />
                            <x-jet-input wire:model.defer="CLABE" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="CLABE" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="Banco" class="mt-3" />
                            <x-jet-input wire:model.defer="bank" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="bank" class="text-xs" />
                        </div>
                    </div>
                </div>
            @endif

            @foreach ($bank_data_list as $i => $bank_data)
                <x-item-list :index="$i" :active="true" editMethodName="editBankData"
                    deleteMethodName="deleteBankData" :objectId="null">
                    <x-product-quick-view name="{{ $i + 1 }}:">
                        <div class="text-xs">
                            <i class="fas fa-user-circle mr-1"></i><span
                                class="mr-2">{{ $bank_data['beneficiary_name'] }}</span> &nbsp;
                            <i class="fas fa-money-check mr-1"></i><span
                                class="mr-2">{{ $bank_data['account'] }}</span> &nbsp;
                            <i class="fas fa-university mr-1"></i><span
                                class="mr-2">{{ $bank_data['bank'] }}</span> &nbsp;
                        </div>
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <x-jet-input-error for="bank_data_list" class="text-xs" />

            <!-- add Contacts -->
            <h2 wire:click="$set('add_contact', {{ !$add_contact }})"
                class="text-center font-bold text-lg text-sky-600 my-2 hover:cursor-pointer">
                Contactos
                @if ($add_contact)
                    <i class="fas fa-angle-up ml-1 text-gray-800"></i>
                @else
                    <i class="fas fa-angle-down ml-1 text-gray-800"></i>
                @endif
            </h2>
            @if ($add_contact || !is_null($edit_contact_index))
                <div class="p-3 bg-sky-200 my-1 rounded-2xl">
                    <div class="flex justify-end">
                        @if (is_null($edit_contact_index))
                            <i wire:click="addContactToList"
                                class="fas fa-plus-circle text-green-600 hover:cursor-pointer"></i>
                        @else
                            <div>
                                <i wire:click="updateContactFromList"
                                    class="fas fa-check-circle text-green-600 hover:cursor-pointer mr-1"></i>
                                <i wire:click="closeEditContact" class="fas fa-times hover:cursor-pointer"></i>
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-jet-label value="Nombre" class="mt-1" />
                        <x-jet-input wire:model.defer="contact_name" type="text" class="w-full mt-2" />
                        <x-jet-input-error for="contact_name" class="text-xs" />
                    </div>

                    <div class="lg:grid lg:grid-cols-2 lg:gap-x-2">
                        <div>
                            <x-jet-label value="Correo" class="mt-3" />
                            <x-jet-input wire:model.defer="email" type="text" class="w-full mt-2 placeholder:text-xs" />
                            <x-jet-input-error for="email" class="text-xs" />
                        </div>
                        <div>
                            <x-jet-label value="Teléfono" class="mt-3" />
                            <x-jet-input wire:model.defer="contact_phone" type="text" class="w-full mt-2" />
                            <x-jet-input-error for="contact_phone" class="text-xs" />
                        </div>
                        <div class="col-span-full">
                            <x-jet-label value="Fecha de nacimiento" class="mt-3" />
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-jet-label value="Día" class="mt-3" />
                                    <select class="input mt-2 w-full" wire:model.defer="day">
                                        <option value="">-- Seleccione --</option>
                                        @for ($day = 1; $day <= 31; $day++)
                                            <option value="{{ $day }}">{{ $day }}</option>
                                        @endfor
                                    </select>
                                    <x-jet-input-error for="day" class="text-xs" />
                                </div>
                                <div>
                                    <x-jet-label value="Mes" class="mt-3" />
                                    <select class="input mt-2 w-full" wire:model.defer="month">
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($months as $key => $month)
                                            <option value="{{ $key }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                    <x-jet-input-error for="month" class="text-xs" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @foreach ($contact_list as $i => $contact)
                <x-item-list :index="$i" :active="true" editMethodName="editContact"
                    deleteMethodName="deleteContact" :objectId="null">
                    <x-product-quick-view name="{{ $i + 1 }}:">
                        <div class="text-xs">
                            <i class="fas fa-user-circle mr-1"></i><span
                                class="mr-2">{{ $contact['name'] }}</span> &nbsp;
                            <i class="fas fa-envelope mr-1"></i><span
                                class="mr-2">{{ $contact['email'] }}</span> &nbsp;
                            <i class="fas fa-phone-alt mr-1"></i><span
                                class="mr-2">{{ $contact['phone'] }}</span> &nbsp;
                        </div>
                    </x-product-quick-view>
                </x-item-list>
            @endforeach
            <x-jet-input-error for="contact_list" class="text-xs" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
