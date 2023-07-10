<?php

namespace App\Http\Livewire\Blending;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Settlement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BlendingTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['settlements.wmt_shipped']);
        $this->setSearchLazy();
        $this->setBulkActions([
            'blending' => 'Mezclar',
        ]);
    }

    public function builder(): Builder
    {
        return Settlement::query()
            ->join('orders','orders.id','settlements.order_id')
            ->where('settlements.wmt_shipped','<>',DB::raw('orders.wmt'))
            ->orderBy('id','DESC');
    }

    public function columns(): array
    {
        return [
            Column::make("Fecha", "date")
                ->sortable()
                ->collapseOnTablet(),
            Column::make("Lote","Batch")
                ->searchable()
                ->collapseOnTablet(),
            Column::make("Concentrado","Order.Concentrate.concentrate")
                ->searchable(),
            Column::make("TMH Total","Order.wmt")
                ->format(fn ($value) => number_format($value,2))
                ->collapseOnTablet(),
            Column::make("TMH sin Mezclar","Order.wmt")
                ->format(fn ($value,$row) => number_format($value-$row['wmt_shipped'],2)),
            Column::make("Cliente",'Order.Client.name')
                ->searchable()
                ->collapseOnTablet(),
            Column::make("Acciones","id")
            ->format(fn ($value,$row) => view('livewire.settlement.actions',[
                'id' => $value,
                'wmt' => $row['wmt_shipped']
            ]))
            ->collapseOnTablet(),
        ];
    }

    public function openModal($settlementId){
        $this->emitTo('settlement.modal','openModal',$settlementId,0);
    }

    public function showDetails($id){
        $this->emit('showDetails',$id);
    }

    public function blending():void{
        $this->emitTo('blending.modal', 'openModal', $this->getSelected(),0);
        $this->clearSelected();
    }
}
