<div>
    <x-jet-dialog-modal wire:model="open">

        <x-slot name="title">
            Solicitar autorización para horas adicionales
        </x-slot>

        <x-slot name="content">
            <div class="mt-2">
                <x-jet-label value="Tiempo adicional solicitado" />
                <div class="flex items-center">
                    <input type="number" class="input mt-2 w-24" wire:model="hours" />&nbsp;:&nbsp;
                    <input type="number" class="input mt-2 w-24" wire:model="minutes" />
                </div>
                <div class="flex items-center">
                    <x-jet-input-error for="hours" class="text-xs mr-4" />&nbsp;&nbsp;
                    <x-jet-input-error for="minutes" class="text-xs" />
                </div>
            </div>

            <!-- banner -->
            <div x-data="{ open: true }" x-show="open"
                class="w-full flex justify-between mx-auto bg-blue-100 dark:bg-blue-300 rounded-lg p-4 my-6 text-sm font-medium dark:text-blue-800 text-blue-700"
                role="alert">
                <div class="w-full flex">
                    <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
                    <div>
                        Es necesario describir las actividades que justifiquen
                        el tiempo adicional que estas solicitando. <br>
                        Sólo se podrá realizar una solicitud por semana, por lo que debes de ingresar las horas semanales
                        adicionales a tu jornada normal.
                    </div>
                </div>

                <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
            </div>

            <div wire:ignore>
                <x-jet-label value="Reporte" class="mt-3" />
                <textarea id="editor2" wire:model.defer="report" rows="3"></textarea>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-2" wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button class="mr-2 disabled:opacity-25 disabled:cursor-not-allowed" wire:loading.attr="disabled"
                wire:target="send" wire:click="send">
                Solicitar
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor2'), {
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
