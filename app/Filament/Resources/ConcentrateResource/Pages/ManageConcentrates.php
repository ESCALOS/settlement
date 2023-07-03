<?php

namespace App\Filament\Resources\ConcentrateResource\Pages;

use App\Filament\Resources\ConcentrateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageConcentrates extends ManageRecords
{
    protected static string $resource = ConcentrateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
