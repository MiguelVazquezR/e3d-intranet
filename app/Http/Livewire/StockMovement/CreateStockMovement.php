<?php

namespace App\Http\Livewire\StockMovement;

use App\Models\StockActionType;
use App\Models\StockMovement;
use App\Models\StockProduct;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateStockMovement extends Component
{
    public $stock_action_type_id,
        $open = false,
        $quantity,
        $movement = 'entrada',
        $stock_product,
        $notes;

    protected $rules = [
        'stock_action_type_id' => 'required',
    ];

    protected $listeners = [
        'render',
        'openModal',
    ];

    public function mount()
    {
        //initial values to avoid error at 1st rendering 
        // (showing measurement unit)
        $this->stock_product = new StockProduct();
        $this->stock_product->product_id = 1;
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open', 
                'stock_product',
            ]);
        }
    }

    public function openModal($data)
    {
        if ($data[0] == 'entrada') {
            $this->movement = 'entrada';
        } else {
            $this->movement = 'salida';
        }

        $this->stock_product = StockProduct::find($data[1]);

        $this->open = true;
    }

    public function store()
    {
        if ($this->movement == 'entrada') {
            $this->rules['quantity'] = "required";
        } else {
            $this->rules['quantity'] = "required|numeric|max:{$this->stock_product->quantity}";
        }

        $this->validate();

        StockMovement::create([
            'user_id' => Auth::user()->id,
            'stock_product_id' => $this->stock_product->id,
            'stock_action_type_id' => $this->stock_action_type_id,
            'quantity' => $this->quantity,
            'notes' => $this->notes,
        ]);

        if ($this->movement == 'entrada') {
            $this->stock_product->quantity += $this->quantity;
        } else {
            $this->stock_product->quantity -= $this->quantity;
        }

        $this->stock_product->save();

        $this->resetExcept(['stock_product']);

        $this->emitTo('stock.edit-stock', 'refresh-movements');
        $this->emit('success', 'Nuevo movimiento registrado');
    }

    public function render()
    {
        if ($this->movement == 'salida') {
            $stock_action_types = StockActionType::where('movement', '0')->get();
        } else {
            $stock_action_types = StockActionType::where('movement', '1')->get();
        }

        return view('livewire.stock-movement.create-stock-movement', [
            'stock_action_types' => $stock_action_types,
        ]);
    }
}
