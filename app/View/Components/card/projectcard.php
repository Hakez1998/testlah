<?php

namespace App\View\Components\card;

use Illuminate\View\Component;

class projectcard extends Component
{
    public $title, $description, $time, $redirect, $projectId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $description, $time, $redirect, $projectId)
    {
        $this->title = $title; 
        $this->description = $description; 
        $this->time = $time;
        $this->redirect = $redirect;
        $this->projectId = $projectId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.projectcard');
    }
}
