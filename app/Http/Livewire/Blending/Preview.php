<?php

namespace App\Http\Livewire\Blending;

use App\Helpers\Helpers;
use Livewire\Component;

class Preview extends Component
{
    public $open = false;
    public $settlements = [];
    public $law = [
        'copper' => 0,
        'silver' => 0,
        'gold' => 0,
    ];
    public $penalty = [
        'arsenic' => 0,
        'antomony' => 0,
        'lead' => 0,
        'zinc' => 0,
        'bismuth' => 0,
        'mercury' => 0,
    ];
    public $total = [
        'wmt' => 0,
        'dnwmt' => 0,
        'amount' => 0,
    ];

    protected $listeners = ['openModal'];

    public function openModal(array $settlements){
        $this->resetExcept('open');
        [$this->settlements,$this->law,$this->penalty,$this->total] = Helpers::getBlendingData($settlements);
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.blending.preview');
    }
}
