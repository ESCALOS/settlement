<?php

namespace App\Http\Livewire\Order;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchLazy();
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
            BooleanColumn::make('¿Liquidado?','settled')
                ->collapseOnTablet(),
        ];
    }
}
