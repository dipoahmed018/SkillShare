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

    /**
     * 
     * @param $parent is base review it's only available on reivew-replies. this variable is used to 
     * identify parent review and reply review and also may other cases
     */
    public $reviewData;
    public $parent;
    public $course;
    public $user;
    public function __construct($reviewData, $course, $user, $parent = null)
    {
        $this->reviewData = $reviewData;
        $this->parent = $parent;
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
        return view('components.course.review');
    }
    public function canReply()
    {
        //return true if user is the owner of this course so he can reply tho this review
        if ($this->course->ownerDetails->id == $this->user->id && $this->reviewData->ownerDetails->id !== $this->user->id) {
            return true;
        };

        //return true if the user is owner of the base parent and current review is not replied by current user
        if ($this->parent) {
            return $this->parent->ownerDetails->id == $this->user->id && $this->reviewData->ownerDetails->id !== $this->user->id ;
        }
        return false;
    }
}
