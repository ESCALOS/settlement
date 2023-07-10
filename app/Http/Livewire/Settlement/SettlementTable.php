<?php

namespace App\Http\Livewire\Settlement;

use App\Models\Order;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Settlement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use PDOException;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class SettlementTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['settlements.wmt_shipped']);
        $this->setSearchLazy();
    }

    public function builder(): Builder
    {
        return Settlement::query()
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
            Column::make("TMH","Order.wmt")
                ->format(fn ($value) => number_format($value,2)),
            Column::make("Cliente",'Order.Client.name')
                ->searchable()
                ->collapseOnTablet(),
            Column::make("Total","SettlementTotal.total")
                ->format(fn ($value) => '$ '.number_format($value,2))
                ->collapseOnTablet(),
            Column::make("Acciones","id")
            ->format(fn ($value,$row) => view('livewire.settlement.actions',[
                'id' => $value,
                'wmt' => $row['wmt_shipped']
            ]))
            ->collapseOnTablet(),
        ];
    }

    public function filters(): array{
        return [
            SelectFilter::make('¿Estado?')
                ->options([
                    '' => 'Todos',
                    '1' => 'Mezcla Pendiente',
                    '2' => 'Mezcla Parcial',
                    '3' => 'Mezcla Total'
                ])
                ->filter(function (Builder $builder, string $value){
                    if($value === '1'){
                        $builder->where('settlements.wmt_shipped',0);
                    }elseif($value === '2'){
                        $builder->where('settlements.wmt_shipped','>',0)->where('settlements.wmt_shipped','<',DB::raw('orders.wmt'));
                    }elseif($value === '3'){
                        $builder->where('settlements.wmt_shipped',DB::raw('orders.wmt'));
                    }
                }),
        ];
    }

    public function openModal($settlementId){
        $this->emitTo('settlement.modal','openModal',$settlementId,0);
    }

    public function showDetails($id){
        $this->emit('showDetails',$id);
    }

    public function delete(int $id):void{
        try {
            DB::transaction(function () use ($id) {
                $orderId = Settlement::find($id)->order_id;
                $count = Settlement::where('id',$id)->where('wmt_shipped',0)->delete();
                if($count > 0){
                    $order = Order::find($orderId);
                    $order->settled = false;
                    $order->save();
                    $this->alert('success','Liquidación eliminada');
                }else{
                    $this->alert('error','Liquidación en mezcla');
                }
                $this->emit('refreshDatatable');
            });
        } catch (PDOException $e) {
            $this->alert('error',$e->getMessage());
        } catch (\Exception $e) {
            $this->alert('error',$e->getMessage());
        }
    }
}
