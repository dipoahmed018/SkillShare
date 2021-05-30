<?php

use App\Http\Controllers\Forum\ForumController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/forum', [ForumController::class, 'index']);
ROute::get('/test/query',function()
{
    $course = Course::find(6);
    $course->tutorial= $course->get_tutorials_details();
    return $course;
});