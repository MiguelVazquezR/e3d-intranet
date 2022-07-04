<?php

namespace App\Mail;

use App\Models\SellOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellOrderShippedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public
        $sell_order,
        $packages_shipped,
        $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SellOrder $sell_order, $packages_shipped = null)
    {
        $this->sell_order = $sell_order;
        $this->subject = "En camino orden de venta OV-" . str_pad($sell_order->id, 4, '0', STR_PAD_LEFT);
        $this->packages_shipped = $packages_shipped;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.sell-order-shipped');
    }
}
