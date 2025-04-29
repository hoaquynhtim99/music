<!-- BEGIN: main -->
<div class="mb-4">
    <div class="block-auto-play clearfix">
        <div class="block-auto-play-title">{LANG.play_sugges_song}</div>
        <div class="block-auto-play-btn">
            {LANG.autoplay}
            <div class="switch-button switch-button-sm ml-1">
                <input type="checkbox" name="{MODULE_DATA}_autoplay_suggessong" id="{MODULE_DATA}_autoplay_suggessong"><span><label for="{MODULE_DATA}_autoplay_suggessong"></label></span>
            </div>
        </div>
    </div>
    <div class="ms-main-list-song ms-main-list-song-no-tools" id="{MODULE_DATA}_suggessongs">
        <!-- BEGIN: loop -->
        <div class="ms-main-list-song-item">
            <article class="clearfix">
                <div class="ms-main-list-song-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                    <a class="ms-main-list-song-fw" data-toggle="linkitem" href="{ROW.song_link}" title="{ROW.song_name}">
                        <span class="ms-main-list-song-mask">
                            <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif"/>
                        </span>
                        <span class="ms-main-list-song-iconplay"><i class="fa fa-play-circle-o"></i></span>
                    </a>
                    <img src="{ROW.resource_avatar_thumb}" class="ms-main-list-song-cover"/>
                </div>
                <div class="ms-main-list-song-title">
                    <h3 class="ms-ellipsis">
                        <a href="{ROW.song_link}" class="ms-so" title="{ROW.song_name}">{ROW.song_name}</a>
                    </h3>
                    <h4 class="ms-ellipsis ms-third-title">
                        <!-- BEGIN: show_singer -->
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                        <!-- END: show_singer -->

                        <!-- BEGIN: va_singer -->
                        <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-mainlist-songs-singers-{ROW.song_code}" class="ms-sg">{VA_SINGERS}</a>
                        <span class="hidden" id="{UNIQUEID}-mainlist-songs-singers-{ROW.song_code}" title="{LANG.singer_list}">
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
    <script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js"></script>
    <script>
    $(function() {
        var isAuto = $.cookie('{MODULE_DATA}_autoplay_suggessong');
        if (isAuto != null && isAuto == 1) {
            $('#{MODULE_DATA}_autoplay_suggessong').attr("checked", "checked");
        } else {
            $('#{MODULE_DATA}_autoplay_suggessong').removeAttr("checked");
        }
        $('#{MODULE_DATA}_autoplay_suggessong').on("change", function() {

            if ($(this).is(":checked")) {
                $.cookie('{MODULE_DATA}_autoplay_suggessong', 1, {expires: 30});
            } else {
                $.cookie('{MODULE_DATA}_autoplay_suggessong', 0, {expires: 30});
            }
        });
    });
    </script>
</div>
<!-- END: main -->
