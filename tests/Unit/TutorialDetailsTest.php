<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\TutorialDetails;
use Tests\TestCase;

class TutorialDetailsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_tutorial_details()
    {
        $course = Course::where('id',4)->first();
        $tutorialDetials = $course->tutorialDetails;
        $this->assertTrue($tutorialDetials->count() > 0 ? true : false, 'no tutorial found');
    }
    public function test_get_course()
    {
        $tutorial_detail = TutorialDetails::find(11);
        $this->assertTrue($tutorial_detail->course ? true : false, 'no course found');
    }
}
