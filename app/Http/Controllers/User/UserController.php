<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\FileLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    public function ShowUserUpdateForm(Request $request)
    {
        $user = $request->user();
        return !Auth::check() ? redirect('/', 302) : view('/pages/ProfileUpdateForm', ['user' => $user, 'profile_picture' => $user->getProfilePicture()]);
    }
    public function Update(UpdateRequest $request)
    {
        $user = $request->user();
        $profile_picture = $request->file('profile_picture');
        $name = $request->name;
        $gender = $request->gender;
        $birthdate = $request->birthdate;

        if ($profile_picture) {
            $newfilename = str_replace(['.', ' '], '', $user->name) . '_profile.' . $profile_picture->getClientOriginalExtension();
            $file_path = storage_path('/app/public/profile/profile_photo/'.$newfilename);
            $image = Image::make($profile_picture->getRealPath());
            if ($user->getProfilePicture() !== 'https://skillshare.com/storage/profile/profile_photo/default.JPG') {
                $user->profile_details->delete();
                Storage::disk('public')->delete("/profile/profile_photo/" . $newfilename);
            }
            $image->resize(800, null, function ($constraint) {
                $constraint->aspectRation();
            })->save($file_path);
            $file = FileLink::create([
                'file_name' => $newfilename,
                'file_link' => asset('/storage/profile/profile_photo/'.$newfilename),
                'file_type' => 'profile_photo',
                'security' => 'public',
                'fileable_id' => $user->id,
                'fileable_type' => 'user',
            ]);
            $profile_picture = $file->file_link;
        } $profile_picture = null;
        
        return back()->with('profile_picture')
    }
}
