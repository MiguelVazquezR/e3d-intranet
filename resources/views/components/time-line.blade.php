@props(['event'])

<li class="mb-6 ml-4">
    <div
        class="absolute w-3 h-3 bg-gray-200 rounded-full -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700">
    </div>
    <time class="mb-1 font-normal leading-none text-gray-400">
        <x-date-time-line :date="$event->created_at->isoFormat('dddd DD MMMM, YYYY')" :time="$event->created_at->isoFormat('hh:mm a')" />
    </time>
    <h3 class="text-sm font-semibold text-gray-500">
        <i class="fas fa-user mr-1"></i>
        {{ $event->user->name }}
    </h3>
    <p class="text-sm font-normal text-gray-500"> {{ $event->description }} </p>
</li>