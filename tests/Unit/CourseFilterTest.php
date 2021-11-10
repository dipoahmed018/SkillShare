<?php

namespace Tests\Unit;

use App\Models\Course;
use Tests\TestCase;

class CourseFilterTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     * 
     * 
     */
    public function test_avg_review()
    {
        $courses = Course::withAVG('review as avg_rate', 'stars')
        ->havingRaw('avg_rate < ?', [7])
        ->first();
        $this->assertTrue($courses->avg_rate ? true : false);
    }


    public function test_monthly_sales()
    {
        $courses = Course::withSum(['students as sales' => fn($q) => $q->where('course_students.created_at','>', now()->subMonth())], 'id')->first();
        $this->assertTrue(($courses->sales ? true : false));
    }
}
