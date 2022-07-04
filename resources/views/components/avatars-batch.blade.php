@props(['users'])

<div x-data="{ modal_open: false }">
    <div class="flex -space-x-4">
        @foreach ($users as $i => $user)
            @if ($i < 5)
                <img class="w-10 h-10 border-2 border-white rounded-full" src="{{ $user['profile_photo_url'] }}"
                    alt="{{ $user['name'] }}">
            @endif
        @endforeach
        @if (count($users) > 5)
            <button x-on:click="modal_open = !modal_open"
                class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600">
                +{{ count($users) - 5 }}
            </button>
        @endif

    </div>
    <div class="border rounded-2xl shadow-md bg-white my-2 flex flex-wrap py-1 px-2" x-show="modal_open" x-transition
        x-transition:enter.duration.500ms x-transition:leave.duration.400ms>
        @foreach ($users as $i => $user)
            <img class="w-10 h-10 border-2 border-white rounded-full" src="{{ $user['profile_photo_url'] }}"
                alt="{{ $user['name'] }}">
        @endforeach
    </div>
</div>
