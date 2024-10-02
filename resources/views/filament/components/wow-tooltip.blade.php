@php
    // see if a domain is specified in the evironment
    $domain = ((!empty(getenv('WOWHEAD_DOMAIN'))) ? "&domain=".getenv('WOWHEAD_DOMAIN') : '');
    $iconsize = ((!empty(getenv('WOWHEAD_ICONSIZE'))) ? getenv('WOWHEAD_ICONSIZE') : 'small');


    if(!isset($record) || !($record instanceof \App\Models\Item)) {
        if(isset($getRecord) && is_callable($getRecord)) {
            $record = $getRecord();
        }
    } else
        throw new \Exception('No record provided to wow-tooltip component');

    // support for record type Item and record type Need
    $item = null;
    if($record instanceof \App\Models\Item) {
        $item = $record;
    } elseif(method_exists($record, 'item') && ($record->item instanceof \App\Models\Item)) {
        $item = $record->item;
    }
@endphp
<a data-wowhead="item={{ $item->wowhead_id }}" target="_blank" data-wh-icon-size="{{ $iconsize }}" href="https://www.wowhead.com/item={{ $item->wowhead_id }}{{ $domain }}">{{ $item->name }}</a>
