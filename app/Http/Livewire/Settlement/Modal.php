<?php

namespace App\Http\Livewire\Settlement;

use App\Helpers\Helpers;
use App\Models\AllowedAmount;
use App\Models\Deduction;
use App\Models\DeductionTotal;
use App\Models\InternationalPayment;
use App\Models\Law;
use App\Models\Order;
use App\Models\PayableTotal;
use App\Models\Penalty;
use App\Models\PenaltyPrice;
use App\Models\PenaltyTotal;
use App\Models\PercentagePayable;
use App\Models\Protection;
use App\Models\Refinement;
use App\Models\Requirement;
use App\Models\Settlement;
use App\Models\SettlementTotal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use PDOException;

class Modal extends Component
{
    use LivewireAlert;

    public $open = false;
    public $settlementId = 0;
    public $orderId = 0;
    public $date;
    public $batch = "";
    public $invoice = false;
    public $internationalPayment = [
        'copper' => '',
        'silver' => '',
        'gold' => '',
    ];
    public $law = [
        'copper' => '',
        'humidity' => '',
        'decrease' => '',
        'silver' => '',
        'silverFactor' => 1.1023,
        'gold' => '',
        'goldFactor' => 1.1023,
    ];
    public $percentagePayable = [
        'cobre' => '',
        'silver' => '',
        'gold' => '',
    ];
    public $requirement = [
        'protection' => [
            'copper' => '',
            'silver' => '',
            'gold' => '',
        ],
        'deduction' => [
            'copper' => '',
            'silver' => '',
            'gold' => '',
        ],
        'refinement' => [
            'copper' => '',
            'silver' => '',
            'gold' => '',
        ],
        'other' => [
            'maquila' => '',
            'analysis' => '',
            'stevedore' => '',
        ]
    ];
    public $penalty = [
        'arsenic' => 0,
        'antomony' => 0,
        'lead' => 0,
        'zinc' => 0,
        'bismuth' => 0,
        'mercury' => 0,
    ];
    public $maximum = [
        'arsenic' => 0,
        'antomony' => 0,
        'lead' => 0,
        'zinc' => 0,
        'bismuth' => 0,
        'mercury' => 0,
    ];
    public $price = [
        'arsenic' => 10,
        'antomony' => 10.65,
        'lead' => 5,
        'zinc' => 5,
        'bismuth' => 10,
        'mercury' => 10,
    ];

    protected $listeners = ['openModal'];

    protected function rules(){
        return [
            'internationalPayment.copper' => 'required|decimal:0,4',
            'internationalPayment.silver' => 'required|decimal:0,4',
            'internationalPayment.gold' => 'required|decimal:0,4',
            'law.copper' => 'required|decimal:0,4',
            'law.humidity' => 'required|decimal:0,4',
            'law.decrease' => 'required|decimal:0,4',
            'law.silver' => 'required|decimal:0,4',
            'law.silverFactor' => 'required|decimal:0,4',
            'law.gold' => 'required|decimal:0,4',
            'law.goldFactor' => 'required|decimal:0,4',
            'percentagePayable.copper' => 'required|decimal:0,4',
            'percentagePayable.silver' => 'required|decimal:0,4',
            'percentagePayable.gold' => 'required|decimal:0,4',
            'requirement.protection.copper' => 'required|decimal:0,4',
            'requirement.protection.silver' => 'required|decimal:0,4',
            'requirement.protection.gold' => 'required|decimal:0,4',
            'requirement.deduction.copper' => 'required|decimal:0,4',
            'requirement.deduction.silver' => 'required|decimal:0,4',
            'requirement.deduction.gold' => 'required|decimal:0,4',
            'requirement.refinement.copper' => 'required|decimal:0,4',
            'requirement.refinement.silver' => 'required|decimal:0,4',
            'requirement.refinement.gold' => 'required|decimal:0,4',
            'requirement.other.maquila' => 'required|decimal:0,4',
            'requirement.other.analysis' => 'required|decimal:0,4',
            'requirement.other.stevedore' => 'required|decimal:0,4',
            'penalty.arsenic' => 'required|decimal:0,4',
            'penalty.antomony' => 'required|decimal:0,4',
            'penalty.lead' => 'required|decimal:0,4',
            'penalty.zinc' => 'required|decimal:0,4',
            'penalty.bismuth' => 'required|decimal:0,4',
            'penalty.mercury' => 'required|decimal:0,4',
            'maximum.arsenic' => 'required|decimal:0,4',
            'maximum.antomony' => 'required|decimal:0,4',
            'maximum.lead' => 'required|decimal:0,4',
            'maximum.zinc' => 'required|decimal:0,4',
            'maximum.bismuth' => 'required|decimal:0,4',
            'maximum.mercury' => 'required|decimal:0,4',
            'price.arsenic' => 'required|decimal:0,4',
            'price.antomony' => 'required|decimal:0,4',
            'price.lead' => 'required|decimal:0,4',
            'price.zinc' => 'required|decimal:0,4',
            'price.bismuth' => 'required|decimal:0,4',
            'price.mercury' => 'required|decimal:0,4',
        ];
    }

