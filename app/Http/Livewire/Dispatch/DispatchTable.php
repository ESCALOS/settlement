<?php

namespace App\Http\Livewire\Dispatch;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Dispatch;

class DispatchTable extends DataTableComponent
{
    protected $model = Dispatch::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Fecha", "date_blending")
                ->sortable(),
            Column::make("Lote", "batch")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
