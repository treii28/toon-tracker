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
<style>
    .instancename {
        color: green; /* Set the color to green */
        font-weight: bold;
    }
</style>
