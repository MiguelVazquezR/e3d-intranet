<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Solicitud de tiempo adicional
        </x-slot>

        <x-slot name="content">

            @if ($request->user)
                <x-avatar-with-title-subtitle :user="$request->user">
                    <x-slot name="title">{{ $request->user->name }}</x-slot>
                    <x-slot name="subtitle">{{ $request->created_at->diffForHumans() }}</x-slot>
                </x-avatar-with-title-subtitle>
            @endif
            <div>
                <x-jet-label value="Tiempo solicitado" class="mt-3" />
                <p>{{ substr($request->additional_time, 0, 5) }} hrs.</p>
            </div>
            
            <div class="mt-3">
                <x-jet-label value="Descripción de actividades" />
                <div class="px-4 py-2 text-sm dark:bg-blue-300 bg-blue-100 text-gray-700 shadow-lg rounded-lg">
                    {!! $request->report !!}
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-3" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
            @if ($request->authorized_by)
                <x-jet-danger-button wire:click="removeAuthorization" wire:loading.attr="disabled"
                    wire:target="removeAuthorization" class="disabled:opacity-25">
                    Quitar autorización
                </x-jet-danger-button>
            @else
                <x-jet-button wire:click="authorize" wire:loading.attr="disabled" wire:target="authorize"
                    class="disabled:opacity-25">
                    Autorizar
                </x-jet-button>
            @endif
        </x-slot>

    </x-jet-dialog-modal>
</div>
