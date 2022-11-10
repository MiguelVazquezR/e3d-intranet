@props(['title', 'counter', 'icon'])

<a {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-700 overflow-hidden shadow-xl sm:rounded-lg']) }}>
    <div class="p-3 bg-white dark:bg-slate-700 border-b lg:border-b-0 lg:border-gray-300 hover:bg-gray-100 dark:hover:bg-slate-600 hover:scale-105 transition-all duration-500">
        <div class="text-center text-gray-400 dark:text-gray-300">
            {{ $title }}
        </div>
        <div class="flex justify-center items-center text-5xl text-gray-400 dark:text-gray-300 p-4">
            <div class="mr-3 {{ $counter ? 'text-black dark:text-gray-300' : ''  }}">
                {{ $counter }}
            </div>
            <div>
                <i class="{{ $counter ? 'text-black dark:text-gray-300 '.$icon : $icon  }}"></i>
            </div>
        </div>
    </div>
</a>