<div class="divide-y max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
    @forelse ($notifications as $notification)
        <x-jet-dropdown-link wire:click="goToNotificationUrl('{{ $notification->id }}')" :link="false"
            class="text-left {{ $notification->read_at ? 'text-gray-400' : 'border-l-2 border-l-yellow-500 bg-yellow-50' }} ">
            {!! $notification->data['message'] !!} <br>
            <div>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </div>
        </x-jet-dropdown-link>
    @empty
        <p class="text-gray-400 text-xs text-center py-2">No tienes notificaciones</p>
    @endforelse
</div>
