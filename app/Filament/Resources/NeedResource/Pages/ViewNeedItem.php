<?php

namespace App\Filament\Resources\NeedResource\Pages;

use App\Filament\Infolists\Components\WowTooltip;
use App\Filament\Resources\NeedResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;

class ViewNeedItem extends ViewRecord
{
    protected static string $resource = NeedResource::class;
    protected static string $view = 'filament.resources.need-resource.pages.view-need-item';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $header = parent::getHeader();
        return view('components.custom-header', ['header' => $header]);
    }

    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('toon.name')
                    ->label('Toon'),
                WowTooltip::make('item.name')
                    ->label('Item'),
                Infolists\Components\TextEntry::make('priority')
                    ->label('Priority'),
                Infolists\Components\TextEntry::make('quantity')
                    ->label('Quantity'),
                Infolists\Components\TextEntry::make('notes')
                    ->label('Need Notes'),
                Infolists\Components\TextEntry::make('item.instance')
                    ->label('Item Instance'),
                Infolists\Components\TextEntry::make('item.slot')
                    ->label('Item Slot'),
                Infolists\Components\TextEntry::make('item.sources')
                    ->label('Item Sources'),
                Infolists\Components\TextEntry::make('item.notes')
                    ->label('Item Notes'),
            ]);
    }

    public function getInfolist(string $name): Infolists\Infolist
    {
        return parent::getInfolist($name);
    }
}
