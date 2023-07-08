<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;

class Base extends Component
{
    public function openModal(){
        $this->emitTo('order.modal','openModal',0);
    }

    public function render()
    {
        return view('livewire.order.base');
    }
}
