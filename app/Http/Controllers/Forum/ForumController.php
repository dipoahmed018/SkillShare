<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Message;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $response = Message::find(1)->user();
        return response($response, 200);
    }
}
