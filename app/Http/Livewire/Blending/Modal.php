<?php

namespace App\Http\Livewire\Blending;

use App\Helpers\Helpers;
use App\Models\Dispatch;
use App\Models\DispatchDetail;
use App\Models\DispatchLaw;
use App\Models\DispatchPenalty;
use App\Models\DispatchTotal;
use App\Models\Settlement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Modal extends Component
{
    use LivewireAlert;

    public $open = false;
    public $dispatchId = 0;
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

    protected function rules(){
        return [
            'settlements.*.wmt_to_blending' => 'required|decimal:0,3|gt:0|lte:settlements.*.wmt_missing',
        ];
    }

    public function messages(){
        return [
            'settlements.*.wmt_to_blending.required' => 'Digite el TMH',
            'settlements.*.wmt_to_blending.decimal' => 'Debe tener máximo 3 decimales',
            'settlements.*.wmt_to_blending.gt' => 'Digite una cantidad válida',
            'settlements.*.wmt_to_blending.lte' => 'Digite una cantidad válida',
        ];
    }

    public function mount(){
        $this->date = Carbon::now()->toDateString();
    }

    /**
     * @param array $settlementIds Los id's de las liquidaciones a mezclar
     */
    public function openModal(array $settlementIds,$id):void{
        $this->reset('settlements');
        $this->dispatchId = $id;
        if($id > 0){
            $this->settlements = Helpers::getDispatchDetails($id);
        }else{
            foreach($settlementIds as $index => $settlementId){
                $settlement = Settlement::find($settlementId);
                $blended = DispatchDetail::where('settlement_id',$settlement->id)->sum('wmt');
                $this->settlements[$index]['id'] = $settlement->id;
                $this->settlements[$index]['batch'] = $settlement->batch;
                $this->settlements[$index]['concentrate'] = $settlement->Order->Concentrate->concentrate;
                $this->settlements[$index]['wmt'] = number_format($settlement->Order->wmt,3);
                $this->settlements[$index]['wmt_missing'] =  number_format($settlement->Order->wmt - $blended,3);
                $this->settlements[$index]['wmt_to_blending'] = number_format($this->settlements[$index]['wmt_missing'],3);
            }
        }

        $this->wmtTotal = number_format(array_sum(array_column($this->settlements,'wmt_to_blending')),3);
        $this->open = true;
    }

    public function blending():void{
        $this->validate();
        try{
            DB::transaction(function(){
                $dispatch = Dispatch::firstOrCreate(
                    ['id' => $this->dispatchId],
                    [
                        'batch' => Helpers::createBatch('dispatches','D'),
                        'user_id' => Auth::user()->id,
                        'date_blending' => $this->date,
                    ]
                );
                [$settlements,$law,$penaly,$total] = Helpers::getBlendingData($this->settlements);
                foreach($settlements as $settlement){
                    DispatchDetail::updateOrCreate(
                        [
                            'dispatch_id' => $dispatch->id,
                            'settlement_id' => $settlement['id'],
                        ],
                        [
                            'wmt' => $settlement['wmt_to_blending'],
                            'dnwmt' => $settlement['dnwmt'],
                            'igv' => $settlement['igv'],
                            'amount' => $settlement['amount']
                        ]
                    );
                    $blended = DB::table('dispatch_details')->where('settlement_id', $settlement['id'])->sum('wmt');
                    if($blended > $settlement['wmt']){
                        throw new \Exception("Datos modificados, actualice la página");
                    }
                }
                DispatchLaw::updateOrCreate(
                    ['dispatch_id' => $this->dispatchId],
                    [
                        'dispatch_id' => $dispatch->id,
                        'copper' => $law['copper'],
                        'silver' => $law['silver'],
                        'gold' => $law['gold'],
                    ]
                );

                DispatchPenalty::updateOrCreate(
                    ['dispatch_id' => $this->dispatchId],
                    [
                        'dispatch_id' => $dispatch->id,
                        'arsenic' => $penaly['arsenic'],
                        'antomony' => $penaly['antomony'],
                        'lead' => $penaly['lead'],
                        'zinc' => $penaly['zinc'],
                        'bismuth' => $penaly['bismuth'],
                        'mercury' => $penaly['mercury'],
                    ]
                );

                DispatchTotal::updateOrCreate(
                    ['dispatch_id' => $this->dispatchId],
                    [
                        'dispatch_id' => $dispatch->id,
                        'wmt' => $total['wmt'],
                        'dnwmt' => $total['dnwmt'],
                        'amount' => $total['amount'],
                    ]
                );

                $this->alert('success', '¡Blending Exitoso!', [
                    'position' => 'center',
                    'timer' => 2000,
                    'toast' => true,
                ]);
            });
            $this->emit('refreshDatatable');
            $this->open = false;
        }catch(\PDOException $e){
            $this->alert('error', $e->getMessage());
        }catch(\Exception $e){
            $this->alert('error', $e->getMessage());
        }
    }

    public function updatedSettlements(){
        try {
            $this->wmtTotal = number_format(array_sum(array_column($this->settlements,'wmt_to_blending')),3);
        }catch(\Exception $e) {
            $this->wmtTotal = 'Todas las cantidades deben ser números';
        }
    }

    public function preview(){
        $this->emitTo('blending.preview','openModal',$this->settlements);
    }

    public function render()
    {

        return view('livewire.blending.modal');
    }
}
