<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            @if ($message->creator)
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full object-cover border-2 mr-2"
                        src="{{ $message->creator->profile_photo_url }}" />
                    <div class="flex flex-col">
                        <p class="text-sm text-gray-700 font-semibold">{{ $message->subject }}</p>
                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="content">
            <p style="white-space: pre-line;" class="px-4 py-2 text-sm bg-blue-100 text-gray-700 shadow-lg rounded-lg">
                {{ $message->body }}
            </p>
            <x-jet-label value="Comentarios" class="mt-4" />

            <div wire:ignore class="mt-1">
                <textarea id="editor1" wire:model="comment_body" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
            </div>
            @if ($comment_body)
                <x-jet-button class="mt-1" wire:click="sendComment">
                    comentar
                </x-jet-button>
            @endif

            @foreach ($comments as $_comment)
                <div class="text-sm mt-2">
                    <div class="grid grid-cols-7 lg:grid-cols-9">
                        <div class="flex justify-center">
                            <img class="h-10 w-10 rounded-full object-cover border-2"
                                src="{{ $_comment->user->profile_photo_url }}" />
                        </div>
                        <p style="white-space: pre-line;"
                            class="col-span-6 lg:col-span-8 text-gray-700 rounded-xl rounded-tl-none bg-blue-100 shadow-lg px-4 pt-2 ml-1">
                            {{ $_comment->body }}
                            <small class="text-gray-500 text-right mb-0" style="font-size: 10px;">
                                {{ $_comment->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            @endforeach

            <x-jet-label value="Leido por" class="mt-8" />
            <div class="leading-none">
                <x-avatars-batch :users="$users_read" />
            </div>
            <x-jet-label value="No leido" class="mt-3" />
            <div class="leading-none">
                <x-avatars-batch :users="$users_unread" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cerrar
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>

    {{-- @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor1'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            }
                        ]
                    }
                })
                .then(function(editor) {
                    editor.model.document.on('change:data', () => {
                        @this.set('comment_body', editor.getData());
                    })
                })
                .catch(error => {
                    console.log(error);
                });
        </script>
    @endpush --}}
</div>
