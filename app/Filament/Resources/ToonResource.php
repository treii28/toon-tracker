<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToonResource\Pages;
use App\Filament\Resources\ToonResource\RelationManagers;
use App\Models\Classification;
use App\Models\Toon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToonResource extends Resource
{
    protected static ?string $model = Toon::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Manage Toons';
    protected static ?string $navigationGroup = 'Toons';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('guild')
                    ->label('Guild')
                    ->required(),
                Forms\Components\TextInput::make('level')
                    ->numeric()
                    ->label('Level')
                    ->minValue(1)
                    ->maxValue(60)
                    ->default(60)
                    ->step(1)
                    ->required(),
                Forms\Components\Radio::make('gender')
                    ->label('Gender')
                    ->options(['Female' => "Female",'Male' => "Male"])
                    ->default('Female')
                    ->required(),
                Forms\Components\Radio::make('role')
                    ->label('Role')
                    ->options(['Tank' => "Tank",'Healer' => "Healer", 'DPS' => "DPS"])
                    ->default('DPS')
                    ->required(),
                Forms\Components\Select::make('realm')
                    ->label('Realm')
                    ->options(['Mankrik' => "Mankrik", 'Pagle' => "Pagle", 'Ashkandi' => "Ashkandi"])
                    ->default('Mankrik')
                    ->required(),
                Forms\Components\Select::make('class_id')
                    ->label('Race-Class-Spec')
                    ->required()
                    ->options(Classification::getAllSpecs()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classification.race')
                    ->label('Race')
                    ->formatStateUsing(function ($state, Toon $toon) {
                        $cFmt = sprintf(
                            "%s:%s",
                            $toon->classification->faction,
                            $toon->classification->race
                        );
                        return $cFmt;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('classification.spec')
                    ->label('Spec')
                    ->formatStateUsing(function ($state, Toon $toon) {
                        $cFmt = sprintf(
                            "%s:%s",
                            $toon->classification->class,
                            $toon->classification->spec
                        );
                        return $cFmt;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->sortable(),
                Tables\Columns\TextColumn::make('realm')
                    ->label('Realm')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guild')
                    ->label('Guild')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListToons::route('/'),
            'create' => Pages\CreateToon::route('/create'),
            'edit' => Pages\EditToon::route('/{record}/edit'),
        ];
    }
}
