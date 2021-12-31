<?php

namespace App\View\Components\Tutorial;

use Illuminate\View\Component;

class TutorialCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $tutorial;
    public $course;
    public $user;
    public function __construct($tutorial, $course, $user)
    {
        $this->tutorial = $tutorial;
        $this->course = $course;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tutorial.tutorialCard');
    }

    public function canModify()
    {
        return $this->user ? $this->course->owner == $this->user->id : false;
    }
    public function isStreamable()
    {
        return $this->canModify() || $this->course->isStudent ? 'watch-tutorial' : '';
    }
}
