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

class SettlementTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
                ->searchable(),
            Column::make("Acciones","id")
            ->format(fn ($value,$row) => view('livewire.settlement.actions',[
                'id' => $value,
                'wmt' => $row['wmt_shipped']
            ]))
            ->collapseOnTablet(),
        ];
    }

    public function delete($id){
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
