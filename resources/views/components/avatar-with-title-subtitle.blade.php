@props(['user' => null])

<div
    class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
    @if ($user)
        <a class="flex-shrink-0 hover:cursor-pointer" target="_blank" href="{{ $user->profile_photo_url }}">
            <img class="h-10 w-10 rounded-full object-cover border-2" src="{{ $user->profile_photo_url }}"
                alt="{{ $user->name }}" />
        </a>
    @else
        <i class="fas fa-user-circle text-5xl"></i>
    @endif
    <div class="ml-2 flex-1">
        <div class="text-gray-900 dark:text-gray-300">
            {{ $title }}
        </div>
        <div class="text-xs">
            {{ $subtitle }}
        </div>
    </div>
</div>
