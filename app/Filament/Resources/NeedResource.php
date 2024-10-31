<?php

namespace App\Filament\Resources;

use App\Filament\Exports\NeedExporter;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

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
            Forms\Components\TextInput::make('priority')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(10)
                ->default(1),
            Forms\Components\Select::make('filterSlot')
                ->label('Item Slot')
                ->reactive()
                ->options(Item::getSlotOptionsCached()) // Assuming you have a method to get slot options
                ->dehydrated(false), // This ensures the field is not saved
            Forms\Components\Select::make('item_id')
                ->required()
                ->label('Item')
                ->options(fn (callable $get) => Item::getSlotItems($get('filterSlot')) ),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->maxValue(500)
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
                Tables\Filters\Filter::make('crafted')
                    ->label('Crafted Item')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('droptype', 'craft'))),
                Tables\Filters\Filter::make('pvp_item')
                    ->label('PVP Item')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('droptype', 'pvp'))),
                Tables\Filters\Filter::make('container')
                    ->label('Container')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('slot', 'container'))),
                Tables\Filters\Filter::make('quest_item')
                    ->label('Quest Item')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('slot', 'quest-item'))),
                Tables\Filters\Filter::make('key')
                    ->label('Key')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('slot', 'key'))),
                Tables\Filters\Filter::make('recipe')
                    ->label('Recipe')
                    ->query(fn (Builder $query) => $query->whereHas('item', fn (Builder $subQuery) => $subQuery->where('slot', 'recipe'))),
                Tables\Filters\Filter::make('toppriority')
                    ->label('Priority 1')
                    ->query(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('priority', 1)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                /*
                Tables\Actions\ExportAction::make()
                    ->exporter(NeedExporter::class),
                */
                Tables\Actions\Action::make('importJson')
                    ->label('Import JSON')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\Textarea::make('json_text')
                            ->label('JSON Text')
                            ->rows(10),
                        Forms\Components\FileUpload::make('json_file')
                            ->label('JSON File')
                            ->acceptedFileTypes(['application/json']),
                    ])
                    ->action(function (array $data) {
                        $json = $data['json_text'] ?? null;

                        $json = null;
                        if (isset($data['json_file']) && !empty($data['json_file'])) {
                            $jsonfile = null;
                            if(($data['json_file'] instanceof \Illuminate\Http\UploadedFile) && $data['json_file']->isValid() )
                                $jsonfile = file_get_contents($data['json_file']->getRealPath());
                            elseif(file_exists($data['json_file']))
                                $jsonfile = $data['json_file'];
                            elseif(file_exists(public_path($data['json_file'])))
                                $jsonfile = public_path($data['json_file']);

                            if(!empty($jsonfile))
                                $json = file_get_contents($jsonfile);
                        }

                        if ($json) {
                            $decodedData = json_decode($json, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                foreach($decodedData as $needData) {
                                    if (array_key_exists('id', $needData) && ($need = Need::find($needData['id']))) {
                                        Log::info('Updating Need: ' . $needData['name']);
                                        $need->update($needData);
                                        $need->save();
                                    } else {
                                        Log::info('Creating Need: ' . $needData['name']);
                                        $need = Toon::create($needData);
                                        $need->save();
                                    }
                                }
                            } else {
                                return response()->json([
                                    'error' => 'Invalid JSON',
                                    'json_error' => json_last_error_msg(),
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'error' => 'No JSON data provided',
                            ], 400);
                        }
                    }),
                Tables\Actions\Action::make('exportAllAsJson')
                    ->label('Export as JSON')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $needs = Need::all();
                        $array_data = [];
                        foreach ($needs as $need) {
                            $array_data[$need->id] = $need->toArray();
                        }

                        return response()->streamDownload(function () use ($array_data) {
                            echo json_encode($array_data, JSON_PRETTY_PRINT);
                        }, 'needs.json', [
                            'Content-Type' => 'application/json',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\ExportBulkAction::make()
                    ->exporter(NeedExporter::class)

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //RelationManagers\ToonRelationManager::make('toon'),
            //RelationManagers\ItemRelationManager::make('item'),
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
            'edit' => Pages\EditNeed::route('/{record}/edit'),
            'by-toon' => Pages\NeedsByToon::route('/by-toon/{toon?}'),
            'by-instance' => Pages\NeedsByInstance::route('/by-instance/{instance?}'),
        ];
    }
}
