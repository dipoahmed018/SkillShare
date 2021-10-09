<?php

namespace App\View\Components\course;

use Illuminate\View\Component;

class Review extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $reviewData;
    public $parent;
    public $course;
    public function __construct($reviewData, $course, $parent = null)
    {
        $this->reviewData = $reviewData;
        $this->parent = $parent;
        $this->course = $course;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.course.review');
    }
    public function hello()
    {
        return 'hello';
    }
}
