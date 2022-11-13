<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 dark:bg-blue-700 dark:text-gray-300 dark:hover:bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
