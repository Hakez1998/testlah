<?php

namespace App\View\Components\card;

use Illuminate\View\Component;

class CancelAlert extends Component
{
    public $alpineData;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($alpineData)
    {
        $this->alpineData = $alpineData;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.cancel-alert');
    }
}
