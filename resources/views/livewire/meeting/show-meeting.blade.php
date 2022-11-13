<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Ver Reunión
        </x-slot>

        <x-slot name="content">
            @if ($meeting)
                <div>
                    <x-jet-label value="Título" class="mt-3" />
                    {{ $meeting->title }}
                </div>
                <div>
                    <x-jet-label value="Descripción" class="mt-3" />
                    {{ $meeting->description }}
                </div>
                <div>
                    <x-jet-label value="Inicia" class="mt-3" />
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ $meeting->start->isoFormat('dddd DD MMMM, YYYY') }}
                    <i class="far fa-clock mr-1 ml-3"></i>
                    {{ $meeting->start->isoFormat('hh:mm a') }}
                </div>
                <div>
                    <x-jet-label value="Termina" class="mt-3" />
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ $meeting->end->isoFormat('dddd DD MMMM, YYYY') }}
                    <i class="far fa-clock mr-1 ml-3"></i>
                    {{ $meeting->end->isoFormat('hh:mm a') }}
                </div>
                @if ($meeting->location)
                    <div>
                        <x-jet-label value="Lugar de reunión" class="mt-3" />
                        {{ $meeting->location }}
                    </div>
                @else
                    <div>
                        <x-jet-label value="URL" class="mt-3" />
                        <a href="{{ $meeting->url }}" target="_blank"
                            class="text-blue-400 italic">{{ $meeting->url }}</a>
                    </div>
                @endif
                <div class="my-3">
                    <x-jet-label value="Lista de participantes" class="mt-3" />
                    <span class="text-xs rounded-full px-2 py-px dark:bg-green-300 bg-green-100 text-gray-600 mr-2 mt-2">
                        {{ $meeting->creator->name }} (creador de la reunión)
                    </span><br>
                    @foreach ($meeting->participants as $participant)
                        <span class="text-xs rounded-full px-2 py-px dark:bg-blue-300 bg-blue-100 text-gray-600 mr-2 mt-2">
                            {{ $participant->user->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
