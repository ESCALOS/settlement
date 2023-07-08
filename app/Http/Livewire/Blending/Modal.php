<?php

namespace App\Http\Livewire\Blending;

use App\Models\Settlement;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Modal extends Component
{
    use LivewireAlert;

    public $open = false;
    public $date = '';
    public $settlements = [[
        'id' => 0,
        'batch' => '',
        'concentrate' => '',
        'wmt' => 0,
        'wmt_missing' => 0,
        'wmt_to_blending' => 0,
    ]];
    public $wmtTotal = 0;

    protected $listeners = ['openModal'];

    public function mount(){
        $this->date = Carbon::now()->toDateString();
    }

    public function updatingOpen(){
        $this->alert('info','Cargando Datos');
    }

    /**
     * @param array $settlementIds Los id's de las liquidaciones a mezclar
     */
    public function openModal(array $settlementIds):void{
        $this->reset('settlements');
        foreach($settlementIds as $index => $settlementId){
            $settlement = Settlement::find($settlementId);
            $this->settlements[$index]['id'] = $settlement->id;
            $this->settlements[$index]['batch'] = $settlement->batch;
            $this->settlements[$index]['concentrate'] = $settlement->Order->Concentrate->concentrate;
            $this->settlements[$index]['wmt'] = number_format($settlement->Order->wmt,3);
            $this->settlements[$index]['wmt_missing'] =  number_format($settlement->Order->wmt -$settlement->wmt_shipped,3);
            $this->settlements[$index]['wmt_to_blending'] = number_format($this->settlements[$index]['wmt_missing'],3);
        }
        $this->wmtTotal = number_format(array_sum(array_column($this->settlements,'wmt_to_blending')),3);
        $this->open = true;
    }

    public function updatedSettlements(){
        try {
            $this->wmtTotal = number_format(array_sum(array_column($this->settlements,'wmt_to_blending')),3);
        }catch(\Exception $e) {
            $this->wmtTotal = 'Todas las cantidades deben ser números';
        }
    }

    public function render()
    {

        return view('livewire.blending.modal');
    }
}
