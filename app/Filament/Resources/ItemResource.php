<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ItemExporter;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use function Termwind\render;

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
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('zone')
                    ->label('Zone')
                    ->options(fn(Forms\Get $get) => dupeKeys( Item::filterZoneByContinent($get('continent')) ))
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('instance')
                    ->label('Instance')
                    ->options(fn(Forms\Get $get) => dupeKeys(Item::filterInstanceByZone($get('zone'))))
                    ->required(),
                Forms\Components\Select::make('droptype')
                    ->label('Drop Type')
                    ->required()
                    ->options(dupeKeys( Item::DROPTYPES )),
                Forms\Components\Select::make('feature')
                    ->label('Feature')
                    ->options(dupeKeys( Item::FEATURES)),
                Forms\Components\Select::make('slot')
                    ->label('Slot')
                    ->required()
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
            ->headerActions([
                /*
                Tables\Actions\ExportAction::make()
                    ->exporter(ItemExporter::class),
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
                                foreach($decodedData as $itemData) {
                                    if (array_key_exists('id', $itemData) && ($item = Item::find($itemData['id']))) {
                                            Log::info('Updating Item: ' . $itemData['name']);
                                            $item->update($itemData);
                                            $item->save();
                                    } else {
                                        Log::info('Creating Item: ' . $itemData['name']);
                                        $item = Item::create($itemData);
                                        $item->save();
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
                        $items = Item::all();
                        $array_data = [];
                        foreach ($items as $item) {
                            $array_data[] = $item->toArray();
                        }

                        return response()->streamDownload(function () use ($array_data) {
                            echo json_encode($array_data, JSON_PRETTY_PRINT);
                        }, 'items.json', [
                            'Content-Type' => 'application/json',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\ExportBulkAction::make()
                    ->exporter(ItemExporter::class)
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
