<div>
    <div class="divide-y max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
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
                    <button wire:click="delete({{ $reminder->id }})"
                        class="bg-green-300 p-1 w-5 h-5 rounded-full ml-2 mb-1 flex items-center justify-center hover:bg-green-200"><i
                            class="fas fa-check text-green-500" style="font-size: 9px"></i></button>
                    @if (!$reminder->remind_at->lessThan(now()))
                        <button
                            wire:click="$emit('confirm', {0:'reminder.drop-down', 1:'delete', 2:{{ $reminder->id }}, 3:'¿Deseas confirmar la eliminación?'})"
                            class="bg-red-300 p-1 w-5 h-5 rounded-full ml-2 mb-1 flex items-center justify-center hover:bg-red-200"><i
                                class="fas fa-trash text-red-500" style="font-size: 9px"></i></button>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-xs text-center py-2">No tienes recordatorios</p>
        @endforelse
    </div>
    <x-jet-dropdown-link :link="false" class="text-left text-blue-400 text-xs border-t" wire:click="createReminder">
        Crear un recordatorio
    </x-jet-dropdown-link>
</div>
