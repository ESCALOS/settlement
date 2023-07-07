<?php

namespace App\Http\Livewire\Order;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['orders.settled']);
        $this->setSearchLazy();
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
                    }elseif($value === '2'){
                        $builder->where('settled',false);
                    }
                }),
        ];
    }

    public function delete($id){
        if(Order::find($id)->settled){
            $this->alert('error','Orden Liquidada');
        }else{
           $deleteCount = Order::where('id',$id)->where('settled',false)->delete();
        }
        if($deleteCount == 0){
            $this->alert('error',"No se eliminó la órden");
        }else if($deleteCount > 0){
            $this->alert('success',"Se eliminó la órden");
        }
    }
}
