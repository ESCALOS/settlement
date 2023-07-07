<?php

namespace App\Http\Livewire\Order;

use App\Helpers\Helpers;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use PDOException;

class Modal extends Component
{
    use LivewireAlert;

    public $open = false;
    public $orderId = 0;
    public $date;
    public $ticket = '';
    public $client = [
        'id' => 0,
        'documentNumber' => '',
        'name' => '',
        'address' => ''
    ];
    public $concentrateId = '';
    public $wmt = '';
    public $origin = '';
    public $carriage = [
        'id' => 0,
        'documentNumber' => '',
        'name' => ''
    ];
    public $plateNumber = '';
    public $transportGuide = '';
    public $deliveryNote = '';
    public $weighing = [
        'id' => 0,
        'documentNumber' => '',
        'name' => ''
    ];
    public $settled = false;

    protected $listeners = ['openModal'];

    protected function rules(){
        return [
            'ticket' => 'required|unique:orders,ticket,'.$this->orderId,
            'client.documentNumber' => 'required',
            'client.name' => 'required',
            'concentrateId' => 'required|exists:concentrates,id',
            'wmt' => 'required|decimal:0,3',
            'origin' => 'required',
            'carriage.documentNumber' => 'required',
            'carriage.name' => 'required',
            'plateNumber' => 'required|size:7',
            'weighing.documentNumber' => 'required',
            'weighing.name' => 'required'
        ];
    }

    public function messages(){
        return [
            'ticket.required' => "El ticket es requerido",
            'ticket.unique' => "El ticket ya existe",
            'client.documentNumber.required' => "El número del documento del cliente es requerido",
            'client.name.required' => "El nombre del cliente es requerido",
            'concentrateId.required' => "El concentrado es requerido",
            'wmt.required' => "La TMH es requerida",
            'wmt.decimal' => "Máximo 3 decimales",
            'origin.required' => "La procedencia es requerida",
            'carriage.documentNumber.required' => 'El ruc del transportista es requerido',
            'carriage.name.required' => 'La razón social del transportuista es requerida',
            'plateNumber.required' => 'La placa es requerida',
            'plateNumber.size' => 'Placa incorrecta',
            'weighing.documentNumber.required' => 'El ruc de balanza es requerido',
            'weighing.name.required' => 'La razón social de la balanza es requerida',
        ];
    }

    public function mount(){
        $this->date = Carbon::now()->toDateString();
    }

    public function openModal($orderId){
        $this->resetValidation();
        $this->resetExcept('open','date');
        $this->orderId = $orderId;
        if($orderId){
            $order = Order::find($orderId);
            $this->date = Carbon::parse($order->date)->format('Y-m-d');
            $this->ticket = $order->ticket;
            $this->client['id'] = $order->client_id;
            $this->client['documentNumber'] = $order->Client->document_number;
            $this->client['name'] = $order->Client->name;
            $this->client['address'] = $order->Client->address;
            $this->concentrateId = $order->concentrate_id;
            $this->wmt = floatval($order->wmt);
            $this->origin = $order->origin;
            $this->carriage['id'] = $order->carriage_company_id;
            $this->carriage['documentNumber'] = $order->Carriage->document_number;
            $this->carriage['name'] = $order->Carriage->name;
            $this->plateNumber = $order->plate_number;
            $this->transportGuide = $order->transport_guide;
            $this->deliveryNote = $order->delivery_note;
            $this->weighing['id'] = $order->weighing_scale_company_id;
            $this->weighing['documentNumber'] = $order->WeighingScale->document_number;
            $this->weighing['name'] = $order->WeighingScale->name;
            $this->settled = $order->settled;
        }
        $this->open = true;
    }

    public function updatedClient(){
        $helper = new Helpers();
        $entity = $helper->searchRuc($this->client['documentNumber']);
        if(is_null($entity)){
            $this->client['name'] = '';
            $this->client['address'] = '';
            $this->alert('error', 'Numero incorrecto', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }else{
            $this->client['id'] = $entity->id;
            $this->client['name'] = $entity->name;
            $this->client['address'] = $entity->address;
            $this->alert('success', 'Encontrado', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }
    }

    public function updatedCarriage(){
        if(strlen($this->carriage['documentNumber']) != 11){
            $this->alert('warning','RUC incorrecto');
            return;
        }
        $helper = new Helpers();
        $entity = $helper->searchRuc($this->carriage['documentNumber']);
        if(is_null($entity)){
            $this->carriage['name'] = '';
            $this->alert('error', 'Numero incorrecto', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }else{
            $this->carriage['id'] = $entity->id;
            $this->carriage['name'] = $entity->name;
            $this->alert('success', 'Encontrado', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }
    }
    public function updatedWeighing(){
        if(strlen($this->weighing['documentNumber']) != 11){
            $this->alert('warning','RUC incorrecto');
            return;
        }
        $helper = new Helpers();
        $entity = $helper->searchRuc($this->weighing['documentNumber']);
        if(is_null($entity)){
            $this->weighing['name'] = '';
            $this->alert('error', 'Numero incorrecto', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }else{
            $this->weighing['id'] = $entity->id;
            $this->weighing['name'] = $entity->name;
            $this->alert('success', 'Encontrado', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }
    }

    public function save(){
        $this->validate();
        if($this->orderId > 0){
            $order = Order::find($this->orderId);
            if($order->settled){
                $this->alert('error','Orden ya liquidada');
                return;
            }
        }else{
            $order = new Order();
            $order->batch = Helpers::createBatch('orders','O');
        }
        try{
            DB::transaction(function () use ($order){
                $order->date = $this->date;
                $order->ticket = strtoupper($this->ticket);
                $order->client_id = $this->client['id'];
                $order->concentrate_id = $this->concentrateId;
                $order->wmt = $this->wmt;
                $order->origin = strtoupper($this->origin);
                $order->carriage_company_id = $this->carriage['id'];
                $order->plate_number = strtoupper($this->plateNumber);
                $order->transport_guide = strtoupper($this->transportGuide);
                $order->delivery_note = strtoupper($this->deliveryNote);
                $order->weighing_scale_company_id = $this->weighing['id'];
                $order->user_id = Auth::user()->id;
                $order->save();
                $this->alert('success','Guardado con éxito');
                $this->emit('refreshDatatable');
                $this->reset('open');
            },3);
        }catch(PDOException $e){
            $this->alert('error','Error :'.$e->getMessage(),[
                'timer' => null,
                'toast' => false
            ]);
        }catch(Exception $e){
            $this->alert('error','Error :'.$e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.order.modal');
    }
}
