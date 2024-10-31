<?php

namespace App\Filament\Exports;

use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ItemExporter extends Exporter
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            //ExportColumn::make('id'),
            ExportColumn::make('name'),
            ExportColumn::make('continent'),
            ExportColumn::make('zone'),
            ExportColumn::make('instance'),
            ExportColumn::make('boe'),
            ExportColumn::make('random_enchant'),
            ExportColumn::make('droptype'),
            ExportColumn::make('sources'),
            ExportColumn::make('slot'),
            ExportColumn::make('feature'),
            ExportColumn::make('notes'),
            ExportColumn::make('wowhead_id'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
