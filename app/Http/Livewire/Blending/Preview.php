<?php

namespace App\Http\Livewire\Blending;

use App\Models\Settlement;
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

    public function openModal($settlements){
        $this->resetExcept('open');
        $this->settlements = $settlements;
        foreach($settlements as $key => $settlement){
            $model = Settlement::find($settlement['id']);
            $fraccion = $this->settlements[$key]['wmt_to_blending']/$this->settlements[$key]['wmt'];
            $this->settlements[$key]['factor'] = $model->Law->tmns/$model->Order->wmt;
            $this->settlements[$key]['dnwmt'] = round($this->settlements[$key]['wmt_to_blending']*$this->settlements[$key]['factor'],3);
            $this->settlements[$key]['igv'] = round($model->SettlementTotal->igv*$fraccion,2);
            $this->settlements[$key]['amount'] = round(($model->SettlementTotal->batch_price+$model->SettlementTotal->igv)*$fraccion,2);
            $this->law['copper'] += $this->settlements[$key]['dnwmt']*$model->Law->copper;
            $this->law['silver'] += $this->settlements[$key]['dnwmt']*$model->Law->silver;
            $this->law['gold'] += $this->settlements[$key]['dnwmt']*$model->Law->gold;
            $this->penalty['arsenic'] += $this->settlements[$key]['dnwmt']*$model->Penalty->arsenic;
            $this->penalty['antomony'] += $this->settlements[$key]['dnwmt']*$model->Penalty->antomony;
            $this->penalty['lead'] += $this->settlements[$key]['dnwmt']*$model->Penalty->lead;
            $this->penalty['zinc'] += $this->settlements[$key]['dnwmt']*$model->Penalty->zinc;
            $this->penalty['bismuth'] += $this->settlements[$key]['dnwmt']*$model->Penalty->bismuth;
            $this->penalty['mercury'] += $this->settlements[$key]['dnwmt']*$model->Penalty->mercury;
            $this->total['wmt'] += $this->settlements[$key]['wmt_to_blending'];
            $this->total['dnwmt'] += $this->settlements[$key]['dnwmt'];
            $this->total['amount'] += $this->settlements[$key]['amount'];
        }
        $this->law['copper'] = number_format($this->law['copper']/$this->total['dnwmt'],3);
        $this->law['silver'] = number_format($this->law['silver']/$this->total['dnwmt'],3);
        $this->law['gold'] = number_format($this->law['gold']/$this->total['dnwmt'],3);
        $this->penalty['arsenic'] = number_format($this->penalty['arsenic']/$this->total['dnwmt'],3);
        $this->penalty['antomony'] = number_format($this->penalty['antomony']/$this->total['dnwmt'],3);
        $this->penalty['lead'] = number_format($this->penalty['lead']/$this->total['dnwmt'],3);
        $this->penalty['zinc'] = number_format($this->penalty['zinc']/$this->total['dnwmt'],3);
        $this->penalty['bismuth'] = number_format($this->penalty['bismuth']/$this->total['dnwmt'],3);
        $this->penalty['mercury'] = number_format($this->penalty['mercury']/$this->total['dnwmt'],3);
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.blending.preview');
    }
}
