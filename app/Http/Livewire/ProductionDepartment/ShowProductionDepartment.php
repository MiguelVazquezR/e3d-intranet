<?php

namespace App\Http\Livewire\ProductionDepartment;

use App\Models\SellOrder;
use App\Models\UserHasSellOrderedProduct;
use Carbon\Carbon;
use Livewire\Component;

class ShowProductionDepartment extends Component
{
    public $sell_order,
        $open = false,
        $active_tab = 1;

    protected $listeners = [
        'openModal',
    ];

    public function mount()
    {
        $this->sell_order = new SellOrder();
        $this->sell_order->customer_id = 1;
        $this->sell_order->user_id = 1;
        $this->sell_order->contact_id = 1;
    }

    public function openModal(SellOrder $sell_order)
    {
        $this->sell_order = $sell_order;
        $this->open = true;
    }

    public function refreshActivities()
    {
        $this->sell_order = SellOrder::find($this->sell_order->id);
    }

    public function start(UserHasSellOrderedProduct $activity)
    {
        $activity->update([
            'start' => date('Y-m-d H:i:s')
        ]);

        if ($activity->sellOrderedProduct->status == "Sin iniciar") {
            $activity->sellOrderedProduct->update([
                'status' => 'En proceso'
            ]);
        }

        $this->emit('success', 'Se ha marcado el inicio de tus actividades');
        $this->refreshActivities();
    }

    public function pause(UserHasSellOrderedProduct $activity)
    {
        $activity->update([
            'pause' => date('Y-m-d H:i:s')
        ]);

        $this->emit('success', 'Se ha marcado un paro en tus actividades');
        $this->refreshActivities();
    }

    public function continue(UserHasSellOrderedProduct $activity)
    {
        //calculate how much minutes activities was paused
        $pause = new Carbon($activity->pause);
        $time_paused = $pause->diffInMinutes();

        $activity->time_paused = $activity->time_paused + $time_paused;
        $activity->pause = null;
        $activity->save();

        $this->emit('success', "Se ha marcado la continuación de tus actividades. Pausaste por $time_paused minutos");
        $this->refreshActivities();
    }

    public function getStatus($finished, $all)
    {
        $percentage_finished = $finished / $all * 100;
        if ($finished == $all) {
            $status = 'Terminado';
        } else {
            $status = 'En proceso (' . round($percentage_finished) . '%)';
        }
        return $status;
    }

    public function finish(UserHasSellOrderedProduct $activity)
    {
        $activity->update([
            'finish' => date('Y-m-d H:i:s')
        ]);

        //set sell ordered product status
        $current_product_activities =
            UserHasSellOrderedProduct::where('sell_ordered_product_id', $activity->sell_ordered_product_id)
            ->get()
            ->count();
        $current_product_finished_activities =
            UserHasSellOrderedProduct::where('sell_ordered_product_id', $activity->sell_ordered_product_id)
            ->whereNotNull('finish')
            ->get()
            ->count();

        $activity->sellOrderedProduct->update([
            'status' => $this->getStatus($current_product_finished_activities, $current_product_activities)
        ]);


        //set sell order status
        $sell_order = $activity->sellOrderedProduct->sellOrder;

        $finished_activities = 0;
        $all_activities = 0;
        foreach ($sell_order->sellOrderedProducts as $s_o_p) {
            foreach ($s_o_p->activityDetails as $activity) {
                $all_activities++;
                if ($activity->finish) {
                    $finished_activities++;
                }
            }
        }

        $activity->sellOrderedProduct->sellOrder->update([
            'status' => $this->getStatus($finished_activities, $all_activities)
        ]);

        $this->emit('success', 'Se ha marcado el final de tus actividades');
        $this->emitTo('production-department.home-production', 'render');
        $this->refreshActivities();
    }

    public function render()
    {
        return view('livewire.production-department.show-production-department');
    }
}
