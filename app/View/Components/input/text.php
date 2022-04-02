<?php

namespace App\View\Components\input;

use Illuminate\View\Component;

class text extends Component
{
    public $placeholder, $textID;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($placeholder, $textID)
    {
        $this->placeholder = $placeholder;
        $this->textID = $textID;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input.text');
    }
}
