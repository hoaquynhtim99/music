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
    <a href="#" class="btn btn-default btn-xs"><i class="fa fa-heart" aria-hidden="true"></i> {LANG.favorite}</a>
    <!-- BEGIN: comment_btn -->
    <a href="#" class="btn btn-default btn-xs" data-toggle="scrolltodiv" data-target="#comment-area"><i class="fa fa-comment" aria-hidden="true"></i> {LANG.comment}<!-- BEGIN: stat --> ({COMMENT_NUMS})<!-- END: stat --></a>
    <!-- END: comment_btn -->
    <div class="pull-right">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="#" data-toggle="show-va-singer" data-target="#ember-code-area"><i class="fa fa-code" aria-hidden="true"></i> {LANG.get_ember_code}</a>
                    <div class="hidden" id="ember-code-area" title="{LANG.ember_code}">
                        <textarea class="form-control ms-detailso-ember-code" rows="5" data-toggle="select-all">&lt;iframe src=&quot;{ALBUM.album_link_ember}&quot; width=&quot;100%&quot; height=&quot;500&quot; border=&quot;0&quot; style=&quot;border:0px&quot; &gt;&lt;/iframe&gt;</textarea>
                    </div>
                </li>
                <li><a href="#"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {LANG.report}</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- BEGIN: player -->
<div class="ms-playlist-player">
    <div id="playlistplayer"></div>
</div>

{FILE "playlist-items.tpl"}
{FILE "playlist-control.tpl"}
<!-- END: player -->

<div class="well ms-detailso-lrt">
    <h3 class="ms-detailso-lrt-title">{LANG.lyric}: <span id="solrtName">...</span></h3>
    <div class="ms-detailso-lrt-body" id="detail-song-lrt">
        ...
    </div>
    <div class="ms-detailso-lrt-control">
        <a href="#" class="ms-detailso-lrt-control-f" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="F" data-unique="detail-song-lrt">{LANG.view_full}</a>
        <a href="#" class="ms-detailso-lrt-control-h" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="H" data-unique="detail-song-lrt">{LANG.view_haft}</a>
    </div>
</div>

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

<!-- BEGIN: comment -->
<div class="ms-title-section" id="comment-area">
    <h2><span>{LANG.comment}</span></h2>
</div>
{COMMENT_HTML}
<!-- END: comment -->

<!-- END: main -->
