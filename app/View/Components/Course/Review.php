<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Review extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $review;
    public $reviewable;
    public function __construct($review, $reviewable)
    {
        $this->review = $review;
        $this->reviewable = $reviewable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('Components.course.review');
    }
}
