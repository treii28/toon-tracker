<?php

namespace App\Filament\Resources\NeedResource\Pages;

use App\Filament\Resources\NeedResource;
use App\Filament\Tables\Columns\WowTooltip;
use App\Models\Need;
use App\Models\Item;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;

class NeedsByInstance extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    protected static string $model = Need::class;
    protected static string $resource = NeedResource::class;

    protected static string $view = 'filament.resources.need-resource.pages.needs-by-instance';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationGroup = 'Item Needs';
    protected static ?int $navigationSort = 5;
    //protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $header = parent::getHeader();
        return view('components.custom-header', ['header' => $header]);
    }

    public $instance;
    public $needItems;

    public function mount($instance=null): void
    {
        $this->instance = (!empty($instance)) ? $instance : null;
        $needItems = Need::getNeedsByInstance($instance);
        $this->needItems = $needItems;
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('toon.name')
                    ->label('Toon')
                    ->searchable()
                    ->sortable(),
                WowTooltip::make('item')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.slot')
                    ->label('Slot')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->searchable()
                    ->sortable(),
                /*
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable(),
                */
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make('edit need')
                    ->label('Edit Need')
                    ->url(fn (Need $record): string => route('filament.admin.resources.needs.edit', $record)),
                Tables\Actions\EditAction::make('edit item')
                    ->label('Edit Item')
                    ->url(fn (Need $record): string => route('filament.admin.resources.items.edit', $record->item)),
                Tables\Actions\DeleteAction::make('delete need')
                    ->action(function (Need $record) { $record->delete(); }),

                /*
                Tables\Actions\ViewAction::make('view need')
                ->url(fn (Need $record): string => route('admin.needs.view', $record)),
                Tables\Actions\DeleteAction::make('delete need')
                    ->url(fn (Need $record): string => route('admin.needs.delete', $record))
                */
            ])
            ->defaultSort('toon.name')
            ->defaultGroup(
                Tables\Grouping\Group::make('item.instance')
                    ->getKeyFromRecordUsing(fn ($record) => $record->item->instance)
                    ->getTitleFromRecordUsing(fn ($record) => $record->item->instance)
                    ->label('')
            )
            ->query(
                function() {
                    if(!empty($this->instance)) {
                        return Need::whereHas('item', function($query) {
                            $query->where('instance', $this->instance);
                        });
                    } else {
                        return Need::query();
                    }
                }
            )
            ->paginated([25, 50, 100, 'all']);
    }
}
