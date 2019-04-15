<!-- BEGIN: main -->
<div class="ms-detailso-header clearfix">
    <div class="ms-detailso-header-thumb" style="background-image:url({SONG.resource_avatar_thumb});">
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
    </div>
    <h1 class="ms-detailso-header-name">
        {SONG.song_name} -
        <span class="ms-detailso-header-singer">
        <!-- BEGIN: show_singer -->
        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}">{SINGER.artist_name}</a><!-- END: loop -->
        <!-- END: show_singer -->

        <!-- BEGIN: va_singer -->
        <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-detail-song-singers-{SONG.song_code}">{VA_SINGERS}</a>
        <span class="hidden" id="{UNIQUEID}-detail-song-singers-{SONG.song_code}" title="{LANG.singer_list}">
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
    <ul class="ms-detailso-header-info">
        <li>
            <h2>
                <span class="tit">{LANG.author}:</span>
                <!-- BEGIN: show_author -->
                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->{AUTHOR.artist_name}<!-- END: loop -->
                <!-- END: show_author -->

                <!-- BEGIN: va_author -->
                <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-detail-song-authors-{SONG.song_code}">{VA_AUTHORS}</a>
                <span class="hidden" id="{UNIQUEID}-detail-song-authors-{SONG.song_code}" title="{LANG.author_list}">
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
    <div class="ms-detailso-header-tool">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="#" data-toggle="show-va-singer" data-target="#ember-code-area"><i class="fa fa-code" aria-hidden="true"></i> {LANG.get_ember_code}</a>
                    <div class="hidden" id="ember-code-area" title="{LANG.ember_code}">
                        <textarea class="form-control ms-detailso-ember-code" rows="5" data-toggle="select-all">&lt;iframe src=&quot;{SONG.song_link_ember}&quot; width=&quot;100%&quot; height=&quot;219&quot; border=&quot;0&quot; style=&quot;border:0px&quot; &gt;&lt;/iframe&gt;</textarea>
                    </div>
                </li>
                <li><a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {LANG.report}</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- BEGIN: player -->
<div class="ms-audio-player">
    <div id="songplayer"></div>
</div>
<script type="text/javascript" src="{PLAYER_DIR}jwplayer.js"></script>
<script type="text/javascript">
var songplayer = jwplayer('songplayer').setup({
    width: '100%',
    height: '219',
    stretching: 'fill',
    image: '{SONG.resource_cover}',
    sources: [<!-- BEGIN: filesdata --><!-- BEGIN: comma -->, <!-- END: comma -->{
        "file": "{FILESDATA.resource_path}",
        "label": "{FILESDATA.quality_name}"
    }<!-- END: filesdata -->],
    <!-- BEGIN: tracks -->tracks: [<!-- BEGIN: loop -->{
        "file": "{TRACK.caption_file}",
        "kind": "captions",
        "label": "{TRACK.caption_name}",
        "default": {TRACK.is_default}
    }<!-- BEGIN: comma -->, <!-- END: comma --><!-- END: loop -->],<!-- END: tracks -->
    title: "{SONG_FULL_NAME}",
    description: "{SONG_FULL_SINGER}",
    autostart: true,
    repeat: false,
    skin: {name: "nvmsso"}
});
msJwplayerStyleCaption(songplayer);
songplayer.on("complete", function(event) {
    var btnAutoPlay = $("#{MODULE_DATA}_autoplay_suggessong");
    var isAutoPlay = (btnAutoPlay.length && btnAutoPlay.is(":checked"));
    if (isAutoPlay) {
        // Phát bài hát tiếp theo
        window.location = $("#{MODULE_DATA}_suggessongs").find('[data-toggle="linkitem"]:first').attr("href");
    } else {
        // Lặp lại bài hát này
        songplayer.play();
    }
});
</script>
<!-- END: player -->
<div class="ms-detailso-action">
    <a href="#" class="btn btn-default btn-xs"><i class="fa fa-bookmark" aria-hidden="true"></i> {LANG.add_to}</a>
    <!-- BEGIN: comment_btn -->
    <a href="#" class="btn btn-default btn-xs" data-toggle="scrolltodiv" data-target="#comment-area"><i class="fa fa-comment" aria-hidden="true"></i> {LANG.comment}<!-- BEGIN: stat --> ({COMMENT_NUMS})<!-- END: stat --></a>
    <!-- END: comment_btn -->
    <a href="#" class="btn btn-default btn-xs" title="{LANG.download_this_song}" data-toggle="mscallpop" data-mode="downloadsong" data-code="{SONG.song_code}" data-tokend="{SONG.tokend}"><i class="fa fa-download" aria-hidden="true"></i> {LANG.download}</a>
    <!-- BEGIN: video -->
    <a href="{SONG.video.video_link}" class="btn btn-primary btn-xs"><i class="fa fa-file-video-o" aria-hidden="true"></i> {LANG.view_video}</a>
    <!-- END: video -->
</div>
<div class="well ms-detailso-lrt">
    <h3 class="ms-detailso-lrt-title">{LANG.lyric}: {SONG.song_name}</h3>
    <div class="ms-detailso-lrt-body" id="detail-song-lrt">
        {SONG.song_introtext}
    </div>
    <div class="ms-detailso-lrt-control">
        <a href="#" class="ms-detailso-lrt-control-f" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="F" data-unique="detail-song-lrt">{LANG.view_full}</a>
        <a href="#" class="ms-detailso-lrt-control-h" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="H" data-unique="detail-song-lrt">{LANG.view_haft}</a>
    </div>
</div>

<!-- BEGIN: albums -->
<div class="ms-title-section">
    <h2><a href="{SONG.album_link}">{LANG.album} {SONG.singer_name}</a></h2>
</div>
{ALBUM_HTML}
<!-- END: albums -->

<!-- BEGIN: videos -->
<div class="ms-title-section">
    <h2><a href="{SONG.video_link}">{LANG.video} {SONG.singer_name}</a></h2>
</div>
{VIDEO_HTML}
<!-- END: videos -->

<!-- BEGIN: comment -->
<div class="ms-title-section" id="comment-area">
    <h2><span>{LANG.comment}</span></h2>
</div>
{COMMENT_HTML}
<!-- END: comment -->

<!-- END: main -->
