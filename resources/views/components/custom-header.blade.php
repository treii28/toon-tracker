{!! $header !!}
<script>
    const whTooltips = {colorLinks: true, iconizeLinks: true, renameLinks: true};

    var rooTimeout = null;

    function refreshOnlyOnce() {
        if(rooTimeout)
            clearTimeout(rooTimeout);
        rooTimeout = setTimeout(doRefresh, 300);
    }

    function doRefresh() {
        //console.log('refreshing wowhead tooltips');
        WH.Tooltips.refreshLinks();
    }

    window.onload = function() {
        setTimeout(function() {
            Livewire.hook('morph.updated', ({ el, component }) => {
                refreshOnlyOnce()
            })
        }, 1500)
    };
</script>
<script src="https://wow.zamimg.com/js/tooltips.js"></script>

@if ($heading = $this->getHeading())
    @php
        $subheading = $this->getSubheading();
    @endphp

    <x-filament-panels::header
        :actions="$this->getCachedHeaderActions()"
        :breadcrumbs="filament()->hasBreadcrumbs() ? $this->getBreadcrumbs() : []"
        :heading="$heading"
        :subheading="$subheading"
    >
        @if ($heading instanceof \Illuminate\Contracts\Support\Htmlable)
            <x-slot name="heading">
                {{ $heading }}
            </x-slot>
        @endif

        @if ($subheading instanceof \Illuminate\Contracts\Support\Htmlable)
            <x-slot name="subheading">
                {{ $subheading }}
            </x-slot>
        @endif
    </x-filament-panels::header>
@endif
