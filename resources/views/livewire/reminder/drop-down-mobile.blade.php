<div>
    <div class="-mr-2 flex items-center md:hidden">
        <button @click="open_reminders = ! open_reminders"
            wire:click="$emitTo('reminder.show-reminders-mobile', 'render')"
            class="inline-flex items-center justify-center p-2 rounded-md dark:hover:text-gray-300 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-alarm-fill inline-block relative" viewBox="0 0 16 16">
                <path
                    d="M6 .5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H9v1.07a7.001 7.001 0 0 1 3.274 12.474l.601.602a.5.5 0 0 1-.707.708l-.746-.746A6.97 6.97 0 0 1 8 16a6.97 6.97 0 0 1-3.422-.892l-.746.746a.5.5 0 0 1-.707-.708l.602-.602A7.001 7.001 0 0 1 7 2.07V1h-.5A.5.5 0 0 1 6 .5zm2.5 5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5zM.86 5.387A2.5 2.5 0 1 1 4.387 1.86 8.035 8.035 0 0 0 .86 5.387zM11.613 1.86a2.5 2.5 0 1 1 3.527 3.527 8.035 8.035 0 0 0-3.527-3.527z" />
            </svg>
            @if ($notify)
                <span class="flex h-4 w-4 relative">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                    <span class="absolute inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            @endif
        </button>
    </div>
</div>
