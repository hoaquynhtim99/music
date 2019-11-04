<!-- BEGIN: main -->
<!-- BEGIN: css -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE_CSS}/css/{MODULE_THEME}.css">
<!-- END: css -->
<div class="ms-bchart">
    <div class="btitle">
        {LANG.chart_stitle_song}
    </div>
    <ul class="bcat">
        <!-- BEGIN: cat_title -->
        <li>
            <a href="#" data-toggle="msLoadChartTab{CONFIG.bid}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{CAT.cat_name}</a>
        </li>
        <!-- END: cat_title -->
    </ul>
    <div class="ccontent">
        <div class="cloader">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
        </div>
        <div class="ccontent-inner" id="ms-bchart{CONFIG.bid}">
            <!-- BEGIN: chart_empty -->
            <div class="alert alert-info mb-0">{LANG.chart_is_updating}</div>
            <!-- END: chart_empty -->
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('[data-toggle="msLoadChartTab{CONFIG.bid}"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var container = $('#ms-bchart{CONFIG.bid}');
        var wrapper = container.parent();
        if ($this.is('.active') || wrapper.is('.loading')) {
            console.log("Busy");
            return false;
        }
        wrapper.addClass('loading');
        // AJAX để load tại đây
        setTimeout(function() {
            $('[data-toggle="msLoadChartTab{CONFIG.bid}"]').removeClass('active');
            $this.addClass('active');
            wrapper.removeClass('loading');
        }, 2000);
    });
});
</script>
<!-- END: main -->
