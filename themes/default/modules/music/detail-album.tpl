<!-- BEGIN: main -->
<div class="ms-detailab-header clearfix">
    <div class="ms-detailab-header-thumb" style="background-image:url({ALBUM.resource_avatar_thumb});">
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
    </div>
    <h1 class="ms-detailab-header-name">
        {ALBUM.album_name} -
        <span class="ms-detailab-header-singer">
        <!-- BEGIN: show_singer -->
        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}">{SINGER.artist_name}</a><!-- END: loop -->
        <!-- END: show_singer -->

        <!-- BEGIN: va_singer -->
        <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-detail-album-singers-{ALBUM.album_code}">{VA_SINGERS}</a>
        <span class="hidden" id="{UNIQUEID}-detail-album-singers-{ALBUM.album_code}" title="{LANG.singer_list}">
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
                <span class="tit">{LANG.categories}:</span>
                <!-- BEGIN: show_cat -->
                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate --><a href="{CAT.cat_link}">{CAT.cat_name}</a><!-- END: loop -->
                <!-- END: show_cat -->

                <!-- BEGIN: no_cat -->{UNKNOW_CAT}<!-- END: no_cat -->
            </h2>
        </li>
    </ul>
    <div class="ms-detailab-desc">
        {ALBUM.album_description}
    </div>
</div>
<div class="ms-detailso-action">
    <a href="#" class="btn btn-default btn-xs"><i class="fa fa-bookmark" aria-hidden="true"></i> {LANG.add_to}</a>
    <a href="#" class="btn btn-default btn-xs"><i class="fa fa-comment" aria-hidden="true"></i> {LANG.comment}</a>
    <div class="pull-right">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="#" data-toggle="show-va-singer" data-target="#ember-code-area"><i class="fa fa-code" aria-hidden="true"></i> {LANG.get_ember_code}</a>
                    <div class="hidden" id="ember-code-area" title="{LANG.ember_code}">
                        <textarea class="form-control ms-detailso-ember-code" rows="5" data-toggle="select-all">&lt;iframe src=&quot;{ALBUM.album_link_ember}&quot; width=&quot;100%&quot; height=&quot;219&quot; border=&quot;0&quot; style=&quot;border:0px&quot; &gt;&lt;/iframe&gt;</textarea>
                    </div>
                </li>
                <li><a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {LANG.report}</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="ms-playlist-player">
    <div id="playlistplayer"></div>
</div>

<div class="ms-detailab-playlist">
    <div class="ms-detailab-solists" id="soplaylists">
        <div class="ctn">
            <ul>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <span class="_stt">1</span>
                    <h4 class="ms-ellipsis">Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm Duyên Đầu Năm </h4>
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="well ms-detailso-lrt">
    <h3 class="ms-detailso-lrt-title">Lời bài hát: {SONG.song_name}</h3>
    <div class="ms-detailso-lrt-body" id="detail-song-lrt">
        {SONG.song_introtext}
    </div>
    <div class="ms-detailso-lrt-control">
        <a href="#" class="ms-detailso-lrt-control-f" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="F" data-unique="detail-song-lrt">{LANG.view_full}</a>
        <a href="#" class="ms-detailso-lrt-control-h" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="H" data-unique="detail-song-lrt">{LANG.view_haft}</a>
    </div>
</div>

<link type="text/css" href="{PLUGINS_DIR}jscrollpane/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
<script type="text/javascript" src="{PLAYER_DIR}jwplayer.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/mwheelIntent.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/jquery.jscrollpane.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#soplaylists').jScrollPane({
        animateScroll: true
    });
    var playlistplayer = jwplayer('playlistplayer').setup({
        width: '100%',
        height: '219',
        stretching: 'fill',
        image: '{ALBUM.resource_avatar}',
        playlist: [{
            sources: [
                {"file": "/uploads/ca-nhac/1.mp3", "label": "128K"},
                {"file": "/uploads/ca-nhac/1.mp3", "label": "320K"},
                {"file": "/uploads/ca-nhac/1.mp3", "label": "Losless"}
            ],
            image: '{ALBUM.resource_avatar}',
            title: 'Âm Thầm Yêu Anh'
        }, {
            sources: [
                {"file": "/uploads/ca-nhac/2.mp3", "label": "128K"},
                {"file": "/uploads/ca-nhac/2.mp3", "label": "320K"},
                {"file": "/uploads/ca-nhac/2.mp3", "label": "Losless"}
            ],
            image: '{ALBUM.resource_avatar}',
            title: 'Âm Thầm Yêu Anh'
        }, {
            sources: [
                {"file": "/uploads/ca-nhac/3.mp3", "label": "128K"},
                {"file": "/uploads/ca-nhac/3.mp3", "label": "320K"},
                {"file": "/uploads/ca-nhac/3.mp3", "label": "Losless"}
            ],
            image: '{ALBUM.resource_avatar}',
            title: 'Âm Thầm Yêu Anh'
        }, {
            sources: [
                {"file": "/uploads/ca-nhac/4.mp3", "label": "128K"},
                {"file": "/uploads/ca-nhac/4.mp3", "label": "320K"},
                {"file": "/uploads/ca-nhac/5.mp3", "label": "Losless"}
            ],
            image: '{ALBUM.resource_avatar}',
            title: 'Âm Thầm Yêu Anh'
        }],
        autostart: true,
        repeat: true,
        skin: {name: "nvmsso"},
        localization: {
            fullscreen: '{LANG.player_lang_fullscreen}',
            settings: '{LANG.player_lang_settings}',
            hd: '{LANG.player_lang_hd}'
        }
    });
});
</script>

<!-- BEGIN: singer_albums -->
<div class="ms-title-section">
    <h2><a href="{ALBUM.singer_albums_link}">{LANG.album} {ALBUM.singer_name}</a></h2>
</div>
{SINGER_ALBUMS_HTML}
<!-- END: singer_albums -->

<!-- BEGIN: cat_albums -->
<div class="ms-title-section">
    <h2><a href="{ALBUM.cat_albums_link}">{LANG.album} {ALBUM.cat_name}</a></h2>
</div>
{CAT_ALBUMS_HTML}
<!-- END: cat_albums -->



<!-- END: main -->