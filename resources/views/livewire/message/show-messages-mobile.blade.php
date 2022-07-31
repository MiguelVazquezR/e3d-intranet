<div class="border-t">
    <div class="flex items-center justify-between w-11/12 my-px mx-auto" @click="can_close = false">
        <div class="rounded-full p-2 bg-white shadow-md text-xs">
            <span wire:click="$set('received', true)"
                class="{{ $received ? 'bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' : 'text-gray-700 p-1 hover:cursor-pointer' }}">
                Recibidos
            </span>
            <span wire:click="$set('received', false)"
                class="{{ $received ? 'text-gray-700 p-1 hover:cursor-pointer' : 'bg-blue-100 text-blue-500 border border-blue-500 rounded-full p-1 hover:cursor-pointer' }}">
                Enviados
            </span>
        </div>
        <div wire:click="render">
            <i wire:loading.remove class="fas fa-sync-alt text-sm text-gray-500 hover:cursor-pointer"></i>
            <i wire:loading class="fas fa-sync-alt text-sm text-gray-400 animate-spin"></i>
        </div>
    </div>
    @if ($received)
        <div wire:loading.remove @click="can_close = true"
            class="divide-y-2 max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
            @forelse ($notifications as $notification)
                @php
                    $message = App\Models\Message::findOrFail($notification->data['id']);
                @endphp
                <x-jet-dropdown-link wire:click="showMessage('{{ $notification->id }}')" :link="false"
                    class="text-left {{ $notification->read_at ? 'text-gray-400' : 'border-l-4 border-l-yellow-500 bg-yellow-50' }} ">
                    {!! $notification->data['notification_description'] !!} <br>
                    <div class="flex justify-between">
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                        <small><i class="fas fa-comments"></i> {{ $message->comments->count() }} </small>
                    </div>
                </x-jet-dropdown-link>
            @empty
                <p class="p-2 py-2 text-xs text-center text-gray-500"> No tienes mensajes </p>
            @endforelse
        </div>
        <p wire:loading class="p-2 py-2 text-xs text-center text-gray-500"> Cargando ... </p>
    @else
        <div wire:loading.remove @click="can_close = true"
            class="divide-y-2 max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
            @forelse ($sent_messages as $sent_message)
                <x-jet-dropdown-link wire:click="showMessage('{{ $sent_message->id }}')" :link="false"
                    class="text-left text-gray-400">
                    {!! $sent_message->subject !!} <br>
                    <div class="flex justify-between">
                        <small>{{ $sent_message->created_at->diffForHumans() }}</small>
                        <small><i class="fas fa-comments"></i> {{ $sent_message->comments->count() }} </small>
                    </div>
                </x-jet-dropdown-link>
            @empty
                <p class="p-2 py-2 text-xs text-center text-gray-500"> No tienes mensajes </p>
            @endforelse
        </div>
        <p wire:loading class="p-2 py-2 text-xs text-center text-gray-500"> Cargando ... </p>
    @endif

    <x-jet-dropdown-link :link="false" class="text-left text-blue-400 text-xs border-t" wire:click="createMessage">
        Crear un mensaje
    </x-jet-dropdown-link>
</div>
