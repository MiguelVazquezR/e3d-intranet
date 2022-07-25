<?php

namespace App\Http\Livewire\Quote;

use App\Models\CompositProduct;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteCompositProduct;
use App\Models\QuoteProduct;
use App\Models\User;
use App\Notifications\RequestApproved;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditQuote extends Component
{
    public $open = false,
        $price,
        $quantity,
        $show_image = 1,
        $edit_index = null,
        $new_customer = 1,
        $simple_product = 0,
        $customer,
        $product_notes;

    public $quote,
        $selected_product,
        $products_list = [],
        $temporary_product_deleted_list = [],
        $temporary_composit_deleted_list = [],
        $tooling_currency,
        $freight_currency;

    protected $listeners = [
        'render',
        'openModal',
        'selected-product' => 'selectedProduct',
        'selected-composit-product' => 'selectedCompositProduct',
        'selected-customer' => 'selectedCustomer',
    ];

    protected $rules = [
        'quote.receiver' => 'required',
        'quote.department' => 'required',
        'quote.first_production_days' => 'required',
        'quote.tooling_cost' => 'required',
        'quote.freight_cost' => 'required',
        'quote.currency_id' => 'required',
        'quote.strikethrough_tooling_cost' => 'min:0',
        'quote.notes' => 'max:255',
        'quote.customer_name' => 'max:150',
        'customer' => 'required',
    ];

    protected $quote_product_rules = [
        'price' => 'required',
        'quantity' => 'required',
        'product_notes' => 'max:120',
        'quote.currency_id' => 'required',
    ];


    public function mount()
    {
        $this->quote = new Quote();
    }

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
                'quote',
            ]);
        }
    }

    public function updatedSimpleProduct()
    {
        $this->reset('selected_product');
    }

    public function openModal(Quote $quote)
    {
        $this->quote = $quote;
        $this->open = true;
        $this->quote->strikethrough_tooling_cost =
            intval($this->quote->strikethrough_tooling_cost);
        foreach ($this->quote->quotedProducts as $q_product) {
            $this->products_list[] = $q_product->toArray();
        }
        foreach ($this->quote->quotedCompositProducts as $q_product) {
            $this->products_list[] = $q_product->toArray();
        }
        $this->tooling_currency = $this->quote->toolingCurrency();
        $this->freight_currency = $this->quote->freightCurrency();
        $this->quote->tooling_cost = explode('$', $this->quote->tooling_cost)[0];
        $this->quote->freight_cost = explode('$', $this->quote->freight_cost)[0];

        $this->customer = Customer::find($quote->customer_id);
        if ($this->customer) {
            $this->new_customer = 0;
        }
    }

    public function selectedCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function authorize()
    {
        $this->quote->authorized_user_id = Auth::user()->id;
        $this->quote->save();

        // User::findOrFail($this->quote->user_id)->notify( new RequestApproved('cotización',$this->quote->id) );

        $this->resetExcept([
            'quote',
        ]);

        $this->emit('success', 'Cotización autorizada.');
        $this->emitTo('quote.quotes', 'render');
    }

    public function update()
    {
        if (!$this->new_customer) {
            unset($this->rules['quote.customer_name']);
        } else {
            unset($this->rules['customer']);
        }

        $this->validate(null, [
            'products_list.required' => 'Debe de haber mínimo un producto
             para cotizar',
        ]);

        if (!$this->new_customer) {
            $this->quote->customer_id = $this->customer->id;
            $this->quote->customer_name = null;
        } else {
            $this->quote->customer_id = null;
        }

        // add currencies to freight and tooling
        $this->quote->freight_cost .= $this->freight_currency;
        $this->quote->tooling_cost .= $this->tooling_currency;

        $this->quote->save();

        // create movement history
        MovementHistory::create([
            'movement_type' => 2,
            'user_id' => Auth::user()->id,
            'description' => "Se editó cotización con ID {$this->quote->id}"
        ]);

        // create new quoted products and update olds
        foreach ($this->products_list as $quote_product) {
            if (array_key_exists('id', $quote_product)) {
                if (array_key_exists('product_id', $quote_product)) {
                    QuoteProduct::find($quote_product["id"])
                        ->update($quote_product);
                } else {
                    QuoteCompositProduct::find($quote_product["id"])
                        ->update($quote_product);
                }
            } else {
                $quote_product["quote_id"] = $this->quote->id;
                if (array_key_exists('product_id', $quote_product)) {
                    QuoteProduct::create($quote_product);
                } else {
                    QuoteCompositProduct::create($quote_product);
                }
            }
        }

        // delete old products on temporary list
        QuoteProduct::whereIn('id', $this->temporary_product_deleted_list)->delete();
        QuoteCompositProduct::whereIn('id', $this->temporary_composit_deleted_list)->delete();

        $this->resetExcept([
            'quote',
        ]);

        $this->emit('success', 'Cotización actualizada.');
    }

    public function toggleStrikeThrough()
    {
        if ($this->quote->strikethrough_tooling_cost)
            $this->quote->strikethrough_tooling_cost = 1;
        else
            $this->quote->strikethrough_tooling_cost = 0;
    }

    // ------------ Quote products functions ------------
    public function selectedProduct(Product $selection)
    {
        if ($this->open) {
            $this->selected_product = $selection;
            $this->reset('edit_index');
        }
    }

    public function selectedCompositProduct(CompositProduct $selection)
    {
        if ($this->open) {
            $this->selected_product = $selection;
            $this->reset('edit_index');
        }
    }

    public function addProductToList()
    {
        $validated_data = $this->validate($this->quote_product_rules);

        if ($this->selected_product instanceof Product) {
            $quote_product = new QuoteProduct(
                [
                    'product_id' => $this->selected_product->id,
                    'show_image' => $this->show_image,
                    'notes' => $this->product_notes,
                ] + $validated_data
            );
        } else {
            $quote_product = new QuoteCompositProduct(
                [
                    'composit_product_id' => $this->selected_product->id,
                    'show_image' => $this->show_image,
                    'notes' => $this->product_notes,
                ] + $validated_data
            );
        }

        $this->products_list[] = $quote_product->toArray();

        $this->resetProduct();
    }

    public function updateProductFromList()
    {
        $validated_data = $this->validate($this->quote_product_rules);

        if (array_key_exists('id', $this->products_list[$this->edit_index])) {
            if ($this->selected_product instanceof Product) {
                $quote_product = new QuoteProduct(
                    [
                        'id' => $this->products_list[$this->edit_index]["id"],
                        'product_id' => $this->selected_product->id,
                        'show_image' => $this->show_image,
                        'notes' => $this->product_notes,
                    ] + $validated_data
                );
            } else {
                $quote_product = new QuoteCompositProduct(
                    [
                        'id' => $this->products_list[$this->edit_index]["id"],
                        'composit_product_id' => $this->selected_product->id,
                        'show_image' => $this->show_image,
                        'notes' => $this->product_notes,
                    ] + $validated_data
                );
            }
        } else {
            if ($this->selected_product instanceof Product) {
                $quote_product = new QuoteProduct(
                    [
                        'product_id' => $this->selected_product->id,
                        'show_image' => $this->show_image,
                        'notes' => $this->product_notes,
                    ] + $validated_data
                );
            } else {
                $quote_product = new QuoteCompositProduct(
                    [
                        'composit_product_id' => $this->selected_product->id,
                        'show_image' => $this->show_image,
                        'notes' => $this->product_notes,
                    ] + $validated_data
                );
            }
        }

        $this->products_list[$this->edit_index] = $quote_product->toArray();

        $this->resetProduct();
    }

    public function resetProduct()
    {
        $this->reset([
            'price',
            'quantity',
            'product_notes',
            'selected_product',
            'show_image',
            'edit_index',
        ]);
    }

    public function editItem($index)
    {
        if (array_key_exists("product_id", $this->products_list[$index])) {
            $this->selected_product =
                Product::find($this->products_list[$index]["product_id"]);
        } else {
            $this->selected_product =
                CompositProduct::find($this->products_list[$index]["composit_product_id"]);
        }
        $this->quantity = $this->products_list[$index]["quantity"];
        $this->price = $this->products_list[$index]["price"];
        $this->show_image = $this->products_list[$index]["show_image"];
        $this->product_notes = $this->products_list[$index]["notes"];
        $this->edit_index = $index;
    }

    // public function removeFromTemporaryDeletedList($id)
    // {
    //     $index = array_search($id, $this->temporary_deleted_list);
    //     unset($this->temporary_deleted_list[$index]);
    // }

    public function deleteItem($index)
    {
        if (array_key_exists('id', $this->products_list[$index])) {
            if (array_key_exists('product_id', $this->products_list[$index])) {
                $this->temporary_product_deleted_list[] = $this->products_list[$index]["id"];
            } else {
                $this->temporary_composit_deleted_list[] = $this->products_list[$index]["id"];
            }
        } else {
            unset($this->products_list[$index]);
        }
    }

    // -----------------------------------------------


    public function render()
    {
        return view('livewire.quote.edit-quote', [
            'currencies' => Currency::all(),
        ]);
    }
}
