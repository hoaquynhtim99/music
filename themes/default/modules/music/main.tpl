<!-- BEGIN: albums -->
<div class="ms-title-section">
    <h2>
        <a href="{ALBUMS_LINK}">{LANG.album}</a>
    </h2>
</div>
{ALBUMS_HTML}
<!-- END: albums -->

<!-- BEGIN: singers -->
<div class="ms-title-section">
    <h2>
        <a href="{SINGERS_LINK}">{LANG.singer}</a>
    </h2>
</div>
<div class="row ms-main-singer">
    <!-- BEGIN: loop -->
    <div class="<!-- BEGIN: x1 -->col-xs-12 col-sm-8 col-md-4<!-- END: x1 --><!-- BEGIN: x2 -->col-sm-8 col-md-8 visible-sm visible-md visible-lg<!-- END: x2 --> ms-main-singer-item">
        <article>
            <div class="ms-main-singer-thumb" style="background-image:url({SINGER.resource_avatar_thumb});">
                <a class="ms-main-singer-fw" href="{SINGER.singer_link}">
                    <span class="ms-main-singer-mask">
                        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                </a>
                <h4><a href="{SINGER.singer_link}">{SINGER.artist_name}</a></h4>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
<!-- END: singers -->

<!-- BEGIN: songs -->
<div class="ms-title-section">
    <h2>
        <span>{LANG.song}</span>
    </h2>
</div>
<div class="row ms-main-list-song">
    <!-- BEGIN: loop -->
    <div class="col-xs-24 col-sm-12 col-md-12 col-lg-12 ms-main-list-song-item">
        <article class="clearfix">
            <div class="ms-main-list-song-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-main-list-song-fw" href="{ROW.song_link}" title="{ROW.song_name}">
                    <span class="ms-main-list-song-mask">
                        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-main-list-song-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-main-list-song-cover"/>
            </div>
            <div class="ms-main-list-song-action">
                <ul>
                    <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                    <li><a href="{ROW.song_link_full}" title="{LANG.share_song_fb}" data-toggle="share-song-fb" data-code="{ROW.song_code}" data-tokend="{ROW.tokend}"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></a></li>
                    <li><a href="#" title="{LANG.download_this_song}" class="song-hidden-inline" data-toggle="mscallpop" data-code="{ROW.song_code}" data-tokend="{ROW.tokend}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                    <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                </ul>
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
<!-- END: songs -->

<!-- BEGIN: videos -->
<div class="ms-title-section">
    <h2>
        <a href="{VIDEOS_LINK}">{LANG.video}</a>
    </h2>
</div>
{VIDEOS_HTML}
<!-- END: videos -->
