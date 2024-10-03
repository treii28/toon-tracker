<?php

namespace App\Filament\Resources\NeedResource\Pages;

use App\Filament\Resources\NeedResource;
use App\Filament\Tables\Columns\WowTooltip;
use App\Models\Need;
use App\Models\Item;
use App\Models\Toon;
use Filament\Panel;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class NeedsByToon extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $model = Need::class;
    protected static string $resource = NeedResource::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Item Needs';
    protected static ?int $navigationSort = 4;
    //protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.resources.need-resource.pages.needs-by-toon';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $header = parent::getHeader();
        return view('components.custom-header', ['header' => $header]);
    }

    public $toon;
    public $needitems;

    public function mount($toon=null): void
    {
        if(!empty($toon)) {
            $this->toon = Toon::searchForToon($toon);
            $needItems = [];
            foreach($this->toon->needs as $need) {
                $needItems[] = $need->item;
            }
            $this->needitems = $needItems;
        } else {
            $this->needitems = Need::all();
        }
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
                Tables\Columns\TextColumn::make('item.instance')
                    ->label('Instance')
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
                */
            ])
            ->defaultSort('item.instance')
            ->defaultGroup(
                Tables\Grouping\Group::make('toon')
                    ->getKeyFromRecordUsing(fn ($record) => $record->toon->name)
                    ->getTitleFromRecordUsing(fn ($record) => $record->toon->name)
                    ->label('')
            )
            ->query(
                function() {
                    if($this->toon instanceof Toon) {
                        $name = $this->toon->name;
                        return Need::whereHas('toon', function($query) use ($name) {
                            $query->where('name', $name);
                        });
                    } else {
                        return Need::query();
                    }
                }
            )
            ->paginated([25, 50, 100, 'all']);
    }
}
