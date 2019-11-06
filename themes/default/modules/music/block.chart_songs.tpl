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
            <!-- BEGIN: chart_data -->
            <ul class="chart-items">
                <!-- BEGIN: loop -->
                <li class="clearfix">
                    <!-- BEGIN: image -->
                    <a href="{ROW.song_link}" class="item-image" style="background-image:url({ROW.resource_avatar_thumb});">
                        <span><span>01</span></span>
                    </a>
                    <!-- END: image -->
                    <div class="item-rank">
                        <span class="chart-no">{ROW.chart_order}</span>
                        <!-- BEGIN: order_desc --><span class="chart-order chart-order-desc"></span><!-- END: order_desc -->
                        <!-- BEGIN: order_asc --><span class="chart-order chart-order-asc"></span><!-- END: order_asc -->
                        <!-- BEGIN: order_no --><span class="chart-order chart-order-no"></span><!-- END: order_no -->
                        <!-- BEGIN: order_num --><span class="chart-order-num">{ORDER_NUM}</span><!-- END: order_num -->
                    </div>
                    <div class="item-info">
                        <h3 class="ms-ellipsis">
                            <a href="{ROW.song_link}" class="ms-so" title="{ROW.song_name}">{ROW.song_name}</a>
                        </h3>
                        <div class="artist ms-ellipsis">
                            <!-- BEGIN: show_singer -->
                            <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                            <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                            <!-- END: show_singer -->

                            <!-- BEGIN: va_singer -->
                            <a href="#" data-toggle="show-va-singer-b{CONFIG.bid}" data-target="#{UNIQUEID}-blockchart-songs-singers-{ROW.song_code}" class="ms-sg">{VA_SINGERS}</a>
                            <span class="hidden" id="{UNIQUEID}-blockchart-songs-singers-{ROW.song_code}" title="{LANG.singer_list}">
                                <span class="list-group ms-singer-listgr-modal">
                                    <!-- BEGIN: loop -->
                                    <a href="{SINGER.singer_link}" class="list-group-item">{SINGER.artist_name}</a>
                                    <!-- END: loop -->
                                </span>
                            </span>
                            <!-- END: va_singer -->

                            <!-- BEGIN: no_singer -->
                            <span class="ms-sg">{UNKNOW_SINGER}</span>
                            <!-- END: no_singer -->
                        </div>
                    </div>
                </li>
                <!-- END: loop -->
            </ul>
            <!-- END: chart_data -->
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

    // Hiển thị danh sách ca sĩ của bài hát có quá nhiều ca sĩ
    $('[data-toggle="show-va-singer-b{CONFIG.bid}"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).data('target'));
    });
});
</script>
<!-- END: main -->
