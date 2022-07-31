<div class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
    <div wire:loading wire:target="createReminder">
        <x-loading-indicator />
    </div>

    <div class="relative">
        <x-jet-dropdown align="center" width="60">
            <x-slot name="trigger">
                <div class="hidden space-x-8 sm:ml-10 md:flex">
                    <span class="hover:cursor-pointer inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-alarm-fill inline-block relative" viewBox="0 0 16 16">
                            <path
                                d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5zm2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.035 8.035 0 0 0 .86 5.387zM11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.035 8.035 0 0 0-3.527-3.527z" />
                        </svg>
                        <span class="flex h-4 w-4 relative">
                            <span
                                class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                            <span class="absolute inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    </span>
                </div>
            </x-slot>

            <x-slot name="content">
                <div
                    class="divide-y max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                    @forelse ($reminders as $reminder)
                        <div class="flex flex-col py-1 px-3">
                            <span class="text-sm text-gray-600">{{ $reminder->title }}</span>
                            <span class="text-xs text-gray-400 flex items-center">
                                <i class="fas fa-dot-circle mr-1 text-blue-300" style="font-size: 8px;"></i>
                                Creado {{ $reminder->created_at->diffForHumans() }}
                            </span>
                            <span class="text-xs text-gray-400 flex items-center">
                                <i class="fas fa-dot-circle mr-1 {{ $reminder->remind_at->lessThan(now()) ? 'text-green-300' : 'text-amber-300' }}"
                                    style="font-size: 8px;"
                                    title="{{ $reminder->remind_at->lessThan(now()) ? 'Recordado' : 'En espera' }}"></i>
                                Recordar el {{ $reminder->remind_at->toDateTimeString() }}
                            </span>
                            <div class="flex mt-1">
                                <button
                                    class="bg-green-300 p-1 w-5 h-5 rounded-full ml-2 mb-1 flex items-center justify-center hover:bg-green-200"><i
                                        class="fas fa-check text-green-500" style="font-size: 9px"></i></button>
                                @if (!$reminder->remind_at->lessThan(now()))
                                    <button
                                        class="bg-red-300 p-1 w-5 h-5 rounded-full ml-2 mb-1 flex items-center justify-center hover:bg-red-200"><i
                                            class="fas fa-trash text-red-500" style="font-size: 9px"></i></button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs text-center py-2">No tienes recordatorios</p>
                    @endforelse
                </div>
                <x-jet-dropdown-link :link="false" class="text-left text-blue-400 text-xs border-t"
                    wire:click="createReminder">
                    Crear un recordatorio
                </x-jet-dropdown-link>
            </x-slot>
        </x-jet-dropdown>
    </div>
</div>
