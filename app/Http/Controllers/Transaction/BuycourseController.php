<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class BuycourseController extends Controller
{

    private $stripeClient;
    function __construct(StripeClient $stripeClient)
    {
        $this->stripeClient = $stripeClient;
    }

    public function show_payment_methods(Course $product)
    {
        try {
            $payment_intent = $this->stripeClient->paymentIntents->create([
                'currency' => 'USD',
                'amount' => 500,
            ]);
            OrderDetails::create([
                'payment_intent' => $payment_intent->id,
                'client_sc' => $payment_intent->client_secret,
                'status' => false,
                'owner' => Auth::user()->id,
                'productable_id' => $product->id,
                'productable_type' => 'course',
            ]);
            return view('pages.transaction.methods',['client_sc' => $payment_intent->client_secret, 'product' => $product]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function confirm_purchase(Request $request)
    {
        $request->validate([
            'client_sc' => 'required|string'
        ]);
        $order_details = OrderDetails::where('client_sc',$request->client_sc)->first();
        try {
            $payment_intent = $this->stripeClient->paymentIntents->retrieve($order_details->payment_intent);
            if ($payment_intent->status == "succeeded") {
                $course = $order_details->course;
                $order_details->status = 1;
                $course->members()->syncWithoutDetaching([Auth::user()->id]);
                $order_details->save();
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
