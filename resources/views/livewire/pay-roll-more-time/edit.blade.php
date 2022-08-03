<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Solicitar autorización para horas adicionales
        </x-slot>

        <x-slot name="content">
            <div class="mt-2">
                <x-jet-label value="Tiempo adicional solicitado" />
                <div class="flex items-center">
                    <x-jet-input type="number" class="mt-2 w-24" wire:model="hours" />&nbsp;:&nbsp;
                    <x-jet-input type="number" class="mt-2 w-24" wire:model="minutes" />
                </div>
                <div class="flex items-center">
                    <x-jet-input-error for="hours" class="text-xs mr-4" />&nbsp;&nbsp;
                    <x-jet-input-error for="minutes" class="text-xs" />
                </div>
            </div>

            <!-- banner -->
            <div x-data="{ open: true }" x-show="open"
                class="w-full flex justify-between mx-auto bg-blue-100 rounded-lg p-4 my-6 text-sm font-medium text-blue-700"
                role="alert">
                <div class="w-full flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Al actualizar los datos de la solicitud, se volverá a pasar a revisión
                        para volver a autorizar si es que ya estaba autorizado.
                    </div>
                </div>

                <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
            </div>

            <div wire:ignore>
                <x-jet-label value="Nuevo reporte" class="mt-3" />
                <textarea id="editor3" wire:model.defer="report" rows="3"></textarea>
            </div>
            <div class="mt2">
                <x-jet-label value="Reporte registrado" class="mt-3" />
                <div class="px-4 py-2 text-sm bg-blue-100 text-gray-700 shadow-lg rounded-lg">
                    {!! $_report !!}
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button class="mr-2 disabled:opacity-25 disabled:cursor-not-allowed" wire:loading.attr="disabled"
                wire:target="update" wire:click="update">
                Actualizar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor3'), {
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
                        @this.set('report', editor.getData());
                    })
                })
                .catch(error => {
                    console.log(error);
                });
        </script>
    @endpush

</div>
