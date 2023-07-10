<?php

namespace App\Http\Livewire\Blending;

use Barryvdh\DomPDF\Facade\Pdf;
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

    public function printBlending(){
        $data = [
            'settlements' => $this->settlements,
            'law' => $this->law,
            'penalty' => $this->penalty,
            'total' => $this->total
        ];
        $titulo = "Detalles del blending.pdf";
        $pdfContent = PDF::loadView('livewire.blending.pdf.preview', $data)->setPaper('a4','portrait')->output();

        return response()->streamDownload(
            fn () => print($pdfContent),
            $titulo
        );
    }

    public function render()
    {
        return view('livewire.blending.preview');
    }
}
