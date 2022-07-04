<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderReceivedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public
        $purchase_order,
        $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PurchaseOrder $purchase_order)
    {
        $this->purchase_order = $purchase_order;
        $this->subject = "Recibida orden de compra OC-" . str_pad($purchase_order->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.purchase-order-received');
    }
}
