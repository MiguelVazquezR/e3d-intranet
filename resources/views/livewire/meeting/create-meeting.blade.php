<div>

    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    @can('crear_reuniones')
        <x-jet-button wire:click="openModal">
            + nuevo
        </x-jet-button>
    @endcan

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nueva reunión
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Título" class="mt-3" />
                <x-jet-input wire:model.defer="title" type="text" class="w-full mt-2" />
                <x-jet-input-error for="title" class="mt-1" />
            </div>
            <div>
                <x-jet-label value="Descripción" class="mt-3" />
                <textarea wire:model.defer="description" rows="5"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
                <x-jet-input-error for="description" class="mt-1" />
            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-2">
                <div>
                    <x-jet-label value="Inicia" class="mt-3" />
                    <x-jet-input wire:model.defer="start" type="datetime-local" class="w-full mt-2" />
                    <x-jet-input-error for="start" class="mt-1" />
                </div>
                <div>
                    <x-jet-label value="Termina" class="mt-3" />
                    <x-jet-input wire:model.defer="end" type="datetime-local" class="w-full mt-2" />
                    <x-jet-input-error for="end" class="mt-1" />
                </div>
            </div>
            <div class="flex border rounded-full overflow-hidden m-4 text-xs">
                <div class="py-2 my-auto px-5 bg-blue-500 text-white font-semibold mr-3">
                    Tipo de reunión
                </div>
                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="remote_meeting" value="1" class="my-auto" type="radio" name="r-meeting" />
                    <div class="px-2">Remota</div>
                </label>

                <label class="flex items-center radio p-2 cursor-pointer">
                    <input wire:model="remote_meeting" value="0" class="my-auto" type="radio" name="r-meeting" />
                    <div class="px-2">Física</div>
                </label>
            </div>
            @if ($remote_meeting)
                <div>
                    <x-jet-label value="URL" class="mt-3" />
                    <x-jet-input wire:model.defer="url" placeholder="http://" type="text" class="placeholder:text-gray-400 italic text w-full mt-2" />
                    <x-jet-input-error for="url" class="mt-1" />
                </div>
            @else
                <div>
                    <x-jet-label value="Lugar de reunión" class="mt-3" />
                    <x-jet-input wire:model.defer="location" type="text" class="w-full mt-2" />
                    <x-jet-input-error for="location" class="mt-1" />
                </div>
            @endif
            <div>
                <x-jet-label value="Participantes" class="mt-3" />
                <x-select class="mt-2 w-full" wire:model="user_id">
                    <option value="" selected>-- Seleccione --</option>
                    @forelse($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @empty
                        <option value="">No hay usuarios registrados</option>
                    @endforelse
                </x-select>
            </div>
            <div class="my-3">
                <h3 class="text-sm">Lista de participantes</h3>
                @foreach ($user_list as $i => $participant_id)
                    @php
                        $participant = App\Models\User::find($participant_id);
                    @endphp
                    <span class="text-xs rounded-full px-2 py-px bg-blue-100 text-gray-600 mr-2 mt-2">
                        {{ $participant->name }} <span wire:click="removeParticipant({{ $i }})"
                            class="hover:text-red-500 hover:cursor-pointer">x</span>
                    </span>
                @endforeach
                <x-jet-input-error for="user_list" class="mt-1" />
            </div>
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
