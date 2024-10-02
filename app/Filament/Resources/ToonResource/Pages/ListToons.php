<?php

namespace App\Filament\Resources\ToonResource\Pages;

use App\Filament\Resources\ToonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListToons extends ListRecords
{
    protected static string $resource = ToonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
