<?php

use App\Models\Course;
use App\Models\Catagory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Forum\ForumController;
use App\Http\Controllers\Forum\PostController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Transaction\BuycourseController;
use App\Http\Controllers\Tutorial\TutorialController;
use App\Models\Forum;
use Illuminate\Http\Request;

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
Route::get('/dashboard', DashboardController::class);


//user interection routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [UserController::class, 'Register'])->name('register');
    Route::get('/register', [UserController::class, 'ShowRegisterForm'])->name('ShowRegisterForm');

    Route::get('/login', [UserController::class, 'ShowLoginForm'])->name('show.login');
    Route::post('/login', [UserController::class, 'Login'])->name('login')->middleware(['throttle:login']);


    //password fogot

    Route::get('/forgot-password', fn () => view('Components/ForgotPassword'))->name('forgot.password.form');
    Route::post('/forgot-password', [UserController::class, 'forgot_password'])->name('forgot.password')->middleware('throttle:2,1');
    Route::get('/reset-password/{email}/{token}', fn ($email, $token) => view('/Components/PasswordReset', ['token' => $token, 'email' => $email]))->name('password.reset');
    Route::post('/reset-password', [UserController::class, 'passwordReset'])->name('password.update');
});

//public
Route::get('/courses', [CourseController::class, 'index'])->name('index.courses');

//get course
Route::get('/show/course/{course}', [CourseController::class, 'showDetails'])->name('show.course.details');

//get review replies
Route::get('/review/{review}/replies', [ReviewController::class, 'getReplies'])->name('get.review.replies');

Route::middleware(['auth:web'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/{user}/profile', [UserController::class, 'getUser'])->name('show.user');
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

        //create course
        Route::post('/create/course', [CourseController::class, 'createCourse'])->name('create.course');
        Route::get('/create/course', fn () => view('pages/course/Create')->with('test', 'hello'));

        //update course
        Route::get('/update/course/{course}', [CourseController::class, 'showEditCourse']);
        Route::put('/update/course/{course}', [CourseController::class, 'updateDetails'])->name('update.course');
        Route::post('/update/course/{course}/thumbnail', [CourseController::class, 'setThumbnail'])->name('update.course.thumbnail');
        Route::post('/update/course/{course}/introduction', [CourseController::class, 'setIntroduction'])->name('update.course.introduction');

        //course tutorial
        Route::get('/show/tutorial/{tutorial}/{course}', [TutorialController::class, 'streamTutorial'])->name('show.tutorial');
        Route::post('/course/{course}/tutorial', [TutorialController::class, 'addTutorial'])->name('course.tutorial.add');
        Route::put('/update/course/tutorial/{tutorial}/order', [TutorialController::class, 'updateTutorialOrder'])->name('tutorial.order.edit');
        Route::put('/update/course/tutorial/{tutorial}/title', [TutorialController::class, 'updateTitle'])->name('tutorial.title.edit');
        Route::delete('/delete/course/{course}/tutorial/{tutorial}', [TutorialController::class, 'deleteTutorial'])->name('delete.course.tutorial');

        //delete course
        Route::delete('/delete/course/{course}', [CourseController::class, 'deleteCourse'])->name('delete.course');

        //review course
        Route::post('/create/review', [ReviewController::class, 'createReview'])->name('create.review');
        Route::delete('/delete/review/{review}', [ReviewController::class, 'deleteReview'])->name('delete.review');
        Route::put('/update/review/{review}', [ReviewController::class, 'editReview'])->name('update.review');

        //forum 
        Route::get('/show/forum/{forum}', [ForumController::class, 'getForumDetails'])->name('show.forum');
        Route::get('/edit/forum/{forum}', fn (Forum $forum) => view('pages/forum/Edit', ['forum' => $forum]))->name('edit.forum');
        Route::put('/edit/forum/{forum}', [ForumController::class, 'updateForumDetails'])->name('update.forum');

        //post
        Route::post('/{postable}/{type}/create', [PostController::class, 'postCreate'])->name('post.create');
        Route::put('/post/edit/{post}', [PostController::class, 'postUpdate'])->name('post.edit');
        Route::post('/save/post/image', [PostController::class, 'saveImage'])->name('forum.save.image');
        Route::get('/get/post/image/{name}/{post?}', [PostController::class, 'getImage'])->name('forum.get.image');

        Route::get('/show/question/{question}', [PostController::class, 'getQuestion'])->name('show.question');
        Route::get('/show/post/{post}', [PostController::class, 'getPost'])->name('show.post');

        Route::put('/{post}/post/vote', [PostController::class, 'updateVote'])->name('post.update.vote')->middleware(['can:access,post']);
        Route::put('/{post}/post/answer', [PostController::class, 'acceptAnswer'])->name('post.update.answer');
        Route::delete('/{post}/post/delete', [PostController::class, 'deletePost'])->name('post.delete');

        //comment
        Route::get('/comments/index/{post?}', [CommentController::class, 'index'])->name('commets');
        Route::post('/comment/create', [CommentController::class, 'commentCreate'])->name('comment.create');
        Route::put('/{comment}/comment/update', [CommentController::class, 'updateComment'])->name('comment.update');
        Route::delete('/{comment}/comment/delete', [CommentController::class, 'deleteComment'])->name('comment.delete');
        Route::post('/{comment}/comment/update/vote', [CommentController::class, 'updateVote'])->name('comment.update.vote');

        //transaction

        Route::post('/purchase/course/confirm', [BuycourseController::class, 'confirm_purchase'])->name('purchase.confirm');
        Route::get('/purchase/course/{product}', [BuycourseController::class, 'show_payment_methods'])->name('purchase.product');


        //images control
        Route::post('/save/image', fn (Request $request) => saveImage($request->file('upload'), env('APP_URL') . "/get/image"))->name('save.image');
        Route::get('/get/image', fn (Request $request) => getImage($request->name))->name('get.image');
    });
});
