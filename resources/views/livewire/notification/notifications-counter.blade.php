<div>
    <div wire:loading wire:target="goToNotificationUrl">
        <x-loading-indicator />
    </div>

    <div class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
        <div class="relative">
            <x-jet-dropdown align="center" width="60">
                <x-slot name="trigger">
                    <div class="hidden space-x-8 sm:ml-10 md:flex">
                        <span class="hover:cursor-pointer inline-flex items-center">
                            <i class="fas fa-bell"></i>
                            @if ($unread)
                                <span class="flex h-4 w-4 relative">
                                    <span
                                        class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="absolute flex justify-center items-center rounded-full h-3 w-3 bg-red-500 text-white font-semibold"
                                        style="font-size: 10px">{{ $unread }}</span>
                                </span>
                            @endif
                        </span>
                    </div>
                </x-slot>

                <x-slot name="content">
                    <div
                        class="divide-y max-h-56 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                        @forelse ($notifications as $notification)
                            <x-jet-dropdown-link wire:click="goToNotificationUrl('{{ $notification->id }}')"
                                :link="false"
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
                </x-slot>
            </x-jet-dropdown>
        </div>
    </div>
</div>
