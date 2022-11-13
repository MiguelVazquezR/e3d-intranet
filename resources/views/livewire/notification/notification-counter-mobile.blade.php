<div>
    <div class="-mr-2 flex items-center md:hidden">
        <button @click="open_notifications = ! open_notifications"
            wire:click="$emitTo('reminder.show-reminders-mobile', 'render')"
            class="inline-flex items-center justify-center p-2 rounded-md dark:text-gray-300 dark:hover:text-gray-200 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
            <i class="fas fa-bell"></i>
            @if ($unread)
                <span class="flex h-4 w-4 relative">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                    <span
                        class="absolute flex justify-center items-center rounded-full h-3 w-3 bg-red-500 text-white font-semibold"
                        style="font-size: 10px">{{ $unread }}</span>
                </span>
            @endif
        </button>
    </div>
</div>
