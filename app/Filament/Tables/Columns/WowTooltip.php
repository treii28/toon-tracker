<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns;

class WowTooltip extends Columns\Column
{
    use Columns\Concerns\CanFormatState;

    protected string $view = 'filament.components.wow-tooltip';
}
