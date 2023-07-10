<?php

namespace App\Http\Livewire\Sent;

use App\Helpers\Helpers;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Dispatch;
use App\Models\DispatchDetail;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SentTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return Dispatch::query()
            ->where('dispatches.shipped',1)
            ->orderBy('id','DESC');
    }


    public function columns(): array
    {
        return [
            Column::make("Fecha", "date_blending")
                ->sortable(),
            Column::make("Lote", "batch")
                ->sortable(),
            Column::make("TMH", "Total.wmt")
                ->format(fn($value) => number_format($value,3)),
            Column::make("TMNS", "Total.dnwmt")
                ->format(fn($value) => number_format($value,3)),
            Column::make("Total", "Total.amount")
                ->format(fn($value) => '$ '.number_format($value,2)),
            Column::make("Acciones","id")
            ->format(fn ($value) => view('livewire.dispatch.actions',[
                'id' => $value,
                'shipped' => true
            ]))
            ->collapseOnTablet(),
        ];
    }

    public function openPreview($id){
        $settlements = Helpers::getDispatchDetails($id);
        $this->emitTo('blending.preview','openModal',$settlements);
    }

    public function unship($id){
        try{
            $dispatch = Dispatch::find($id);
            $dispatch->shipped = false;
            $dispatch->save();
            $this->alert('success','Enviado');
        }catch(\PDOException $e){
            $this->alert('error',$e->getMessage());
        }catch(\Exception $e){
            $this->alert('error',$e->getMessage());
        }
    }
}