    public function messages(){
        return [
            'internationalPayment.copper.required' => 'El campo es requerido',
            'internationalPayment.silver.required' => 'El campo es requerido',
            'internationalPayment.gold.required' => 'El campo es requerido',
            'law.copper.required' => 'El campo es requerido',
            'law.humidity.required' => 'El campo es requerido',
            'law.decrease.required' => 'El campo es requerido',
            'law.silver.required' => 'El campo es requerido',
            'law.silverFactor.required' => 'El campo es requerido',
            'law.gold.required' => 'El campo es requerido',
            'law.goldFactor.required' => 'El campo es requerido',
            'percentagePayable.copper.required' => 'El campo es requerido',
            'percentagePayable.silver.required' => 'El campo es requerido',
            'percentagePayable.gold.required' => 'El campo es requerido',
            'requirement.protection.copper.required' => 'El campo es requerido',
            'requirement.protection.silver.required' => 'El campo es requerido',
            'requirement.protection.gold.required' => 'El campo es requerido',
            'requirement.deduction.copper.required' => 'El campo es requerido',
            'requirement.deduction.silver.required' => 'El campo es requerido',
            'requirement.deduction.gold.required' => 'El campo es requerido',
            'requirement.refinement.copper.required' => 'El campo es requerido',
            'requirement.refinement.silver.required' => 'El campo es requerido',
            'requirement.refinement.gold.required' => 'El campo es requerido',
            'requirement.other.maquila.required' => 'El campo es requerido',
            'requirement.other.analysis.required' => 'El campo es requerido',
            'requirement.other.stevedore.required' => 'El campo es requerido',
            'penalty.arsenic.required' => 'El campo es requerido',
            'penalty.antomony.required' => 'El campo es requerido',
            'penalty.lead.required' => 'El campo es requerido',
            'penalty.zinc.required' => 'El campo es requerido',
            'penalty.bismuth.required' => 'El campo es requerido',
            'penalty.mercury.required' => 'El campo es requerido',
            'maximum.arsenic.required' => 'El campo es requerido',
            'maximum.antomony.required' => 'El campo es requerido',
            'maximum.lead.required' => 'El campo es requerido',
            'maximum.zinc.required' => 'El campo es requerido',
            'maximum.bismuth.required' => 'El campo es requerido',
            'maximum.mercury.required' => 'El campo es requerido',
            'price.arsenic.required' => 'El campo es requerido',
            'price.antomony.required' => 'El campo es requerido',
            'price.lead.required' => 'El campo es requerido',
            'price.zinc.required' => 'El campo es requerido',
            'price.bismuth.required' => 'El campo es requerido',
            'price.mercury.required' => 'El campo es requerido',

            'internationalPayment.copper.decimal' => '3 decimales como máximo',
            'internationalPayment.silver.decimal' => '3 decimales como máximo',
            'internationalPayment.gold.decimal' => '3 decimales como máximo',
            'law.copper.decimal' => '3 decimales como máximo',
            'law.humidity.decimal' => '3 decimales como máximo',
            'law.decrease.decimal' => '3 decimales como máximo',
            'law.silver.decimal' => '3 decimales como máximo',
            'law.silverFactor.decimal' => '4 decimales como máximo',
            'law.gold.decimal' => '3 decimales como máximo',
            'law.goldFactor.decimal' => '4 decimales como máximo',
            'percentagePayable.copper.decimal' => '3 decimales como máximo',
            'percentagePayable.silver.decimal' => '3 decimales como máximo',
            'percentagePayable.gold.decimal' => '3 decimales como máximo',
            'requirement.protection.copper.decimal' => '3 decimales como máximo',
            'requirement.protection.silver.decimal' => '3 decimales como máximo',
            'requirement.protection.gold.decimal' => '3 decimales como máximo',
            'requirement.deduction.copper.decimal' => '3 decimales como máximo',
            'requirement.deduction.silver.decimal' => '3 decimales como máximo',
            'requirement.deduction.gold.decimal' => '3 decimales como máximo',
            'requirement.refinement.copper.decimal' => '3 decimales como máximo',
            'requirement.refinement.silver.decimal' => '3 decimales como máximo',
            'requirement.refinement.gold.decimal' => '3 decimales como máximo',
            'requirement.other.maquila.decimal' => '3 decimales como máximo',
            'requirement.other.analysis.decimal' => '3 decimales como máximo',
            'requirement.other.stevedore.decimal' => '3 decimales como máximo',
            'penalty.arsenic.decimal' => '3 decimales como máximo',
            'penalty.antomony.decimal' => '3 decimales como máximo',
            'penalty.lead.decimal' => '3 decimales como máximo',
            'penalty.zinc.decimal' => '3 decimales como máximo',
            'penalty.bismuth.decimal' => '3 decimales como máximo',
            'penalty.mercury.decimal' => '3 decimales como máximo',
            'maximum.arsenic.decimal' => '3 decimales como máximo',
            'maximum.antomony.decimal' => '3 decimales como máximo',
            'maximum.lead.decimal' => '3 decimales como máximo',
            'maximum.zinc.decimal' => '3 decimales como máximo',
            'maximum.bismuth.decimal' => '3 decimales como máximo',
            'maximum.mercury.decimal' => '3 decimales como máximo',
            'price.arsenic.decimal' => '3 decimales como máximo',
            'price.antomony.decimal' => '3 decimales como máximo',
            'price.lead.decimal' => '3 decimales como máximo',
            'price.zinc.decimal' => '3 decimales como máximo',
            'price.bismuth.decimal' => '3 decimales como máximo',
            'price.mercury.decimal' => '3 decimales como máximo',
        ];
    }

