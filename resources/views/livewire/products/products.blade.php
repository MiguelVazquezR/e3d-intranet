<div>
   <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
         <div class="flex items-center dark:text-gray-400">
            <i class="fab fa-product-hunt mr-2 dark:text-gray-400"></i>
            Productos simples
         </div>
         @livewire('products.create-product')
      </h2>
   </x-slot>

   <div class="py-6">

      <div wire:loading wire:target="edit,show">
         <x-loading-indicator />
      </div>

      <!-- inputs -->
      <div class="w-11/12 lg:w-3/4 mx-auto">
         <x-jet-input class="w-full placeholder:text-xs input" wire:model="search" type="text" name="search" placeholder="Escribe el nombre del producto" />
      </div>
      <div class="w-3/4 mx-auto flex justify-between pt-8">
         <div>
            <span class="mr-2 text-sm dark:text-gray-400">Ver</span>
            <x-select class="mt-2 w-3/4 input" wire:model="filter_family" :options="$families"
                default="Todas las familias" />
        </div>
        <div>
            <span class="mr-2 text-sm dark:text-gray-400">Mostrar</span>
            <select class="mt-2 input" wire:model="elements">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
      </div>

      <!-- banner -->
      <div x-data="{open: true}" x-show="open" class="w-11/12 flex justify-between mx-auto bg-orange-100 rounded-lg p-4 my-6 text-sm font-medium text-orange-700" role="alert">
         <div class="w-11/12 flex">
            <i class="fas fa-exclamation-circle w-5 h-5 inline mr-3"></i>
            <div>
               <span class="font-extrabold">¡Solo productos simples!</span>
               En esta sección se registran productos simples como medallones, llaveros, portaplacas metalicos sin emblemas, etc.
               Si quiere registrar un producto que sea combinación de varios productos simples vaya a <a class="hover:cursor-pointer underline" href="{{ route('composit-products') }}">productos compuestos</a>
            </div>
         </div>

         <i @click="open = false" class="fal fa-times text-right hover:cursor-pointer"></i>
      </div>

      <!-- table -->
      <x-table :models="$products" :sort="$sort" :direction="$direction" :columns="$table_columns">
         <x-slot name="body">
            @foreach( $products as $item )
            <tr>
               <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                     {{$item->id}}
                  </p>
               </td>
               <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  <x-product-quick-view :image="$item->image" :name="$item->name" :nameBolded="false" />
               </td>
               <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">{{$item->material->name}}</p>
               </td>
               <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  <p class="text-gray-900 whitespace-no-wrap dark:text-gray-400">
                     {{$item->family->name}}
                  </p>
               </td>
               <td class="px-3 py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  @if($item->product_status_id == 1)
                  <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                     <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                     <span class="relative">{{$item->status->name}}</span>
                  </span>
                  @elseif($item->product_status_id == 2)
                  <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                     <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                     <span class="relative">{{$item->status->name}}</span>
                  </span>
                  @else
                  <span class="relative inline-block px-3 py-1 font-semibold text-amber-800 leading-tight">
                     <span aria-hidden class="absolute inset-0 bg-amber-200 opacity-50 rounded-full"></span>
                     <span class="relative">{{$item->status->name}}</span>
                  </span>
                  @endif
               </td>
               <td class="w-28 px-px py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
                  @can('ver_productos')
                  <i wire:click="show({{ $item }})" class="far fa-eye table-btn hover:text-sky-400"></i>
                  @endcan
                  @can('editar_productos')
                  <i wire:click="edit({{ $item }})" class="far fa-edit table-btn hover:text-blue-500"></i>
                  @endcan
                  @can('eliminar_productos')
                  <i wire:click="$emit('confirm', { 0:'products.products', 1:'delete' ,2:{{$item->id}}, 3:'Este proceso no se puede revertir' })" class="fas fa-trash table-btn hover:text-red-500"></i>
                  @endcan
               </td>
               <td class="py-3 border-b dark:bg-slate-700 dark:text-gray-400 border-gray-200 bg-white">
               </td>
            </tr>
            @endforeach
         </x-slot>
      </x-table>

      <!-- show modal -->
      @livewire('products.show-product')

      <!-- edit modal -->
      @livewire('products.edit-product')

      {{-- others --}}
      @livewire('product-material.create-product-material')
      @livewire('product-family.create-product-family')
      @livewire('product-status.create-product-status')
      @livewire('measurement-unit.create-measurement-unit')

   </div>

</div>