<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:active:bg-gray-900 dark:focus:outline-none dark:focus:border-gray-900 dark:focus:ring-gray-300 bg-white border border-gray-300 dark:border-blue-900 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
