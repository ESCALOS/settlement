<?php

namespace App\Http\Livewire\Settlement;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Settlement;
use Livewire\Component;

class Detail extends Component
{
    public $open = false;
    public $settlementId = 0;

    protected $listeners = ['showDetails'];

    public function showDetails(int $id):void{
        $this->settlementId = $id;
        $this->open = true;
    }

    public function printSettlement(){

        $settlement = Settlement::find($this->settlementId);

        $data = [
            'settlement' => $settlement
        ];
        $titulo = "Liquidación ".$settlement->batch.'.pdf';
        $pdfContent = PDF::loadView('livewire.settlement.pdf.settlement', $data)->setPaper('a4','portrait')->output();

        return response()->streamDownload(
            fn () => print($pdfContent),
            $titulo
        );
    }

    public function render()
    {
        $settlement = $this->settlementId > 0 ? Settlement::find($this->settlementId) : null;

        return view('livewire.settlement.detail',[
            "settlement" => $settlement
        ]);
    }
}
