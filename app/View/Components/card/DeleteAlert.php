<?php

namespace App\View\Components\card;

use Illuminate\View\Component;

class DeleteAlert extends Component
{
    public $alpineData, $function, $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($alpineData, $function, $title)
    {
        $this->alpineData = $alpineData;
        $this->function = $function;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.delete-alert');
    }
}
