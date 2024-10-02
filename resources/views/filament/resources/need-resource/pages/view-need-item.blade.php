
<x-filament-panels::page>
    {{ $this->infolist(\Filament\Infolists\Infolist::make()
->record($this->getRecord())) }}
</x-filament-panels::page>
