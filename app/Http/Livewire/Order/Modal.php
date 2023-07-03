<?php

namespace App\Http\Livewire\Order;

use Carbon\Carbon;
use Livewire\Component;

class Modal extends Component
{
    public $open = false;
    public $orderId = 0;
    public $date;
    public $ticket = '';
    public $client = [
        'documentNumber' => '708213263',
        'name' => '',
        'address' => ''
    ];
    public $concentrateId = '';
    public $wmt = '';
    public $origin = '';
    public $carriage = [
        'documentNumber' => '',
        'name' => ''
    ];
    public $plateNumber = '';
    public $transportGuide = '';
    public $deliveryNote = '';
    public $weighing = [
        'documentNumber' => '',
        'name' => ''
    ];
    public $settled = false;

    protected $listeners = ['openModal'];

    public function mount(){
        $this->date = Carbon::now()->toDateString();;
    }

    public function openModal(){
        $this->open = true;
    }

    public function render()
    {
        return view('livewire.order.modal');
    }
}
