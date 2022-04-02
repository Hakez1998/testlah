<?php

namespace App\View\Components\icon;

use Illuminate\View\Component;

class title extends Component
{
    public $size, $color;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($size, $color)
    {
        $this->size = $size;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.icon.title');
    }
}
