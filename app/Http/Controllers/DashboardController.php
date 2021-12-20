<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __invoke()
    {

        // return User::all();

        $bestSellersCourses = Course::with([
            'ownerDetails' => fn ($q) => $q->select('users.*')->with('profilePicture'),
            'thumbnail' => fn ($q) => $q->select('file_link.*'),
        ])
            ->withAvg('review as avg_rate', 'stars')
            ->AvarageRating()
            ->MonthlySales()
            ->limit(10)
            ->orderBy('avg_rate', 'desc')
            ->get();

        //recomended courses
        $recommendedCourse = null;
        
        //add owned course catagory filter if logged in
        if ($user = Auth::user()) {
            $recommendedCourse = Course::with([
                'ownerDetails',
                'thumbnail',
            ])
                ->AvarageRating();

            $myCourse = Course::query()
                ->MyCourse($user->id)
                ->with('catagory')
                ->get();

            $catagories = $myCourse->pluck('catagory')->flatten()->unique('id')->pluck('id');
            $recommendedCourse = $catagories->count() < 1 ? null : $recommendedCourse->whereHas('catagory', fn ($q) => $q->whereIn('catagory.id', $catagories))->get();
        }
        $bestSellers = $bestSellersCourses->pluck('ownerDetails');
        $bestSellers = $bestSellers->count() < 1 ? User::query()->whereHas('myCourses')->limit(10)->get() : $bestSellers;

        $recommendedCourse = $recommendedCourse ?? $bestSellersCourses;
        return view('pages.Dashboard', [
            'courses' => $recommendedCourse,
            'best_sellers' => $bestSellers,
        ]);
    }
}
