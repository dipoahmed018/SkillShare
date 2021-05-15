<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//root routes
Route::redirect('/','/dashboard');
Route::view('/dashboard','pages/Dashboard');


//user interection routes
Route::get('/show/register',[UserController::class,'ShowRegisterForm']);
Route::post('/register',[UserController::class,'Register'])->name('register');
Route::get('/show/login',[UserController::class,'ShowLoginForm']);
Route::post('/login',[UserController::class,'Login'])->name('login')->middleware(['throttle:login']);
Route::get('/logout',[UserController::class,'Logout'])->name('logout');
Route::middleware(['auth:web'])->group(function(){
    Route::put('/user/update',[UserController::class,'Update'])->name('user.update');
});