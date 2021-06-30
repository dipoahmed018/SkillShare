<?php

use App\Models\Course;
use App\Models\Catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Forum\ForumController;
use App\Http\Controllers\Review\ReviewController;
use App\Models\Forum;

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

    Route::middleware('verified')->group(function () {
        //show course
        Route::get('/show/tutorial/{tutorial}/{course}', [CourseController::class, 'streamTutorial'])->name('show.tutorial');

        //create course
        Route::post('/create/course', [CourseController::class, 'createCourse'])->name('create.course');
        Route::get('/create/course', fn () => view('pages/course/Create'));

        //update course
        Route::get('/update/course/{course}', fn (Course $course) => view('pages/course/EditCourse', ['course' => $course, 'catagories' => Catagory::all()]));
        Route::put('/update/course/{course}', [CourseController::class, 'updateDetails'])->name('update.course');
        Route::post('/update/course/{course}/thumblin', [CourseController::class, 'setThumblin'])->name('update.course.thumblin');
        Route::post('/update/course/{course}/introduction', [CourseController::class, 'setIntroduction'])->name('update.course.introduction');

        //catagory
        Route::put('/update/course/{course}/catagory', [CourseController::class, 'attachCatagory'])->name('update.course.catagory');
        Route::delete('/delete/course/{course}/catagory', [CourseController::class, 'detachCatagory'])->name('delete.course.catagory');

        //course tutorial
        Route::post('/course/{course}/tutorial', [CourseController::class, 'addTutorial'])->name('course.tutorial.add');
        Route::get('/course/{course}/tutorial/{tutorial}', [CourseController::class, 'showTutorialEdit']);
        Route::put('/course/{course}/tutorial/{tutorial}', [CourseController::class, 'setTutorialDetails'])->name('tutorial.title.edit');

        //delete course
        Route::delete('/delete/course/{course}/tutorial/{tutorial}', [CourseController::class, 'deleteVideo'])->name('delete.course.tutorial');
        Route::delete('/delete/course/{course}', [CourseController::class, 'deleteCourse'])->name('delete.course');

        //review course
        Route::post('/create/{name}/review/{id}',[ReviewController::class, 'createReview'])->name('create.review');
        Route::delete('/delete/{review}',[ReviewController::class, 'deleteReview'])->name('delete.review');
        Route::put('/update/{review}',[ReviewController::class, 'editReview'])->name('update.review');


        //forum 
        Route::get('/show/forum/{forum}',[ForumController::class, 'getForumDetails'])->name('show.forum');
        Route::get('/edit/forum/{forum}',fn(Forum $forum) => view('pages/forum/Edit',['forum' => $forum]))->name('edit.forum');
        Route::put('/edit/forum/{forum}',[ForumController::class, 'updateForumDetails'])->name('update.forum');

        //post
        Route::post('/{forum}/post/create',[ForumController::class, 'postCreate'])->name('forum.post.create');
        Route::post('/{forum}/question/create',[ForumController::class, 'questionCreate'])->name('forum.question.create');
        

    });
});

//get course
Route::get('/show/course/{course}', [CourseController::class, 'showDetails'])->name('show.course.details');

