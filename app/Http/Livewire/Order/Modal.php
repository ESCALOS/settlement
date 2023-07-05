<?php

namespace App\Http\Livewire\Order;

use App\Models\Entity;
use App\Helpers\Helpers;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

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
        $this->date = Carbon::now()->toDateString();;
    }

    public function openModal(){
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
        if(strlen($this->carriage['documentNumber'])){
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
            $this->carriage['name'] = $entity->name;
            $this->alert('success', 'Encontrado', [
                'position' => 'top-right',
                'timer' => 2000,
                'toast' => true,
            ]);
        }
    }
    public function updatedWeighing(){
        if(strlen($this->weighing['documentNumber'])){
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

    }
    

    public function render()
    {
        return view('livewire.order.modal');
    }
}
