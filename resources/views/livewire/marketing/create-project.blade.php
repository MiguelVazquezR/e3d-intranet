<div>
    <div wire:loading wire:target="openModal">
        <x-loading-indicator />
    </div>

    <x-jet-button wire:click="openModal">
        + nuevo
    </x-jet-button>

    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Nuevo proyecto
        </x-slot>

        <x-slot name="content">
            <div>
                <x-jet-label value="Nombre del proyecto" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="project_name" type="text" class="w-full mt-2 input" />
                <x-jet-input-error for="project_name" class="text-xs" />
            </div>
            <x-jet-input-error for="objective" class="text-xs" />
            <div>
                <x-jet-label value="Costo de desarrollo ($MXN)" class="mt-3 dark:text-gray-400" />
                <x-jet-input wire:model.defer="project_cost" type="number" class="w-full mt-2 input" />
                <x-jet-input-error for="project_cost" class="text-xs" />
            </div>
            <div wire:ignore>
                <x-jet-label value="Objetivos" class="mt-3 dark:text-gray-400" />
                <textarea class="input !h-[6rem]" id="editor-cmp" wire:model.defer="objective" rows="3"></textarea>
            </div>
            {{-- marketing tasks --}}
            <h2 class="mt-2 mb-1 text-gray-500 text-lg font-semibold">Tareas del proyecto</h2>
            @foreach ($tasks as $task)
                <li class="list-decimal text-sm text-blue-500">
                   {{ $task['description'] }}
                   <i class="fas fa-calendar-day ml-1 text-xs"></i>
                   {{ explode('T',$task['estimated_finish'])[0] .' '. explode('T',$task['estimated_finish'])[1] }}
                </li>
            @endforeach
            <div class="lg:grid grid-cols-2 gap-x-2 dark:bg-slate-800 bg-gray-100 shadow-lg px-2 py-1 rounded-md mt-2">
                <div class="mt-2">
                    <x-jet-label class="dark:text-gray-400" value="Tarea" />
                    <x-jet-input wire:model.defer="description" type="text" class="w-ful inputl input" />
                    <x-jet-input-error for="description" class="text-xs" />
                </div>
                <div class="mt-2">
                    <x-jet-label class="dark:text-gray-400" value="Estimado de término" />
                    <x-jet-input wire:model.defer="estimated_finish" type="datetime-local" class="w-full input" />
                    <x-jet-input-error for="estimated_finish" class="text-xs" />
                </div>
                <div class="mt-2">
                    <x-jet-label class="dark:text-gray-400" value="Involucrados" />
                    <select class="input w-full input" wire:model="user_id">
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
                {{-- buttons --}}
                <div>
                    <button
                        wire:click="addTask"
                        class="mt-1 text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-1 focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-3 py-1 text-center disabled:opacity-25 disabled:cursor-not-allowed">
                        Agregar tarea
                    </button>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                class="disabled:opacity-25">
                Crear
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor-cmp'), {
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
                        @this.set('objective', editor.getData());
                    })
                })
                .catch(error => {
                    console.log(error);
                });
        </script>
    @endpush

</div>
