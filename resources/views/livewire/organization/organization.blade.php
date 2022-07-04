<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
        <div class="flex items-center">
            <i class="fa fa-industry mr-2"></i>
            Organización
        </div>
    </h2>
</x-slot>

<div class="mx-4 lg:mx-20 mt-10 lg:grid lg:grid-cols-2 lg:gap-x-3">
    <div class="lg:border-r-2 px-2">
        <div>
            <x-jet-label value="Nombre" class="mt-3" />
            <x-jet-input wire:model.defer="organization.name" type="text" class="w-full" />
            <x-jet-input-error for="organization.name" class="mt-1" />
        </div>

        <div>
            <x-jet-label value="Razón social" class="mt-3" />
            <x-jet-input wire:model.defer="organization.bussiness_name" type="text" class="w-full" />
            <x-jet-input-error for="organization.bussiness_name" class="mt-1" />
        </div>

        <div>
            <x-jet-label value="RFC" class="mt-3" />
            <x-jet-input wire:model.defer="organization.rfc" type="text" class="w-full" />
            <x-jet-input-error for="organization.rfc" class="mt-1" />
        </div>

        <div class="grid grid-cols-2 gap-x-2">
            <div>
                <x-jet-label value="Teléfono 1" class="mt-3" />
                <x-jet-input wire:model.defer="organization.phone1" type="text" class="w-full" />
                <x-jet-input-error for="organization.phone1" class="mt-1" />
            </div>
            <div>
                <x-jet-label value="Teléfono 2" class="mt-3" />
                <x-jet-input wire:model.defer="organization.phone2" type="text" class="w-full" />
                <x-jet-input-error for="organization.phone2" class="mt-1" />
            </div>
        </div>

        <div>
            <x-jet-label value="Sitio web" class="mt-3" />
            <x-jet-input wire:model.defer="organization.web_site" type="text" class="w-full" />
            <x-jet-input-error for="organization.web_site" class="mt-1" />
        </div>

        <div class="grid grid-cols-4 gap-x-2">
            <div class="col-span-3">
                <x-jet-label value="Dirección" class="mt-3" />
                <x-jet-input wire:model.defer="organization.address" type="text" class="w-full" />
                <x-jet-input-error for="organization.address" class="mt-1" />
            </div>
            <div>
                <x-jet-label value="C.P." class="mt-3" />
                <x-jet-input wire:model.defer="organization.post_code" type="number" class="w-full" />
                <x-jet-input-error for="organization.post_code" class="mt-1" />
            </div>
        </div>
    </div>

    {{-- images --}}
    <div class="px-2 mt-3">
        <x-image-uploader :image="$logo" :imageExtensions="$image_extensions" :imageId="$logo_image_id" label="Logo de la organización"
            model="logo" :registeredImage="$organization->logo ?? null" />
        <x-image-uploader :image="$shield" :imageExtensions="$image_extensions" :imageId="$shield_image_id" :showAlerts="false"
            label="Escudo de la organización" model="shield" :registeredImage="$organization->shield ?? null" class="mt-4" />
    </div>

    {{-- buttons --}}
    <div class="my-5">
        <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update,logo, shield"
            class="disabled:opacity-25">
            Actualizar
        </x-jet-button>
    </div>
</div>
