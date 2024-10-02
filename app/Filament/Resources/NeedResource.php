<?php

namespace App\Filament\Resources;

use App\Filament\Infolists\Components\WowTooltip;
use App\Filament\Resources\NeedResource\Pages;
use App\Filament\Resources\NeedResource\RelationManagers;
use App\Models\Item;
use App\Models\Need;
use App\Models\Toon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;

class NeedResource extends Resource
{
    protected static ?string $model = Need::class;

    protected static ?string $navigationGroup = 'Item Needs';
    protected static ?string $navigationLabel = 'Manage Needs';
    protected static ?int $navigationSort = 3;
    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getSchema(): array
    {
        return [
            Forms\Components\Select::make('toon_id')
                ->required()
                ->options(Toon::getAllToons()),
            Forms\Components\Select::make('item_id')
                ->required()
                ->options(Item::getSlotItems()),
            Forms\Components\TextInput::make('priority')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(10)
                ->default(1),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->default(1),
            Forms\Components\Textarea::make('notes')
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getSchema());
    }


    public static function getViewColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('toon.name')
                ->label('Toon Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('notes')
                ->label('Need Notes')
                ->width('250px')
                ->searchable()
                ->sortable(),
            \App\Filament\Tables\Columns\WowTooltip::make('item.name')
                ->label('Item Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('item.slot')
                ->label('Item Slot')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('item.instance')
                ->label('Item Instance')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('item.sources')
                ->label('Item Sources')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('item.notes')
                ->label('Item Notes')
                ->searchable()
                ->sortable()
        ];
    }
    public static function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('toon.name')
                ->label('Toon')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('priority')
                ->label('Priority')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('item.instance')
                ->label('Instance')
                ->searchable()
                ->sortable(),
            \App\Filament\Tables\Columns\WowTooltip::make('item.name')
                ->label('Item')
                ->formatStateUsing(function ($value, $record) {
                    return $record->item;
                })
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Qty'),
        ];
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNeeds::route('/'),
            'create' => Pages\CreateNeed::route('/create'),
            'view' => Pages\ViewNeed::route('/{record}'),
            'view-with-item' => Pages\ViewNeedItem::route('/{record}/item'),
            'edit' => Pages\EditNeed::route('/{record}/edit'),
            'by-toon' => Pages\NeedsByToon::route('/by-toon/{toon?}'),
            'by-instance' => Pages\NeedsByInstance::route('/by-instance/{instance?}'),
        ];
    }
}
