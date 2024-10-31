<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ToonExporter;
use App\Filament\Resources\ToonResource\Pages;
use App\Models\Classification;
use App\Models\Toon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ToonResource extends Resource
{
    const REALM_GROUPS = [
        "PVE East" => [
                "Ashkandi"        => "Ashkandi",
                "Mankrik"         => "Mankrik",
                "Pagle"           => "Pagle",
                "Westfall"        => "Westfall",
                "Windseeker"      => "Windseeker"
        ],
        "PVE West" => [
                "Atiesh"          => "Atiesh",
                "Azuresong"       => "Azuresong",
                "Myzrael"         => "Myzrael",
                "Old Blanchy"     => "Old Blanchy"
        ],
        "PVP East1" => [
                "Benediction"     => "Benediction",
                "Faerlina"        => "Faerlina",
                "Heartseeker"     => "Heartseeker",
                "Incendius"       => "Incendius",
                "Netherwind"      => "Netherwind"
        ],
        "PVP East2" => [
                "Earthfury"       => "Earthfury",
                "Herod"           => "Herod",
                "Kirtonos"        => "Kirtonos",
                "Kromcrush"       => "Kromcrush",
                "Skeram"          => "Skeram",
                "Stalagg"         => "Stalagg",
                "Sulfuras"        => "Sulfuras",
                "Thalnos"         => "Thalnos"
        ],
        "PVP West" => [
                "Anathema"        => "Anathema",
                "Arcanite Reaper" => "Arcanite Reaper",
                "Bigglesworth"    => "Bigglesworth",
                "Blaumeux"        => "Blaumeux",
                "Fairbanks"       => "Fairbanks",
                "Kurinnaxx"       => "Kurinaxx",
                "Rattlegore"      => "Rattlegore",
                "Smolderweb"      => "Smolderweb",
                "Thunderfury"     => "Thunderfury",
                "Whitemane"       => "Whitemane"
        ],
        "OCE PVP" => [
                "Arugal"          => "Arugal",
                "Felstriker"      => "Felstriker",
                "Yojamba"         => "Yojamba"
        ]
    ];

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
                    ->label('Guild'),
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
                    ->options(static::REALM_GROUPS)
                    //->options(['Mankrik' => "Mankrik", 'Pagle' => "Pagle", 'Ashkandi' => "Ashkandi"])
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
                Tables\Filters\Filter::make('alliance')
                    ->label('Alliance')
                    ->query(fn (Builder $query) => $query->whereHas('classification', fn (Builder $subQuery) => $subQuery->where('faction', 'Alliance'))),
                Tables\Filters\Filter::make('horde')
                    ->label('Horde')
                    ->query(fn (Builder $query) => $query->whereHas('classification', fn (Builder $subQuery) => $subQuery->where('faction', 'Horde'))),
                Tables\Filters\Filter::make('Mankrik')
                    ->label('Mankrik realm')
                    ->query(fn (Builder $query) => $query->where('realm', "Mankrik")),
                Tables\Filters\Filter::make('Pagle')
                    ->label('Pagle realm')
                    ->query(fn (Builder $query) => $query->where('realm', "Pagle")),
                Tables\Filters\Filter::make('CRH')
                    ->label('CRH guild')
                    ->query(fn (Builder $query) => $query->where('guild', "Classic Retirement Home")),
                Tables\Filters\Filter::make('level60')
                    ->label('Level 60')
                    ->query(fn (Builder $query) => $query->where('level', 60)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                /*
                Tables\Actions\ExportAction::make()
                    ->exporter(ToonExporter::class),
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
                        //Log::debug("in importJson action:\n" . print_r($data, true));
                        Log::debug("public path: " . public_path());
                        $json = $data['json_text'] ?? null;

                        Log::debug("json data not empty: ". (!empty($json)) ? 'true' : 'false');
                        if (isset($data['json_file']) && !empty($data['json_file'])) {
                            Log::debug("json_file is set and not empty");
                            $jsonfile = null;
                            if(($data['json_file'] instanceof \Illuminate\Http\UploadedFile) && $data['json_file']->isValid() ) {
                                Log::debug("json_file is an UploadedFile");
                                $jsonfile = file_get_contents($data['json_file']->getRealPath());
                            } elseif(file_exists(storage_path('app/public/' . $data['json_file']))) {
                                Log::debug("json_file exists in storage app/public path");
                                $jsonfile = storage_path('app/public/' . $data['json_file']);
                            }

                            Log::debug("jsonfile: " . $jsonfile);
                            if(!empty($jsonfile))
                                $json = file_get_contents($jsonfile);
                        }

                        if (!empty($json)) {
                            Log::debug("JSON data provided");
                            $decodedData = json_decode($json, true);

                            if (json_last_error() === JSON_ERROR_NONE) {
                                Log::debug("Decoded JSON: " . print_r($decodedData, true));
                                foreach($decodedData as $toonData) {
                                    $toon = null;
                                    if(array_key_exists('class_id', $toonData)) {
                                        $class = Classification::find($toonData['class_id']);
                                        unset($toonData['class_id']);
                                    }
                                    if (array_key_exists('id', $toonData) && ($toon = Toon::find($toonData['id']))) {
                                        Log::debug('Updating Toon: ' . $toonData['name']);
                                        $toon->update($toonData);
                                    } else {
                                        Log::debug('Creating Toon: ' . $toonData['name']);
                                        $toon = Toon::create($toonData);
                                    }
                                    Log::Debug('Toon: ' . print_r($toon, true));
                                    if($toon instanceof Toon) {
                                        if($class instanceof Classification) {
                                            Log::debug('Associating Classification: ' . $class->name);
                                            $toon->classification()->associate($class);
                                        }
                                    }
                                    $toon->save();
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
                    }),                Tables\Actions\Action::make('exportAllAsJson')
                    ->label('Export as JSON')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $toons = Toon::all();
                        $array_data = [];
                        foreach ($toons as $toon) {
                            $array_data[] = $toon->toArray();
                        }

                        return response()->streamDownload(function () use ($array_data) {
                            echo json_encode($array_data, JSON_PRETTY_PRINT);
                        }, 'toons.json', [
                            'Content-Type' => 'application/json',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\ExportBulkAction::make()
                    ->exporter(ToonExporter::class)
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
