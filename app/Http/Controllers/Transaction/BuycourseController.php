<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class BuycourseController extends Controller
{

    public function buy_course(Course $course, StripeClient $stripeClient){
        $paymentIntent = $stripeClient->paymentIntents->create([
            'country' => 'US',
            'curency' => 'USD',

        ]);
    }
}
