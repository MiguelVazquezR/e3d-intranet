<div>
    <div class="rounded-md w-11/12 mx-auto dark:text-gray-400">
        <div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                <div class="inline-block min-w-full shadow rounded-lg overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 overflow-x-hidden">
                    @if( $models->count() )
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                @foreach($columns as $key => $name)
                                <th wire:click="order( '{{$key}}' )" class="cursor-pointer px-3 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-slate-800 dark:text-gray-400 text-left text-xs font-semibold text-gray-600 tracking-wider">
                                    <span class="uppercase">{{ $name }}</span>
                                    @if($sort == $key)
                                    @if($direction == 'asc')
                                    <i class="fas fa-sort-up float-right mt-px text-blue-500"></i>
                                    @else
                                    <i class="fas fa-sort-down float-right mt-px text-blue-500"></i>
                                    @endif
                                    @else
                                    <i class="fas fa-sort float-right mt-px"></i>
                                    @endif
                                </th>
                                @endforeach
                                <th class="w-28 px-px py-3 border-b-2 dark:bg-slate-800 dark:text-gray-400 border-gray-200 bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    &nbsp;
                                </th>
                                <th class="py-3 border-b-2 dark:bg-slate-800 dark:text-gray-400 border-gray-200 bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            {{$body}}
                        </tbody>
                    </table>
                    @else
                    <div class="text-center p-4 dark:text-slate-400">
                        <p>No hay ningun registro para mostrar</p>
                    </div>
                    @endif

                    @if( $models->hasPages() )
                    <div class="p-3">
                        {{ $models->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>