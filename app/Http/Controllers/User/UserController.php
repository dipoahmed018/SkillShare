<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePassword;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\PasswordReset;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\SendVerificationMail;
use App\Http\Requests\User\UpdateRequest;
use App\Mail\ChangeEmailUrl;
use App\Models\FileLink;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset as EventsPasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getUser(Request $request, User $user)
    {
        return view('pages/user/show');
    }
    public function ShowRegisterForm()
    {
        return Auth::check() ? redirect('/') : view('/pages/user/RegisterForm');
    }

    public function Register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
        ]);
        event(new Registered($user));
        Auth::login($user, true);
        return redirect()->intended('/');
    }

    public function ShowLoginForm()
    {
        return Auth::check() ? redirect('/') : view('/pages/user/LoginForm');
    }
    public function Login(LoginRequest $request)
    {
        if (Auth::check()) {
            return redirect('/', 302);
        } else {
            if (Auth::attempt($request->only('email', 'password'), true)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            } else {
                return redirect('/login', 302)->withErrors(['email' => 'please provide a valid email address']);
            }
        }
    }

    public function Logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function ShowUserUpdateForm(Request $request)
    {
        $user = $request->user();
        return !Auth::check() ? redirect('/', 302) : view('pages/user/ProfileUpdateForm', ['user' => $user, 'profile_picture' => $user->profilePicture]);
    }
    public function Update(UpdateRequest $request)
    {

        $user = $request->user();
        $profile_picture = $request->file('profile_picture');
        $name = $request->name;
        $gender = $request->gender;
        $birthdate = $request->birthdate;

        if ($profile_picture) {
            $newfilename = str_replace(['.', ' ','/'], '', $user->name) . time(). '_profile.' . $profile_picture->getClientOriginalExtension();
            $file_path = storage_path('/app/public/profile/profile_photo/' . $newfilename);
            $image = Image::make($profile_picture->getRealPath());
            if ($user->profilePicture) {
                Storage::disk('public')->delete("/profile/profile_photo/" . $user->profilePicture->file_name);
                $user->profilePicture()->delete();
            }
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($file_path, 80);
            $file = FileLink::create([
                'file_name' => $newfilename,
                'file_link' => asset('/storage/profile/profile_photo/' . $newfilename),
                'file_type' => 'profile_photo',
                'security' => 'public',
                'fileable_id' => $user->id,
                'fileable_type' => 'user',
            ]);
            $profile_picture = $file->file_link;
        }
        if ($name) {
            $user->name = $name;
            $user->save();
        }
        if ($gender) {
            $user->gender = $gender;
            $user->save();
        }
        if ($birthdate) {
            $user->birthdate = $birthdate;
            $user->save();
        }

        return back()
            ->with('profile_picture', $profile_picture)
            ->with('gender', $gender)
            ->with('birthdate', $birthdate)
            ->with('name', $name);
    }

    public function changePassword(ChangePassword $request)
    {
        $user = $request->user();
        $user->forceFill([
            'password' => bcrypt($request->password)
        ])->setRememberToken(Str::random(60));
        $user->save();
        $request->session()->regenerate();
        return redirect('/')->with('pop_password_reset', 'success');
    }

    public function sendEmailVerificationMail(SendVerificationMail $request)
    {
        $user = $request->user();
        $user->sendEmailVerificationNotification();
        return redirect()->intended('/dashboard');
    }
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/');
    }
    public function changeEmail(Request $request)
    {
        $rules = [
            'email' => 'email:rfc|unique:users',
        ];
        $request->validate($rules, $request->all());
        $redis = Redis::connection();
        $user = $request->user();
        $random_code = rand(1000, 9000);

        $redis_code = $redis->get("verify:$user->email:code");
        $code = $request->code;
        if ($request->email && !$user->email_verified_at) {
            $user->email = $request->email;
            $user->remeber_token = Str::random(60);
            $user->save();
            //send notification
            $user->sendEmailVerificationNotification();
            $request->session()->regenerate();
            return back()->with('sent', 'verification_mail')
                ->with('status', 'success');
        }
        if (!$request->email && !$user->email_verified_at) {
            return back()->with('invalid', 'please provide your new email');
        }
        if (!$request->email && (!$redis_code || !$code) && $user->email_verified_at) {
            $redis->setex('verify:' . $user->email . ':code', 60 * 10, $random_code);
            $url = route('user.update.email.form', [$random_code]);
            Mail::to($user)->send(new ChangeEmailUrl($url));
            return back()->with('sent', 'A link has been sent to your email');
        }
        if (!$request->email && $redis_code == $code && $user->email_verified_at) {
            return redirect('/user/update/email/{' . $redis_code . '}')->with('email_invalid', 'please provide you new email address');
        }
        if ($request->email && $redis_code !== $code  && $user->email_verified_at) {
            return redirect('/user/update/email/invalid')->with('expired', 'Your link Has expired');
        }
        if ($request->email && $redis_code == $code && $user->email_verified_at) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->remeber_token = Str::random(60);
            $user->save();
            $user->sendEmailVerificationNotification();
            return redirect('/dashboard')->with('sent', 'verification mail sent to your new email');
        }
    }
    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email:rfc']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function passwordReset(PasswordReset $request)
    {
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new EventsPasswordReset($user));
        });
        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status',__($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
