<!-- BEGIN: main -->

<!-- BEGIN: player -->
<div class="ms-video-player<!-- BEGIN: embed --> embed<!-- END: embed -->">
    <div id="videoplayer"></div>
</div>
<script type="text/javascript" src="{PLAYER_DIR}jwplayer.js"></script>
<script type="text/javascript">
var videoplayer = jwplayer('videoplayer').setup({
    width: '100%',
    <!-- BEGIN: embedplayer -->height: '100%',<!-- END: embedplayer -->
    <!-- BEGIN: fullplayer -->aspectratio: '16:9',<!-- END: fullplayer -->
    androidhls: true,
    sources: [<!-- BEGIN: filesdata --><!-- BEGIN: comma -->, <!-- END: comma -->{
        "file": "{FILESDATA.resource_path}",
        "label": "{FILESDATA.quality_name}"
    }<!-- END: filesdata -->],
    autostart: true,
    primary: 'html5',
    repeat: false,
    skin: {name: "nvmsmv"},
    localization: {
        fullscreen: '{LANG.player_lang_fullscreen}',
        settings: '{LANG.player_lang_settings}',
        hd: '{LANG.player_lang_hd}'
    }
});
videoplayer.on("complete", function(event) {
    var btnAutoPlay = $("#{MODULE_DATA}_autoplay_suggesvideo");
    var isAutoPlay = (btnAutoPlay.length && btnAutoPlay.is(":checked"));
    if (isAutoPlay) {
        // Phát MV tiếp theo
        window.location = $("#{MODULE_DATA}_suggesvideos").find('[data-toggle="linkitem"]:first').attr("href");
    } else {
        // Lặp lại MV này
        videoplayer.play();
    }
});
</script>
<!-- END: player -->

<div class="ms-detailmv-header clearfix">
    <h1 class="ms-detailmv-header-name">
        {VIDEO.video_name} -
        <span class="ms-detailmv-header-singer">
        <!-- BEGIN: show_singer -->
        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}">{SINGER.artist_name}</a><!-- END: loop -->
        <!-- END: show_singer -->

        <!-- BEGIN: va_singer -->
        <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-detail-video-singers-{VIDEO.video_code}">{VA_SINGERS}</a>
        <span class="hidden" id="{UNIQUEID}-detail-video-singers-{VIDEO.video_code}" title="{LANG.singer_list}">
            <span class="list-group ms-singer-listgr-modal">
                <!-- BEGIN: loop -->
                <a href="{SINGER.singer_link}" class="list-group-item">{SINGER.artist_name}</a>
                <!-- END: loop -->
            </span>
        </span>
        <!-- END: va_singer -->

        <!-- BEGIN: no_singer -->{UNKNOW_SINGER}<!-- END: no_singer -->
        </span>
    </h1>
    <ul class="ms-detailmv-header-info">
        <li>
            <h2>
                <span class="tit">{LANG.author}:</span>
                <!-- BEGIN: show_author -->
                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->{AUTHOR.artist_name}<!-- END: loop -->
                <!-- END: show_author -->

                <!-- BEGIN: va_author -->
                <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-detail-video-authors-{VIDEO.video_code}">{VA_AUTHORS}</a>
                <span class="hidden" id="{UNIQUEID}-detail-video-authors-{VIDEO.video_code}" title="{LANG.author_list}">
                    <span class="list-group ms-author-listgr-modal">
                        <!-- BEGIN: loop -->
                        <a href="{AUTHOR.author_link}" class="list-group-item">{AUTHOR.artist_name}</a>
                        <!-- END: loop -->
                    </span>
                </span>
                <!-- END: va_author -->

                <!-- BEGIN: no_author -->{UNKNOW_AUTHOR}<!-- END: no_author -->
            </h2>
        </li>
        <li>
            <h2>
                <span class="tit">{LANG.categories}:</span>
                <!-- BEGIN: show_cat -->
                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->{CAT.cat_name}<!-- END: loop -->
                <!-- END: show_cat -->

                <!-- BEGIN: no_cat -->{UNKNOW_CAT}<!-- END: no_cat -->
            </h2>
        </li>
    </ul>
    <div class="ms-detailmv-header-tool">
        <!-- BEGIN: song -->
        <a href="{VIDEO.song.song_link}" class="btn btn-primary btn-xs"><i class="fa fa-music" aria-hidden="true"></i> {LANG.listen_song}</a>
        <!-- END: song -->
        <a href="#" class="btn btn-default btn-xs"><i class="fa fa-bookmark" aria-hidden="true"></i> {LANG.add_to}</a>
        <!-- BEGIN: comment_btn -->
        <a href="#" class="btn btn-default btn-xs" data-toggle="scrolltodiv" data-target="#comment-area"><i class="fa fa-comment" aria-hidden="true"></i> {LANG.comment}<!-- BEGIN: stat --> ({COMMENT_NUMS})<!-- END: stat --></a>
        <!-- END: comment_btn -->
        <a href="#" class="btn btn-default btn-xs"><i class="fa fa-download" aria-hidden="true"></i> {LANG.download}</a>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="#" data-toggle="show-va-singer" data-target="#ember-code-area"><i class="fa fa-code" aria-hidden="true"></i> {LANG.get_ember_code}</a>
                    <div class="hidden" id="ember-code-area" title="{LANG.ember_code}">
                        <textarea class="form-control ms-detailmv-ember-code" rows="5" data-toggle="select-all">&lt;iframe src=&quot;{VIDEO.video_link_ember}&quot; width=&quot;100%&quot; height=&quot;420&quot; border=&quot;0&quot; style=&quot;border:0px&quot; &gt;&lt;/iframe&gt;</textarea>
                    </div>
                </li>
                <li><a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {LANG.report}</a></li>
            </ul>
        </div>
        <div class="ms-detailso-action">
        </div>
    </div>
</div>

<!-- BEGIN: videos -->
<div class="ms-title-section">
    <h2><a href="{VIDEO.other_video_link}">{LANG.video} {VIDEO.singer_name}</a></h2>
</div>
{VIDEO_HTML}
<!-- END: videos -->

<!-- BEGIN: albums -->
<div class="ms-title-section">
    <h2><a href="{VIDEO.album_link}">{LANG.album} {VIDEO.singer_name}</a></h2>
</div>
{ALBUM_HTML}
<!-- END: albums -->

<!-- BEGIN: comment -->
<div class="ms-title-section" id="comment-area">
    <h2><span>{LANG.comment}</span></h2>
</div>
{COMMENT_HTML}
<!-- END: comment -->

<!-- END: main -->
