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
                <x-jet-label value="Nombre del proyecto" class="mt-3" />
                <x-jet-input wire:model.defer="project_name" type="text" class="w-full mt-2" />
                <x-jet-input-error for="project_name" class="text-xs" />
            </div>
            <x-jet-input-error for="objective" class="text-xs" />
            <div>
                <x-jet-label value="Costo de desarrollo ($MXN)" class="mt-3" />
                <x-jet-input wire:model.defer="project_cost" type="number" class="w-full mt-2" />
                <x-jet-input-error for="project_cost" class="text-xs" />
            </div>
            <div wire:ignore>
                <x-jet-label value="Objetivos" class="mt-3" />
                <textarea id="editor-cmp" wire:model.defer="objective" rows="3"></textarea>
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
                        @this.set('body', editor.getData());
                    })
                })
                .catch(error => {
                    console.log(error);
                });
        </script>
    @endpush

</div>
