<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Models\SellOrder;
use App\Models\User;

class FormatController extends Controller
{
    public function vacationsReceipt($user)
    {
        $user = User::find($user);

        return view('formats.vacations-receipt', compact('user'));
    }

    public function qualityCertificate($sell_order)
    {
        $sell_order = SellOrder::find($sell_order);
        $organization = Organization::find(1);

        return view('formats.quality-certificate', [
            'sell_order' => $sell_order,
            'organization' => $organization,
        ]);
    }

    public function sellOrder($sell_order)
    {
        $sell_order = SellOrder::find($sell_order);
        
        return view('formats.sell-order', compact('sell_order'));
    }

    public function purchaseOrder($purchase_order)
    {
        $purchase_order = PurchaseOrder::find($purchase_order);
        $organization = Organization::find(1);

        return view('formats.purchase-order', [
            'purchase_order' => $purchase_order,
            'organization' => $organization,
        ]);
    }
}
