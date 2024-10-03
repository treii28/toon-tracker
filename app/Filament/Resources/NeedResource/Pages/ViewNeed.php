<?php

namespace App\Filament\Resources\NeedResource\Pages;

use App\Filament\Resources\NeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNeed extends ViewRecord
{
    protected static string $resource = NeedResource::class;


    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $header = parent::getHeader();
        return view('components.custom-header', ['header' => $header]);
    }
}
