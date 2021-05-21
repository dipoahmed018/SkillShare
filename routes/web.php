<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Log;
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
Route::redirect('/', '/dashboard');
Route::view('/dashboard', 'pages/Dashboard');


//user interection routes
Route::get('/show/register', [UserController::class, 'ShowRegisterForm']);
Route::post('/register', [UserController::class, 'Register'])->name('register');
Route::get('/show/login', [UserController::class, 'ShowLoginForm']);
Route::post('/login', [UserController::class, 'Login'])->name('login')->middleware(['throttle:login']);
Route::middleware(['auth:web'])->group(function () {
    
    Route::prefix('user')->group(function () {
        Route::get('/show/update', [UserController::class, 'ShowUserUpdateForm']);
        Route::put('/update', [UserController::class, 'Update'])->name('user.update');
        Route::put('/update/password', [UserController::class, 'changePassword'])->name('user.update.password');
        Route::put('/update/email', [UserController::class, 'changeEmail'])->middleware(['throttle:4,1'])->name('user.update.email');
        Route::get('/update/email/{code}', fn($code) => view('Components/ChangeEmail')->with('code',$code))->name('user.update.email.form');
        Route::get('/logout', [UserController::class, 'Logout'])->name('logout');
        
    });
    
    Route::prefix('sendmail')->group(function(){
        Route::get('/verify', [UserController::class, 'sendEmailVerificationMail'])->name('sendmail.verify');
        
    });

    Route::get('/email/verify/{id}/{hash}',[UserController::class, 'verifyEmail'])->name('verification.verify');
});