<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\DesignOrder;
use App\Models\Employee;
use App\Models\Meeting;
use App\Models\MovementHistory;
use App\Models\PurchaseOrder;
use App\Models\Quote;
use App\Models\SellOrder;
use App\Models\ShippingPackage;
use App\Models\StockProduct;
use App\Models\UserHasSellOrderedProduct;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboards extends Component
{

    public $quotes_for_authorize,
        $sell_orders_for_authorize,
        $purchase_orders_for_authorize,
        $design_orders_for_authorize,
        $so_to_start,
        $low_stock,
        $created_histories,
        $edited_histories,
        $deleted_histories,
        $production_to_start,
        $meetings_as_participant,
        $meetings_as_creator,
        $packages_for_shipping,
        $soon_birthdays = [],
        $soon_customers_birthdays = [],
        $anniversaries = [],
        $new_employees = [],
        $design_to_start;

    public function mount()
    {
        $this->quotes_for_authorize = Quote::whereNull('authorized_user_id')->get();
        $this->sell_orders_for_authorize = SellOrder::whereNull('authorized_user_id')->get();
        $this->purchase_orders_for_authorize = PurchaseOrder::whereNull('authorized_user_id')->get();
        $this->design_orders_for_authorize = DesignOrder::whereNull('authorized_user_id')->get();
        $this->so_to_start = SellOrder::where('status', 'Autorizado. Asignar tareas')->get();
        $this->packages_for_shipping = ShippingPackage::where('status', 'Prepando para envío')->get();
        $this->created_histories = MovementHistory::where('movement_type', 1)->latest()->take(15)->get();
        $this->edited_histories = MovementHistory::where('movement_type', 2)->latest()->take(15)->get();
        $this->deleted_histories = MovementHistory::where('movement_type', 3)->latest()->take(15)->get();
        $this->meetings_as_creator = Meeting::where('user_id', Auth::user()->id)
            ->where('status', 'Esperando inicio')
            ->orWhere('status', 'Iniciada')
            ->latest()->get();

        $this->meetings_as_participant = Meeting::whereIn('status', ['Esperando inicio', 'Iniciada'])
            ->whereHas('participants', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })->latest()->get();

        $this->production_to_start =
            UserHasSellOrderedProduct::where('user_id', Auth::user()->id)
            ->whereNull('start')
            ->get();

        $this->design_to_start =
            DesignOrder::where('user_id', Auth::user()->id)
            ->whereNull('tentative_end')
            ->whereNotNull('authorized_user_id')
            ->get();

        // employees
        $items = Employee::all();
        foreach ($items as $employee) {
            // birthdays
            if ($employee->HasBirthdaySoon())
                $this->soon_birthdays[] = [
                    'employee' => $employee,
                    'days' => $employee->getDaysToBirthday()
                ];

            // anniversaries
            if ($employee->HasAnniversarySoon() && $employee->joinedYears() >= 1)
                $this->anniversaries[] = $employee;

            // new employees
            if ($employee->joinedDays() <= 3)
                $this->new_employees[] = $employee;
        }

        // customer contacts birthdays
        $items = Customer::all();
        foreach ($items as $customer) {
            $contacts = $customer->contacts;
            foreach ($contacts as $contact) {
                if ($contact->HasBirthdaySoon())
                    $this->soon_customers_birthdays[] = [
                        "contact" => $contact,
                        "customer" => $customer,
                        "days" => $contact->getDaysToBirthday()
                    ];
            }
        }

        // low_stock
        $this->low_stock = collect();
        $items = StockProduct::all();
        foreach ($items as $stock_product) {
            if ($stock_product->needsReposition())
                $this->low_stock->add($stock_product);
        }

        foreach ($this->meetings_as_creator as $meeting) {
            $meeting->changeStatus();
        }

        foreach ($this->meetings_as_participant as $meeting) {
            $meeting->changeStatus();
        }
    }

    public function showDetails($view)
    {
        $this->emitTo('common.detail-modal', 'openModal', $view);
    }

    public function render()
    {
        return view('livewire.dashboard.dashboards');
    }
}
