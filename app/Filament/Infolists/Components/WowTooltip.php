<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\TextEntry;

class WowTooltip extends TextEntry
{
    protected function setUp(): void
    {
        parent::setUp();

        // Add any custom setup logic here
    }

    public function render(): \Illuminate\View\View
    {
        return view('filament.components.wow-tooltip', [
            'record' => $this->getRecord(),
        ]);
    }
}
