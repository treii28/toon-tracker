<?php

namespace App\Filament\Resources\ToonResource\Pages;

use App\Filament\Resources\ToonResource;
use App\Models\Toon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Tables;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListToons extends ListRecords
{
    protected static string $resource = ToonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function query(): Builder
    {
        if(Auth::user()->can('view_any_user')) {
            return Toon::query();
        } else
            return Toon::query()->where('user_id', Auth::id());
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(ListToons::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('classification.faction')->label('Faction')->sortable(),
                Tables\Columns\TextColumn::make('classification.race')->label('Race')->sortable(),
                Tables\Columns\TextColumn::make('classification.spec')->label('Spec')->sortable(),
                Tables\Columns\TextColumn::make('role')->sortable(),
                Tables\Columns\TextColumn::make('realm')->sortable(),
                Tables\Columns\TextColumn::make('guild')->sortable(),
                Tables\Columns\TextColumn::make('level')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('gender')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('alliance')->label('Alliance')->query(fn (Builder $query) => $query->whereHas('classification', fn (Builder $subQuery) => $subQuery->where('faction', 'Alliance'))),
                Tables\Filters\Filter::make('horde')->label('Horde')->query(fn (Builder $query) => $query->whereHas('classification', fn (Builder $subQuery) => $subQuery->where('faction', 'Horde'))),
                Tables\Filters\Filter::make('Mankrik')->label('Mankrik realm')->query(fn (Builder $query) => $query->where('realm', "Mankrik")),
                Tables\Filters\Filter::make('Pagle')->label('Pagle realm')->query(fn (Builder $query) => $query->where('realm', "Pagle")),
                Tables\Filters\Filter::make('CRH')->label('CRH guild')->query(fn (Builder $query) => $query->where('guild', "Classic Retirement Home")),
                Tables\Filters\Filter::make('level60')->label('Level 60')->query(fn (Builder $query) => $query->where('level', 60)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (\App\Models\User $user, Toon $record) => $user->can('update', $user, $record)),
                Tables\Actions\DeleteAction::make()->visible(fn ($record) => $record->user_id === Auth::id()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('importJson')
                    ->label('Import JSON')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\Textarea::make('json_text')->label('JSON Text')->rows(10),
                        Forms\Components\FileUpload::make('json_file')->label('JSON File')->acceptedFileTypes(['application/json']),
                    ])
                    ->action(function (array $data) {
                        // Your import logic here
                    }),
                Tables\Actions\Action::make('exportAllAsJson')
                    ->label('Export as JSON')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Your export logic here
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => Auth::user()->can('deleteAny', Toon::class)),
            ]);
    }
}
