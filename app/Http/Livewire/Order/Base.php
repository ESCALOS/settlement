<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;

class Base extends Component
{
    public $model = 'Order';
    public $columns = ['fecha', 'ticket', 'ruc_cliente', 'concentrado', 'simbolo', 'tmh', 'procedencia', 'ruc_transportista', 'numero_de_placa', 'guia_de_transporte', 'guia_de_remision', 'ruc_balanza'];

    public function openModal(){
        $this->emitTo('order.modal','openModal',0);
    }

    public function openImportModal(){
        $this->emitTo('import-modal','openImportModal');
    }

    public function render()
    {
        return view('livewire.order.base');
    }
}