    public function mount(){
        $this->date = Carbon::now()->toDateString();
    }

    private function fillFields($settlementId){
        $this->settlementId = $settlementId;
        $settlement = Settlement::find($settlementId);
        $this->orderId = $settlement->order_id;
        $this->date = Carbon::parse($settlement->date)->format('Y-m-d');
        $this->batch = $settlement->batch;
        $this->internationalPayment['copper'] = floatval($settlement->InternationalPayment->copper);
        $this->internationalPayment['silver'] = floatval($settlement->InternationalPayment->silver);
        $this->internationalPayment['gold'] = floatval($settlement->InternationalPayment->gold);
        $this->law['copper'] = floatval($settlement->Law->copper);
        $this->law['humidity'] = floatval($settlement->Law->humidity);
        $this->law['decrease'] = floatval($settlement->Law->decrease);
        $this->law['silver'] = floatval($settlement->Law->silver);
        $this->law['silverFactor'] = floatval($settlement->Law->silver_factor);
        $this->law['gold'] = floatval($settlement->Law->gold);
        $this->law['goldFactor'] = floatval($settlement->Law->gold_factor);
        $this->percentagePayable['copper'] = floatval($settlement->PercentagePayable->copper);
        $this->percentagePayable['silver'] = floatval($settlement->PercentagePayable->silver);
        $this->percentagePayable['gold'] = floatval($settlement->PercentagePayable->gold);
        $this->requirement['protection']['copper'] = floatval($settlement->Protection->copper);
        $this->requirement['protection']['silver'] = floatval($settlement->Protection->silver);
        $this->requirement['protection']['gold'] = floatval($settlement->Protection->gold);
        $this->requirement['deduction']['copper'] = floatval($settlement->Deduction->copper);
        $this->requirement['deduction']['silver'] = floatval($settlement->Deduction->silver);
        $this->requirement['deduction']['gold'] = floatval($settlement->Deduction->gold);
        $this->requirement['refinement']['copper'] = floatval($settlement->Refinement->copper);
        $this->requirement['refinement']['silver'] = floatval($settlement->Refinement->silver);
        $this->requirement['refinement']['gold'] = floatval($settlement->Refinement->gold);
        $this->requirement['other']['maquila'] = floatval($settlement->Requirement->maquila);
        $this->requirement['other']['analysis'] = floatval($settlement->Requirement->analysis);
        $this->requirement['other']['stevedore'] = floatval($settlement->Requirement->stevedore);
        $this->penalty['arsenic'] = number_format($settlement->Penalty->arsenic,3);
        $this->penalty['antomony'] = number_format($settlement->Penalty->antomony,3);
        $this->penalty['lead'] = number_format($settlement->Penalty->lead,3);
        $this->penalty['zinc'] = number_format($settlement->Penalty->zinc,3);
        $this->penalty['bismuth'] = number_format($settlement->Penalty->bismuth,3);
        $this->penalty['mercury'] = number_format($settlement->Penalty->mercury,3);
        $this->maximum['arsenic'] = number_format($settlement->AllowedAmount->arsenic,3);
        $this->maximum['antomony'] = number_format($settlement->AllowedAmount->antomony,3);
        $this->maximum['lead'] = number_format($settlement->AllowedAmount->lead,3);
        $this->maximum['zinc'] = number_format($settlement->AllowedAmount->zinc,3);
        $this->maximum['bismuth'] = number_format($settlement->AllowedAmount->bismuth,3);
        $this->maximum['mercury'] = number_format($settlement->AllowedAmount->mercury,3);
        $this->price['arsenic'] = number_format($settlement->PenaltyPrice->arsenic,3);
        $this->price['antomony'] = number_format($settlement->PenaltyPrice->antomony,3);
        $this->price['lead'] = number_format($settlement->PenaltyPrice->lead,3);
        $this->price['zinc'] = number_format($settlement->PenaltyPrice->zinc,3);
        $this->price['bismuth'] = number_format($settlement->PenaltyPrice->bismuth,3);
    }

