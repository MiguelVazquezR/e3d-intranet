<div>
    <div class="-mr-2 flex items-center md:hidden">
        <button @click="open_messages = ! open_messages"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
            <i class="fas fa-bullhorn"></i>
            @if ($unreaded)
                <span class="badge mb-3 bg-red-700 rounded-full px-2 py-1 text-center object-right-top text-white mr-1"
                    style="font-size: 10px;">
                    {{ $unreaded }}
                </span>
            @endif
        </button>
    </div>
</div>
