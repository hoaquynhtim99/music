<!-- BEGIN: main -->
<div class="ms-detailab-header clearfix">
    <div class="ms-detailab-header-thumb" style="background-image:url({PLAYLIST.resource_avatar_thumb});">
        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
    </div>
    <h1 class="ms-detailab-header-name">
        {PLAYLIST.playlist_name}
    </h1>
    <ul class="ms-detailso-header-info">
        <li>
            <h2>
                <span class="tit">{LANG.create_by}:</span>
                {PLAYLIST.creat_by}
            </h2>
        </li>
    </ul>
    <div class="ms-detailab-desc">
        {PLAYLIST.playlist_introtext}
    </div>
</div>
<div class="ms-detailso-action">
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
                        <textarea class="form-control ms-detailso-ember-code" rows="5" data-toggle="select-all">&lt;iframe src=&quot;{PLAYLIST.playlist_link_ember}&quot; width=&quot;100%&quot; height=&quot;500&quot; border=&quot;0&quot; style=&quot;border:0px&quot; &gt;&lt;/iframe&gt;</textarea>
                    </div>
                </li>
                <li><a href="#"><i class="fa fa-pencil" aria-hidden="true"></i> {GLANG.edit}</a></li>
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

<!-- BEGIN: comment -->
<div class="ms-title-section" id="comment-area">
    <h2><span>{LANG.comment}</span></h2>
</div>
{COMMENT_HTML}
<!-- END: comment -->

<!-- END: main -->
