@php
    // see if a domain is specified in the evironment
    $domain = ((!empty(getenv('WOWHEAD_DOMAIN'))) ? "&domain=".getenv('WOWHEAD_DOMAIN') : '');
    $dsub = ((getenv('WOWHEAD_DOMAIN') === 'classic') ? "classic/" : '');
    $iconsize = ((!empty(getenv('WOWHEAD_ICONSIZE'))) ? getenv('WOWHEAD_ICONSIZE') : 'small');

    $record = $getRecord();
    // support for record type Item and record type Need
    $item = null;
    if($record instanceof \App\Models\Item) {
        $item = $record;
    } elseif(method_exists($record, 'item') && ($record->item instanceof \App\Models\Item)) {
        $item = $record->item;
    }
@endphp
<a data-wowhead="item={{ $item->wowhead_id }}" target="_blank" data-wh-icon-size="{{ $iconsize }}"
 href="https://www.wowhead.com/{{ $dsub }}item={{ $item->wowhead_id }}{{ $domain }}">{{ $item->name }}</a>
