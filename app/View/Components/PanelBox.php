<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PanelBox extends Component
{
    public $args;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($args=[])
    {
        $this->args=$args;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('backend-theme::components.panel-box');
    }
}
