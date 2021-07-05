<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AnswerShow extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $answer;
    public $question;

    public function __construct($answer, $question)
    {
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('Components.answer-show');
    }
}
