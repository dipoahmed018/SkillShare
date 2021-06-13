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
    public $reviews;

    public function __construct($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('Components.review');
    }
}
