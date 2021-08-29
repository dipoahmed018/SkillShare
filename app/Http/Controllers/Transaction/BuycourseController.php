<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Stripe\StripeClient;

class BuycourseController extends Controller
{

    public function show_payment_methods(StripeClient $stripeClient, Course $product)
    {
        $paymentIntent = $stripeClient->paymentIntents->create([
            'currency' => 'USD',
            'amount' => 500,
        ]);
        return view('pages.transaction.methods',['intent' => $paymentIntent]);
    }
}