    public function openModal($settlementId,$orderId){
        $this->resetValidation();
        $this->resetExcept('open','date');
        if($settlementId){
            $this->fillFields($settlementId);
        }else{
            $client = Order::find($orderId)->client_id;
            $order = Order::where('client_id',$client)->where('settled',1)->orderBy('id','DESC')->first();
            if($order){
                $settlement = Settlement::where('order_id',$order->id)->first();
                $this->fillFields($settlement->id);
            }
            $this->orderId = $orderId;
            $this->settlementId = $settlementId;
        }
        $this->open = true;
    }

    public function save(){
        $this->validate();
        try {
            DB::transaction(function () {
                $order = Order::find($this->orderId);
                if($order->settle){
                    throw new \Exception("La orden ya está liquidada");
                }
                $order->settled = true;

                if($this->settlementId){
                    $settlement = Settlement::find($this->settlementId);
                    $settlement->with_invoice = $this->invoice;
                    $settlement->date = $this->date;
                    $settlement->user_id = Auth::user()->id;

                    $internationalPayment = InternationalPayment::where('settlement_id',$this->settlementId)->first();
                    $percentagePayable = PercentagePayable::where('settlement_id',$this->settlementId)->first();
                    $law = Law::where('settlement_id',$this->settlementId)->first();
                    $protection = Protection::where('settlement_id',$this->settlementId)->first();
                    $deduction = Deduction::where('settlement_id',$this->settlementId)->first();
                    $refinement = Refinement::where('settlement_id',$this->settlementId)->first();
                    $requirement = Requirement::where('settlement_id',$this->settlementId)->first();
                    $penalty = Penalty::where('settlement_id',$this->settlementId)->first();
                    $allowedAmount = AllowedAmount::where('settlement_id',$this->settlementId)->first();
                    $penaltyPrice = PenaltyPrice::where('settlement_id',$this->settlementId)->first();
                    $payableTotal = PayableTotal::where('settlement_id',$this->settlementId)->first();
                    $deductionTotal = DeductionTotal::where('settlement_id',$this->settlementId)->first();
                    $penaltyTotal = PenaltyTotal::where('settlement_id',$this->settlementId)->first();
                    $settlementTotal = SettlementTotal::where('settlement_id',$this->settlementId)->first();
                }else{
                    $settlement = Settlement::create([
                        'order_id' => $this->orderId,
                        'batch' => Helpers::createBatch('settlements','L'),
                        'with_invoice' => $this->invoice,
                        'user_id' => Auth::user()->id,
                        'date' => $this->date
                    ]);
                    $internationalPayment = new InternationalPayment();
                    $percentagePayable = new PercentagePayable();
                    $law = new Law();
                    $protection = new Protection();
                    $deduction = new Deduction();
                    $refinement = new Refinement();
                    $requirement = new Requirement();
                    $penalty = new Penalty();
                    $allowedAmount = new AllowedAmount();
                    $penaltyPrice = new PenaltyPrice();
                    $payableTotal = new PayableTotal();
                    $deductionTotal = new DeductionTotal();
                    $penaltyTotal = new PenaltyTotal();
                    $settlementTotal = new SettlementTotal();
                }

                $internationalPayment->settlement_id = $settlement->id;
                $internationalPayment->copper = $this->internationalPayment['copper'];
                $internationalPayment->silver = $this->internationalPayment['silver'];
                $internationalPayment->gold = $this->internationalPayment['gold'];
                $internationalPayment->save();

                $percentagePayable->settlement_id = $settlement->id;
                $percentagePayable->copper = $this->percentagePayable['copper'];
                $percentagePayable->silver = $this->percentagePayable['silver'];
                $percentagePayable->gold = $this->percentagePayable['gold'];
                $percentagePayable->save();

                $law->settlement_id = $settlement->id;
                $law->copper = $this->law['copper'];
                $law->humidity = $this->law['humidity'];
                $law->decrease = $this->law['decrease'];
                $law->silver = $this->law['silver'];
                $law->silver_factor = $this->law['silverFactor'];
                $law->gold = $this->law['gold'];
                $law->gold_factor = $this->law['goldFactor'];
                $law->tms = $order->wmt*(100-$this->law['humidity'])/100;
                $law->tmns = $law->tms*(100-$law->decrease)/100;

                $protection->settlement_id = $settlement->id;
                $protection->copper = $this->requirement['protection']['copper'];
                $protection->silver = $this->requirement['protection']['silver'];
                $protection->gold = $this->requirement['protection']['gold'];
                $protection->save();

                $deduction->settlement_id = $settlement->id;
                $deduction->copper = $this->requirement['deduction']['copper'];
                $deduction->silver = $this->requirement['deduction']['silver'];
                $deduction->gold = $this->requirement['deduction']['gold'];
                $deduction->save();

                $refinement->settlement_id = $settlement->id;
                $refinement->copper = $this->requirement['refinement']['copper'];
                $refinement->silver = $this->requirement['refinement']['silver'];
                $refinement->gold = $this->requirement['refinement']['gold'];
                $refinement->save();

                $requirement->settlement_id = $settlement->id;
                $requirement->maquila = $this->requirement['other']['maquila'];
                $requirement->analysis = $this->requirement['other']['analysis'];
                $requirement->stevedore = $this->requirement['other']['stevedore'];
                $requirement->save();

                $penalty->settlement_id = $settlement->id;
                $penalty->arsenic = $this->penalty['arsenic'];
                $penalty->antomony = $this->penalty['antomony'];
                $penalty->lead = $this->penalty['lead'];
                $penalty->zinc = $this->penalty['zinc'];
                $penalty->bismuth = $this->penalty['bismuth'];
                $penalty->mercury = $this->penalty['mercury'];

                $allowedAmount->settlement_id = $settlement->id;
                $allowedAmount->arsenic = $this->maximum['arsenic'];
                $allowedAmount->antomony = $this->maximum['antomony'];
                $allowedAmount->lead = $this->maximum['lead'];
                $allowedAmount->zinc = $this->maximum['zinc'];
                $allowedAmount->bismuth = $this->maximum['bismuth'];
                $allowedAmount->mercury = $this->maximum['mercury'];

                $penaltyPrice->settlement_id = $settlement->id;
                $penaltyPrice->arsenic = $this->price['arsenic'];
                $penaltyPrice->antomony = $this->price['antomony'];
                $penaltyPrice->lead = $this->price['lead'];
                $penaltyPrice->zinc = $this->price['zinc'];
                $penaltyPrice->bismuth = $this->price['bismuth'];
                $penaltyPrice->mercury = $this->price['mercury'];

                $payableTotal->settlement_id = $settlement->id;

                $payableTotalCopperPercent = $this->law['copper']/100*$this->percentagePayable['copper']/100-$this->requirement['deduction']['copper']/100;
                $payableTotal->unit_price_copper = floor((($this->internationalPayment['copper'] - $this->requirement['protection']['copper'])*2204.62)*1000)/1000;
                $payableTotal->total_price_copper = floor($payableTotal->unit_price_copper*$payableTotalCopperPercent*1000)/1000;

                $payableTotalSilverPercent = floor(((floor($this->law['silver']*$this->law['silverFactor']*1000)/1000)*$this->percentagePayable['silver']/100-$this->requirement['deduction']['silver'])*100)/100;
                $payableTotal->unit_price_silver = $this->internationalPayment['silver'] - $this->requirement['protection']['silver'];
                $payableTotal->total_price_silver = floor(($payableTotal->unit_price_silver*$payableTotalSilverPercent)*1000)/1000;

                $payableTotalGoldPercent =floor(((floor($this->law['gold']*$this->law['goldFactor']*1000)/1000)*$this->percentagePayable['gold']/100-$this->requirement['deduction']['gold'])*100)/100;
                $payableTotal->unit_price_gold = $this->internationalPayment['gold'] - $this->requirement['protection']['gold'];
                $payableTotal->total_price_gold = floor(($payableTotal->unit_price_gold*$payableTotalGoldPercent)*1000)/1000;

                $deductionTotal->settlement_id = $settlement->id;
                $deductionTotal->unit_price_copper = floor(2204.62*$this->requirement['refinement']['copper']*10000)/10000;
                $deductionTotal->total_price_copper = floor(($payableTotalCopperPercent*$deductionTotal->unit_price_copper)*1000)/1000;

                $deductionTotal->unit_price_silver = $this->requirement['refinement']['silver'];
                $deductionTotal->total_price_silver = floor($payableTotalSilverPercent*$deductionTotal->unit_price_silver*1000)/1000;

                $deductionTotal->unit_price_gold = $this->requirement['refinement']['gold'];
                $deductionTotal->total_price_gold = floor($payableTotalGoldPercent*$deductionTotal->unit_price_gold*1000)/1000;

                $deductionTotal->maquila = $this->requirement['other']['maquila'];
                $deductionTotal->analysis = $this->requirement['other']['analysis']/$law->tmns;
                $deductionTotal->stevedore = $this->requirement['other']['stevedore']/$order->wmt;

                $penaltyTotal->settlement_id = $settlement->id;

                $penaltyTotal->leftover_arsenic = $this->penalty['arsenic'] - $this->maximum['arsenic'] > 0 ? $this->penalty['arsenic'] - $this->maximum['arsenic'] : 0;
                $penaltyTotal->leftover_antomony = $this->penalty['antomony'] - $this->maximum['antomony'] > 0 ? $this->penalty['antomony'] - $this->maximum['antomony'] : 0;
                $penaltyTotal->leftover_lead = $this->penalty['lead'] - $this->maximum['lead'] > 0 ? $this->penalty['lead'] - $this->maximum['lead'] : 0;
                $penaltyTotal->leftover_zinc = $this->penalty['zinc'] - $this->maximum['zinc'] > 0 ? $this->penalty['zinc'] - $this->maximum['zinc'] : 0;
                $penaltyTotal->leftover_bismuth = $this->penalty['bismuth'] - $this->maximum['bismuth'] > 0 ? $this->penalty['bismuth'] - $this->maximum['bismuth'] : 0;
                $penaltyTotal->leftover_mercury = $this->penalty['mercury'] - $this->maximum['mercury'] > 0 ? $this->penalty['mercury'] - $this->maximum['mercury'] : 0;

                $penaltyTotal->total_arsenic = $penaltyTotal->leftover_arsenic*$penaltyPrice->arsenic*10;
                $penaltyTotal->total_antomony = floor($penaltyTotal->leftover_antomony*$penaltyPrice->antomony*10000)/1000;
                $penaltyTotal->total_lead = $penaltyTotal->leftover_lead*$penaltyPrice->lead;
                $penaltyTotal->total_zinc = $penaltyTotal->leftover_zinc*$penaltyPrice->zinc;
                $penaltyTotal->total_bismuth = $penaltyTotal->leftover_bismuth*$penaltyPrice->bismuth*100;
                $penaltyTotal->total_mercury = $penaltyTotal->leftover_mercury*$penaltyPrice->mercury/20;

                $settlementTotal->settlement_id = $settlement->id;

                $settlementTotal->payable_total = $payableTotal->total_price_copper+$payableTotal->total_price_silver+$payableTotal->total_price_gold;
                $settlementTotal->deduction_total = $deductionTotal->total_price_copper+$deductionTotal->total_price_silver+$deductionTotal->total_price_gold+$deductionTotal->maquila+$deductionTotal->analysis+$deductionTotal->stevedore;
                $settlementTotal->penalty_total = $penaltyTotal->total_arsenic+$penaltyTotal->total_antomony+$penaltyTotal->total_lead+$penaltyTotal->total_zinc+$penaltyTotal->total_bismuth+$penaltyTotal->total_mercury;
                $settlementTotal->unit_price = $settlementTotal->payable_total-$settlementTotal->deduction_total-$settlementTotal->penalty_total;
                $settlementTotal->batch_price = $settlementTotal->unit_price*$law->tmns;
                $settlementTotal->igv = $this->invoice == 1 ? $settlementTotal->batch_price*0.18 : 0;
                $settlementTotal->detraccion = $this->invoice == 1 ? ($settlementTotal->batch_price+$settlementTotal->igv)*0.1 : 0;
                $settlementTotal->total = $settlementTotal->batch_price+$settlementTotal->igv-$settlementTotal->detraccion;

                $penalty->save();
                $penaltyPrice->save();
                $penaltyTotal->save();
                $law->save();
                $payableTotal->save();
                $order->save();
                $allowedAmount->save();
                $deductionTotal->save();
                $settlementTotal->save();
                $settlement->save();

                $this->alert('success',"Liquidación éxitosa");
                $this->emit('refreshDatatable');
                $this->resetExcept('date');
            },2);
        }catch(PDOException $e){
            $this->alert('error', $e->getMessage());
        }catch(\Exception $e){
            $this->alert('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settlement.modal');
    }
}
