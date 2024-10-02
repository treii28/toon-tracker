<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationGroup = 'Items';
    protected static ?string $navigationLabel = 'Manage Items';

    protected static ?int $navigationSort = 2;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('wowhead_id')
                    ->label('Wowhead ID')
                    ->callAfterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {

                    }),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\Select::make('continent')
                    ->label('Continent')
                    ->options(dupeKeys( Item::getContinents() ))
                    ->required(),
                Forms\Components\Select::make('zone')
                    ->label('Zone')
                    ->options(dupeKeys( Item::getZoneList() ))
                    ->required(),
                Forms\Components\Select::make('instance')
                    ->label('Instance')
                    ->options(dupeKeys( Item::getInstanceList() ))
                    ->required(),
                Forms\Components\Select::make('droptype')
                    ->label('Drop Type')
                    ->options(dupeKeys( Item::DROPTYPES )),
                Forms\Components\Select::make('feature')
                    ->label('Feature')
                    ->options(dupeKeys( Item::FEATURES)),
                Forms\Components\Select::make('slot')
                    ->label('Slot')
                    ->options(dupeKeys( Item::SLOTS )),
                Forms\Components\Checkbox::make('boe')
                    ->label('BoE'),
                Forms\Components\Checkbox::make('random_enchant')
                    ->label('Randomly Enchanted'),
                Forms\Components\Textarea::make('sources')
                    ->label('Source(s)'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes'),
            ]);
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \App\Filament\Tables\Columns\WowTooltip::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot')
                    ->label('Slot')
                    ->sortable(),
                /*
                Tables\Columns\TextColumn::make('continent')
                    ->label('Continent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->label('Zone')
                    ->sortable(),
                */
                Tables\Columns\TextColumn::make('instance')
                    ->label('Instance')
                    ->sortable(),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
