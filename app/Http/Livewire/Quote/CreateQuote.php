<?php

namespace App\Http\Livewire\Quote;

use App\Mail\ApproveMailable;
use App\Models\CompositProduct;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\MovementHistory;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteCompositProduct;
use App\Models\QuoteProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateQuote extends Component
{
    use WithFileUploads;

    public $open = false,
        $first_production_days = '3 a 4 semanas',
        $currency_id,
        $tooling_currency,
        $freight_currency,
        $customer_name,
        $customer,
        $receiver,
        $department,
        $tooling_cost,
        $strikethrough_tooling_cost = 0,
        $notes,
        $freight_cost,
        $price,
        $quantity,
        $selected_product,
        $products_list = [],
        $edit_index = null,
        $show_image = 1,
        $new_customer = 1,
        $template_language = 1,
        $simple_product = 0,
        $product_notes,
        $images = [];
    // $aditional_images = [];

    protected $rules = [
        'receiver' => 'required|max:60',
        'department' => 'required|max:60',
        'first_production_days' => 'required|max:70',
        'tooling_cost' => 'required|max:56',
        'freight_cost' => 'required|max:56',
        'currency_id' => 'required',
        'strikethrough_tooling_cost' => 'numeric|min:0',
        'products_list' => 'required',
        'spanish_template' => 'required',
    ];

    protected $quote_product_rules = [
        'price' => 'required',
        'quantity' => 'required',
        'currency_id' => 'required',
        'product_notes' => 'max:255',
        'images.*' => 'mimes:jpg,png,jpeg,gif,svg'
    ];

    protected $listeners = [
        'render',
        'selected-product' => 'selectedProduct',
        'selected-composit-product' => 'selectedCompositProduct',
        'selected-customer' => 'selectedCustomer',
    ];

    public function updatingOpen()
    {
        if ($this->open == true) {
            $this->resetExcept([
                'open',
            ]);
        }
    }

    public function updatedSimpleProduct()
    {
        $this->reset('selected_product');
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function selectedCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function toggleStrikeThrough()
    {
        if ($this->strikethrough_tooling_cost)
            $this->strikethrough_tooling_cost = 1;
        else
            $this->strikethrough_tooling_cost = 0;
    }

    public function store()
    {
        if ($this->new_customer) {
            $this->rules['customer_name'] = 'required';
        } else {
            $this->rules['customer'] = 'required';
        }

        $validated_data = $this->validate(null, [
            'products_list.required' => 'Debe de haber mínimo un producto para cotizar'
        ]);

        // Add notes and creator to create the quote
        $aditional = [
            'user_id' => Auth::user()->id,
            'notes' => $this->notes ?? null,
        ];

        if (!$this->new_customer) {
            $aditional['customer_id'] = $this->customer->id;
        }

        // add currencies to freight and tooling
        $validated_data['freight_cost'] .= $this->freight_currency;
        $validated_data['tooling_cost'] .= $this->tooling_currency;

        $quote = Quote::create($validated_data + $aditional);

        // create quoted products
        foreach ($this->products_list as $quote_product) {
            $quote_product['product']["quote_id"] = $quote->id;
            if (array_key_exists('product_id', $quote_product['product'])) {
                $product = QuoteProduct::create($quote_product['product']);
            } else {
                $product = QuoteCompositProduct::create($quote_product['product']);
            }
            foreach($quote_product['aditional_images'] as $image_path) {
                $product->addMedia($image_path)->toMediaCollection();
            }
        }

        // create movement history
        MovementHistory::create([
            'movement_type' => 1,
            'user_id' => Auth::user()->id,
            'description' => "Se creó nueva cotización con ID {$quote->id}"
        ]);

        if (Auth::user()->can('autorizar_cotizaciones')) {
            $quote->authorized_user_id = Auth::user()->id;
            $quote->save();
        } else {
            // send email notification
            Mail::to('maribel@emblemas3d.com')
                // ->bcc('miguelvz26.mv@gmail.com')
                ->queue(new ApproveMailable('Cotización', $quote->id, Quote::class));
        }

        $this->reset();

        $this->emitTo('quote.quotes', 'render');
        $this->emit('success', 'Nueva cotización generada');
    }

    public function selectedProduct(Product $selection)
    {
        if ($this->open) {
            $this->selected_product = $selection;
        }
    }

    public function selectedCompositProduct(CompositProduct $selection)
    {
        if ($this->open) {
            $this->selected_product = $selection;
        }
    }

    public function addProductToList()
    {
        $validated_data = $this->validate($this->quote_product_rules);

        // save aditional images if they exist
        $aditional_images = [];
        foreach ($this->images as $image) {
            $aditional_images[] = $image->getRealPath();
        }

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

        $this->products_list[] = array('aditional_images' => $aditional_images, 'product' => $quote_product->toArray());
        $this->resetProduct();
    }

    public function editProductFromList()
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

        $this->products_list[$this->edit_index] = array('aditional_images' => [], 'product' => $quote_product->toArray());

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
        if (array_key_exists("product_id", $this->products_list[$index]['product'])) {
            $this->selected_product =
                Product::find($this->products_list[$index]['product']["product_id"]);
        } else {
            $this->selected_product =
                CompositProduct::find($this->products_list[$index]['product']["composit_product_id"]);
        }
        $this->quantity = $this->products_list[$index]['product']["quantity"];
        $this->price = $this->products_list[$index]['product']["price"];
        $this->show_image = $this->products_list[$index]['product']["show_image"];
        $this->product_notes = $this->products_list[$index]['product']["notes"];
        $this->edit_index = $index;
    }

    public function deleteItem($index)
    {
        unset($this->products_list[$index]);
    }

    public function render()
    {
        return view('livewire.quote.create-quote', [
            'currencies' => Currency::all(),
        ]);
    }
}
