<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Editar reunión
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Título" class="mt-3" />
                <x-jet-input wire:model.defer="meeting.title" type="text" class="w-full mt-2" />
                <x-jet-input-error for="meeting.title" class="text-xs" />
            </div>
            <div>
                <x-jet-label value="Descripción" class="mt-3" />
                <textarea wire:model.defer="meeting.description" rows="5"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="meeting.description" class="text-xs" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="Inicia" class="mt-3" />
                    <x-jet-input wire:model.defer="start" type="datetime-local" class="w-full mt-2" />
                    <x-jet-input-error for="start" class="text-xs" />
                </div>
                <div>
                    <x-jet-label value="Termina" class="mt-3" />
                    <x-jet-input wire:model.defer="end" type="datetime-local" class="w-full mt-2" />
                    <x-jet-input-error for="end" class="text-xs" />
                </div>
            </div>
            <x-my-radio :options="['Física', 'Remota']" label="Tpo de reunión" model="remote_meeting" />
            @if ($remote_meeting)
                <div>
                    <x-jet-label value="URL" class="mt-3" />
                    <x-jet-input wire:model.defer="meeting.url" placeholder="http://" type="text"
                        class="placeholder:text-gray-400 italic text w-full mt-2" />
                    <x-jet-input-error for="meeting.url" class="text-xs" />
                </div>
            @else
                <div>
                    <x-jet-label value="Lugar de reunión" class="mt-3" />
                    <x-jet-input wire:model.defer="meeting.location" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="meeting.location" class="text-xs" />
                </div>
            @endif
            <div>
                <x-jet-label value="Participantes" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model="user_id" :options="$users" />
            </div>
            <div class="my-3">
                <x-jet-label value="Lista de participantes" class="mt-3" />
                @foreach ($user_list as $i => $participant_id)
                    @php
                        $participant = App\Models\User::find($participant_id);
                    @endphp
                    <span class="text-xs rounded-full px-2 py-px bg-blue-100 text-gray-600 mr-2 mt-2">
                        {{ $participant->name }} <span wire:click="removeParticipant({{ $i }})"
                            class="hover:text-red-500 hover:cursor-pointer">x</span>
                    </span>
                @endforeach
                <x-jet-input-error for="user_list" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="update" wire:loading.attr="disabled" wire:target="update"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

</div>
