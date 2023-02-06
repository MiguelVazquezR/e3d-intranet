<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Organization;
use App\Models\PayRoll;
use App\Models\Quote;
use App\Models\User;

class PdfController extends Controller
{
    public function quote($hash)
    {
        $quote_id = ($hash - 880) / 480 - 990;
        $quote = Quote::findOrFail($quote_id);

        if ($quote->spanish_template)
            return view('pdf.quote', compact('quote'));
        else
            return view('pdf.english-quote', compact('quote'));
    }

    public function payRoll($data)
    {
        $pay_roll = PayRoll::find(json_decode($data)[0]);
        if (is_array(json_decode($data)[1])) {
            $users = User::whereIn('id', json_decode($data)[1])->get();
        } else {
            $users = [User::find(json_decode($data)[1])];
        }

        $organization = Organization::find(1);

        return view('pdf.pay-roll', [
            'pay_roll' => $pay_roll,
            'users' => $users,
            'bonuses' => Bonus::all(),
            'organization' => $organization,
        ]);
    }
}
