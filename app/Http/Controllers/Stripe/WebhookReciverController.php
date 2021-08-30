<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookReciverController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::channel('event')->info($request->all());
        return 'helo';
    }
}
