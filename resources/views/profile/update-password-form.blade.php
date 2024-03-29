<x-jet-form-section submit="updatePassword">
    <x-slot name="title">
        Cambiar contraseña
    </x-slot>

    <x-slot name="description">
        Asegúrate de que nadie tenga acceso a tu contraseña. Mantenla segura y no la digas a nadie.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label class="dark:text-gray-400" for="current_password" value="Contraseña actual" />
            <x-jet-input id="current_password" type="password" class="mt-1 block w-full input" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-jet-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label class="dark:text-gray-400" for="password" value="Nueva contraseña" />
            <x-jet-input id="password" type="password" class="mt-1 block w-full input" wire:model.defer="state.password" autocomplete="new-password" />
            <x-jet-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label class="dark:text-gray-400" for="password_confirmation" value="Confirmar contraseña" />
            <x-jet-input id="password_confirmation" type="password" class="mt-1 block w-full input" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-jet-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            Actualizada
        </x-jet-action-message>

        <x-jet-button>
            Actualizar
        </x-jet-button>
    </x-slot>
</x-jet-form-section>