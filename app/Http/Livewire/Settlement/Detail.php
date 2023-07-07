<?php

namespace App\Http\Livewire\Settlement;

use App\Models\Settlement;
use Livewire\Component;

class Detail extends Component
{
    public $open = false;
    public $settlementId = 0;

    protected $listeners = ['showDetails'];

    public function showDetails($id){
        $this->settlementId = $id;
        $this->open = true;
    }

    public function render()
    {
        $settlement = $this->settlementId > 0 ? Settlement::find($this->settlementId) : null;

        return view('livewire.settlement.detail',[
            "settlement" => $settlement
        ]);
    }
}
