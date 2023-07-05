<?php

namespace App\Http\Livewire\Order;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderTable extends DataTableComponent
{
    use LivewireAlert;

    public $orderId = [];

    protected $listeners = [
        'refreshDatatable' => '$refresh',
        'clearSelected' => 'clearSelected',
        'delete' => 'delete'
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['orders.settled']);
        $this->setSearchLazy();
        $this->setBulkActions([
            'deleteBulk' => 'Eliminar',
        ]);
    }

    public function builder(): Builder
    {
        return Order::query()
            ->orderBy('id','DESC');
    }


    public function columns(): array
    {
        return [
            Column::make("Fecha", "date")
                ->sortable(),
            Column::make('Lote','batch')
                ->searchable()
                ->collapseOnTablet(),
            Column::make('Concentrado','concentrate.concentrate')
                ->searchable(),
            Column::make('TMH','wmt')
                ->format(fn ($value) => number_format($value,2))
                ->collapseOnTablet(),
            Column::make('Cliente','client.name')
                ->searchable()
                ->collapseOnTablet(),
            Column::make('Acciones','id')
                ->format(fn ($value,$row) => view('livewire.order.actions',[
                    'id' => $value,
                    'settled' => $row['settled']
                ]))
                ->collapseOnTablet(),
        ];
    }

    public function filters(): array{
        return [
            SelectFilter::make('¿Estado?','settled')
                ->options([
                    '' => 'Todos',
                    '1' => 'Liquidado',
                    '2' => 'No liquidado'
                ])
                ->filter(function (Builder $builder, string $value){
                    if($value === '1'){
                        $builder->where('settled',true);
                    }elseif($value === '0'){
                        $builder->where('settled',false);
                    }
                }),
        ];
    }

    public function deleteBulk() {
        $this->confirmDelete($this->getSelected());
    }

    public function confirmDelete($orders){
        $this->orderId = [];
        $orders = Arr::wrap($orders);
        $title = "";
        if(is_array($orders)){
            foreach($orders as $order){
                if(!Order::find($order)->settled){
                    $this->orderId[] = $order;
                }
            }
        }
        $selected = sizeof($this->orderId);
        if(!$this->orderId){
            $this->alert('error','No se puede eliminar');
            return;
        }else if($selected == 1){
            $title = "¿Seguro de eliminar la orden?";
        }else{
            $title = "¿Seguro de eliminar {$selected} órdenes?";
        }

        $this->alert('question', $title, [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Aceptar',
            'showDenyButton' => true,
            'denyButtonText' => 'Cancelar',
            'onConfirmed' => 'delete',
            'position' => 'center',
            'toast' => false,
            'timer'=> null
        ]);
    }

    public function delete(){
        $deleteCount =  Order::whereIn('id',$this->orderId)->where('settled',false)->delete();
        if($deleteCount == 0){
            $this->alert('error',"No se eliminó ninguna órden");
        }else if($deleteCount == 1){
            $this->alert('success',"Se eliminó una órden");
        }else{
            $this->alert('success',"Se eliminaron {$deleteCount} órdenes");
        }
        $this->emit('clearSelected');
    }
}
