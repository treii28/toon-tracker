<?php

namespace App\Filament\Resources\NeedResource\Pages;

use App\Filament\Resources\NeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNeeds extends ListRecords
{
    protected static string $resource = NeedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $header = parent::getHeader();
        return view('components.custom-header', ['header' => $header]);
    }
}
