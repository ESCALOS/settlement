<?php

namespace App\Http\Livewire\Dispatch;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Dispatch;
use App\Models\DispatchDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DispatchTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchLazy();
    }

    public function builder(): Builder
    {
        return Dispatch::query()
            ->where('dispatches.shipped',0)
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
                'shipped' => false
            ]))
            ->collapseOnTablet(),
        ];
    }

    public function openModal($id){
        $settlements = DispatchDetail::where('dispatch_id',$id)->get();

        $this->alert('success',$settlements->count());
    }

    public function delete($id){
        try{
            DB::transaction(function () use($id) {
                DispatchDetail::where('dispatch_id',$id)->delete();
                Dispatch::find($id)->delete();
                $this->alert('success','Mezcla deshecha');
            });
        }catch(\PDOException $e){
            $this->alert('error',$e->getMessage());
        }catch(\Exception $e){
            $this->alert('error',$e->getMessage());
        }
    }

    public function ship($id){
        try{
            $dispatch = Dispatch::find($id);
            $dispatch->shipped = true;
            $dispatch->save();
            $this->alert('success','Enviado');
        }catch(\PDOException $e){
            $this->alert('error',$e->getMessage());
        }catch(\Exception $e){
            $this->alert('error',$e->getMessage());
        }
    }
}
