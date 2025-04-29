<!-- BEGIN: css -->
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}themes/{TEMPLATE_CSS}/css/{MODULE_THEME}.css">
<!-- END: css -->

<!-- BEGIN: main -->
<div class="ms-bchart">
    <div class="btitle">
        {LANG.chart_stitle_album}
    </div>
    <ul class="bcat">
        <!-- BEGIN: cat_title -->
        <li>
            <a href="#" data-toggle="msLoadChartTab{CONFIG.bid}" data-code="{CAT.cat_code}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{CAT.cat_name}</a>
        </li>
        <!-- END: cat_title -->
    </ul>
    <div class="ccontent">
        <div class="cloader">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
        </div>
        <div class="ccontent-inner" id="ms-bchart{CONFIG.bid}">
            <!-- BEGIN: chart_content -->
            <!-- BEGIN: chart_empty -->
            <div class="alert alert-info mb-0">{LANG.chart_is_updating}</div>
            <!-- END: chart_empty -->
            <!-- BEGIN: chart_data -->
            <ul class="chart-items">
                <!-- BEGIN: loop -->
                <li class="clearfix">
                    <!-- BEGIN: image -->
                    <a href="{ROW.album_link}" class="item-image" style="background-image:url({ROW.resource_avatar_thumb});">
                        <span><span>01</span></span>
                    </a>
                    <!-- END: image -->
                    <div class="item-rank">
                        <span class="chart-no">{ROW.chart_order_show}</span>
                        <!-- BEGIN: order_desc --><span class="chart-order chart-order-desc"></span><!-- END: order_desc -->
                        <!-- BEGIN: order_asc --><span class="chart-order chart-order-asc"></span><!-- END: order_asc -->
                        <!-- BEGIN: order_no --><span class="chart-order chart-order-no"></span><!-- END: order_no -->
                        <!-- BEGIN: order_num --><span class="chart-order-num">{ORDER_NUM}</span><!-- END: order_num -->
                    </div>
                    <div class="item-info">
                        <h3 class="ms-ellipsis">
                            <a href="{ROW.album_link}" class="ms-so" title="{ROW.album_name}">{ROW.album_name}</a>
                        </h3>
                        <div class="artist ms-ellipsis">
                            <!-- BEGIN: show_singer -->
                            <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                            <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                            <!-- END: show_singer -->

                            <!-- BEGIN: va_singer -->
                            <a href="#" data-toggle="show-va-singer-b{CONFIG.bid}" data-target="#{UNIQUEID}-blockchart-albums-singers-{ROW.album_code}" class="ms-sg">{VA_SINGERS}</a>
                            <span class="hidden" id="{UNIQUEID}-blockchart-albums-singers-{ROW.album_code}" title="{LANG.singer_list}">
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
            <!-- END: chart_content -->
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
            return false;
        }
        wrapper.addClass('loading');
        // AJAX để load tại đây
        $.ajax({
            type: 'POST',
            url: '{AJAX_URL}',
            data: {
                'getBlockChartAlbumTab': 1,
                'cat_code': $this.data('code'),
            }
        }).done(function(res) {
            container.html(res);
            $('[data-toggle="msLoadChartTab{CONFIG.bid}"]').removeClass('active');
            $this.addClass('active');
            wrapper.removeClass('loading');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
            wrapper.removeClass('loading');
        });
    });

    // Hiển thị danh sách ca sĩ của bài hát có quá nhiều ca sĩ
    $(document).delegate('[data-toggle="show-va-singer-b{CONFIG.bid}"]', 'click', function(e) {
        e.preventDefault();
        modalShowByObj($(this).data('target'));
    });
});
</script>
<!-- END: main -->
