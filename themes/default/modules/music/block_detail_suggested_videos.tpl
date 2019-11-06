<!-- BEGIN: main -->
<div class="mb-4">
    <div class="block-auto-play clearfix">
        <div class="block-auto-play-title">{LANG.play_sugges_video}</div>
        <div class="block-auto-play-btn">
            {LANG.autoplay}
            <div class="switch-button switch-button-sm ml-1">
                <input type="checkbox" name="{MODULE_DATA}_autoplay_suggesvideo" id="{MODULE_DATA}_autoplay_suggesvideo"><span><label for="{MODULE_DATA}_autoplay_suggesvideo"></label></span>
            </div>
        </div>
    </div>
    <div class="ms-list-video" id="{MODULE_DATA}_suggesvideos">
        <!-- BEGIN: loop -->
        <div class="ms-list-video-item">
            <article class="clearfix">
                <div class="ms-list-video-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                    <a class="ms-list-video-fw" data-toggle="linkitem" href="{ROW.video_link}" title="{ROW.video_name}">
                        <span class="ms-list-video-mask">
                            <img src="{PIX_IMAGE}"/>
                        </span>
                        <span class="ms-list-video-iconplay"><i class="fa fa-play-circle-o"></i></span>
                    </a>
                    <img src="{ROW.resource_avatar_thumb}" class="ms-list-video-cover"/>
                </div>
                <div class="ms-list-video-title">
                    <h3 class="ms-ellipsis-2line ms-second-title">
                        <a href="{ROW.video_link}" class="ms-so" title="{ROW.video_name}">{ROW.video_name}</a>
                    </h3>
                    <h4 class="ms-ellipsis ms-third-title">
                        <!-- BEGIN: show_singer -->
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                        <!-- END: show_singer -->

                        <!-- BEGIN: va_singer -->
                        <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-mainlist-videos-singers-{ROW.video_code}" class="ms-sg">{VA_SINGERS}</a>
                        <span class="hidden" id="{UNIQUEID}-mainlist-videos-singers-{ROW.video_code}" title="{LANG.singer_list}">
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
                    </h4>
                </div>
            </article>
        </div>
        <!-- END: loop -->
    </div>
    <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js"></script>
    <script>
    $(function() {
        var isAuto = $.cookie('{MODULE_DATA}_autoplay_suggesvideo');
        if (isAuto != null && isAuto == 1) {
            $('#{MODULE_DATA}_autoplay_suggesvideo').attr("checked", "checked");
        } else {
            $('#{MODULE_DATA}_autoplay_suggesvideo').removeAttr("checked");
        }
        $('#{MODULE_DATA}_autoplay_suggesvideo').on("change", function() {
            if ($(this).is(":checked")) {
                $.cookie('{MODULE_DATA}_autoplay_suggesvideo', 1, {expires: 30});
            } else {
                $.cookie('{MODULE_DATA}_autoplay_suggesvideo', 0, {expires: 30});
            }
        });
    });
    </script>
</div>
<!-- END: main -->
