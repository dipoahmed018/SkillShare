<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $bestSellersCourses = Course::with([
            'ownerDetails' => fn ($q) => $q->select('users.*')->with('profilePicture'),
            'thumbnail' => fn ($q) => $q->select('file_link.*'),
        ])
            ->selectRaw('AVG(review.stars) AS avg_rate, course.*, COUNT(course_students.id) AS sales')
            ->MonthlySales()
            ->Review()
            ->orderBy('sales', 'asc')
            ->limit(10)
            ->get();

        //recomended courses
        $recommendedCourse = Course::with([
            'ownerDetails' => fn ($q) => $q->select('users.*'),
            'thumbnail' => fn ($q) => $q->select('file_link.*'),
        ])
            ->selectRaw('AVG(review.stars) as avg_rate, course.*');

        $recommendedCourse->Review();

        //add owned course catagory filter if logged in
        if ($user = Auth::user()) {
            $myCourse = Course::query()
                ->MyCourse($user->id)
                ->with('catagory')
                ->get();
            $catagories = $myCourse->pluck('catagory')->flatten()->unique('id')->pluck('id');
            $catagories->count() < 1 ?: $recommendedCourse->whereHas('catagory', fn ($q) => $q->whereIn('catagory.id', $catagories));
            $recommendedCourse = $recommendedCourse->get();
            $recommendedCourse = $recommendedCourse->count() < 1 ? null : $recommendedCourse;
        }
        $recommendedCourse = $recommendedCourse ?: $bestSellersCourses;
        // dump($bestSellersCourses->pluck('ownerDetails'));
        return view('pages.Dashboard', [
            'courses' => $recommendedCourse,
            'best_sellers' => $bestSellersCourses->pluck('ownerDetails'),
        ]);
    }
}
