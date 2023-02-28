<div>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Crear nuevo mensaje
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Asunto" class="mt-3" />
                <x-jet-input wire:model.defer="subject" type="text" class="w-full mt-2" />
                <x-jet-input-error for="subject" class="text-xs" />
            </div>
            {{-- CKEDITOR 5 --}}
            {{-- <div wire:ignore>
                <x-jet-label value="Mensaje" class="mt-3" />
                <textarea id="editor" wire:model.defer="body" rows="3"></textarea>
            </div> --}}
            <div>
                <x-jet-label value="Mensaje" class="mt-3" />
                <textarea wire:model="body" rows="3" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
            </div>
            <x-jet-input-error for="body" class="text-xs" />
            <div>
                <x-jet-label value="Enviar a" class="mt-3" />
                <select class=" input mt-2 w-full" wire:model="user_id">
                    <option value="" selected>-- Seleccione --</option>
                    <option value="all">Todos</option>
                    @forelse($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @empty
                        <option value="">No hay usuarios registrados</option>
                    @endforelse
                </select>
            </div>
            <div class="my-3">
                <h3 class="text-sm">Lista de usuarios</h3>
                @foreach ($user_list as $i => $to_user_id)
                    @php
                        $user = App\Models\User::find($to_user_id);
                    @endphp
                    <span class="text-xs rounded-full px-2 py-px bg-blue-100 text-gray-600 mr-2 mt-2">
                        {{ $user->name }} <span wire:click="removeUser({{ $i }})"
                            class="hover:text-red-500 hover:cursor-pointer">x</span>
                    </span>
                @endforeach
                <x-jet-input-error for="user_list" class="text-xs" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="send" wire:loading.attr="disabled" wire:target="send"
                class="disabled:opacity-25">
                Enviar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

    {{-- @push('js')
    <script>
            ClassicEditor
                .create(document.querySelector('#editor'), {
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
                        @this.set('body', editor.getData());
                    })
                })
                .catch(error => {
                    console.log(error);
                });
        </script>
    @endpush --}}
</div>
