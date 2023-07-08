<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderCrud extends Component
{

    public $label;
    public $button;
    public $import;

    public function __construct($label='Registrar',$button=true,$import=false)
    {
        $this->label = $label;
        $this->button = $button;
        $this->import = $import;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header-crud');
    }
}
