<?php

use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\User\UserController;
use App\Models\Course;
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
Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'Register'])->name('register');
    Route::get('/register', [UserController::class, 'ShowRegisterForm'])->name('ShowRegisterForm');

    Route::get('/login', [UserController::class, 'ShowLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'Login'])->name('login')->middleware(['throttle:login']);


    //password fogot

    Route::get('/forgot-password', fn () => view('Components/ForgotPassword'))->name('forgot.password.form');
    Route::post('/forgot-password', [UserController::class, 'forgot_password'])->name('forgot.password')->middleware('throttle:2,1');
    Route::get('/reset-password/{email}/{token}', fn ($email, $token) => view('/Components/PasswordReset', ['token' => $token, 'email' => $email]))->name('password.reset');
    Route::post('/reset-password', [UserController::class, 'passwordReset'])->name('password.update');
});

Route::middleware(['auth:web'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/show/update', [UserController::class, 'ShowUserUpdateForm']);
        Route::put('/update', [UserController::class, 'Update'])->name('user.update');
        Route::put('/update/password', [UserController::class, 'changePassword'])->name('user.update.password');
        Route::put('/update/email', [UserController::class, 'changeEmail'])->middleware(['throttle:4,1'])->name('user.update.email');
        Route::get('/update/email/{code}', fn ($code) => view('Components/ChangeEmail')->with('code', $code))->name('user.update.email.form');
        Route::get('/logout', [UserController::class, 'Logout'])->name('logout');
    });

    Route::prefix('sendmail')->group(function () {
        Route::get('/verify', [UserController::class, 'sendEmailVerificationMail'])->name('sendmail.verify');
    });

    Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verifyEmail'])->name('verification.verify');


    //only for verified user

    Route::middleware('verified')->group(function(){
        //create course
        Route::post('/create/course',[CourseController::class, 'createCourse'])->name('create.course');
        Route::get('/create/course',fn() => view('pages/course/Create'));

        //update course
        Route::get('/update/course/{course}', [CourseController::class, 'Show_UpdateDetails']);
        Route::post('/update/course/{course}',[CourseController::class,'updateDetails'])->name('update.course');
        Route::post('/update/course/{course}/thumblin',[CourseController::class,'setThumblin'])->name('update.course.thumblin');
        Route::post('/update/course/{course}/introduction',[CourseController::class,'setIntroduction'])->name('update.course.introduction');

        //course tutorial
        Route::post('/course/{course}/add-video/',[CourseController::class,'addVideo'])->name('course.addVideo');
        Route::put('/course/{course}/add-video/{video}',[CourseController::class,'setVideoName'])->name('course.setVideoName');
        Route::delete('/course/{course}/delete/video/{video}',[CourseController::class,'deleteVideo'])->name('course.deleteVideo');

        Route::delete('/course/{course}/delete',[CourseController::class, 'deleteCourse'])->name('course.delte');
    });
});
