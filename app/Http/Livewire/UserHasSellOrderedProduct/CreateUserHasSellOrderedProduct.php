<?php

namespace App\Http\Livewire\UserHasSellOrderedProduct;

use App\Models\SellOrderedProduct;
use App\Models\User;
use App\Models\UserHasSellOrderedProduct;
use Livewire\Component;

class CreateUserHasSellOrderedProduct extends Component
{
    public $open = false,
        $user_id,
        $indications,
        $estimated_time,
        $edit_index,
        $activities_detail_list = [],
        $temporary_deleted_list = [],
        $sell_ordered_product;

    protected $rules = [
        'user_id' => 'required',
        'indications' => 'required',
        'estimated_time' => 'required|numeric',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'sell_ordered_product'
            ]);
        }
    }

    public function openModal(SellOrderedProduct $sell_ordered_product, $has_operators)
    {
        $this->open = true;
        $this->sell_ordered_product = SellOrderedProduct::find($sell_ordered_product['id']);

        if ($has_operators) {
            foreach ($this->sell_ordered_product->activityDetails as $activities_detail) {
                $this->activities_detail_list[] = $activities_detail->toArray();
            }
        }
    }

    public function addItemToList()
    {
        $validated_data = $this->validate();

        $activities_detail = new UserHasSellOrderedProduct(
            ['sell_ordered_product_id' => $this->sell_ordered_product->id]
                + $validated_data
        );

        $this->activities_detail_list[] = $activities_detail;

        $this->resetItem();
    }

    public function updateItem()
    {
        $validated_data = $this->validate();
        
        $aditional_data = [
            'sell_ordered_product_id' => $this->sell_ordered_product->id
        ];
        // add id key to avoid duplicates when editing registered
        if( array_key_exists('id', $this->activities_detail_list[$this->edit_index]) ) {
            $aditional_data['id'] = $this->activities_detail_list[$this->edit_index]["id"];
        }
        
        $activities_detail = $aditional_data + $validated_data;
        
        $this->activities_detail_list[$this->edit_index] = $activities_detail;
        
        $this->resetItem();
    }

    public function resetItem()
    {
        $this->reset([
            'user_id',
            'indications',
            'estimated_time',
            'edit_index',
        ]);
    }

    public function editItem($index)
    {
        $this->edit_index = $index;
        $this->user_id = $this->activities_detail_list[$index]["user_id"];
        $this->indications = $this->activities_detail_list[$index]["indications"];
        $this->estimated_time = $this->activities_detail_list[$index]["estimated_time"];
    }

    public function addToTemporaryDeletedList($id)
    {
        $this->temporary_deleted_list[] = $id;
    }

    public function removeFromTemporaryDeletedList($id)
    {
        $index = array_search($id, $this->temporary_deleted_list);
        unset($this->temporary_deleted_list[$index]);
    }

    // public function deleteSellOrderedProduct($index)
    // {
    //     if (array_key_exists('id', $this->sell_ordered_products_list[$index])) {
    //         $this->addToTemporaryDeletedList($this->sell_ordered_products_list[$index]["id"]);
    //     } else {
    //         unset($this->sell_ordered_products_list[$index]);
    //     }
    // }

    public function deleteItem($index)
    {
        if (array_key_exists('id', $this->activities_detail_list[$index])) {
            $this->addToTemporaryDeletedList($this->activities_detail_list[$index]["id"]);
        } else {
            unset($this->activities_detail_list[$index]);
        }
    }

    public function store()
    {
        $this->validate(
            ['activities_detail_list' => 'required'],
            ['activities_detail_list.required' => 'Agregue por lo menos a un operador']
        );

        // create all assigned operators to ordered product and update olds
        foreach ($this->activities_detail_list as $activities) {
            if (array_key_exists('id', $activities)) {
                UserHasSellOrderedProduct::find($activities["id"])
                    ->update($activities);
            } else {
                UserHasSellOrderedProduct::create($activities);
            }
        }

        if ($this->sell_ordered_product->sellOrder->status == 'Esperando autorización') {
            $this->sell_ordered_product->sellOrder->status = 'Sin iniciar';
            $this->sell_ordered_product->sellOrder->save();
        }


        if (!empty($this->temporary_deleted_list)) {
            // delete old activities on temporary list
            UserHasSellOrderedProduct::whereIn('id', $this->temporary_deleted_list)->delete();
        }

        $this->resetExcept([
            'sell_ordered_product'
        ]);

        $this->emitTo('sell-order.edit-sell-order', 'render');
        $this->emitTo('sell-order.sell-orders', 'render');
        $this->emit('success', 'Operador(es) asignados');
    }

    public function render()
    {
        $operators = User::role('Auxiliar_producción')
        ->where('active', 1)
        ->get();

        return view('livewire.user-has-sell-ordered-product.create-user-has-sell-ordered-product', [
            'operators' => $operators
        ]);
    }
}
