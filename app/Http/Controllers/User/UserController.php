<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function ShowRegisterForm()
    {
        return view('pages/RegisterForm');
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
        Auth::login($user, true);
        return redirect('/', 302);
    }

    public function ShowLoginForm()
    {
        return Auth::check() ? redirect('/', 302) : view('/pages/LoginForm');
    }
    public function Login(LoginRequest $request)
    {
        if (Auth::check()) {
            return redirect('/', 302);
        } else {
            if (Auth::attempt($request->only('email', 'password'), true)) {
                $request->session()->regenerate();
                return redirect('/dashboard', '302');
            } else {
                return redirect('/show/login', 302)->withErrors(['email' => 'please provide a valid email address']);
            }
        }
    }

    public function Logout(LogoutRequest $request)
    {
        Auth::logout();
        redirect('/', 302);
    }


    public function Update(UpdateRequest $request)
    {
        $profile_picture = $request->profile_picture;
        $name = $request->name;
        $gender = $request->gender;
        $birthdate = $request->birthdate;

        if (!$profile_picture) {
           $newfilename = str_replace(['.',' '],'',$request->user()->name);
        }
    }
}
