<!-- BEGIN: main -->
<div class="block-auto-play clearfix">
    <div class="block-auto-play-title">{LANG.play_sugges_album}</div>
</div>
<div class="ms-main-list-song ms-main-list-song-no-tools">
    <!-- BEGIN: loop -->
    <div class="ms-main-list-song-item">
        <article class="clearfix">
            <div class="ms-main-list-song-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-main-list-song-fw" href="{ROW.album_link}" title="{ROW.album_name}">
                    <span class="ms-main-list-song-mask">
                        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-main-list-song-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-main-list-song-cover"/>
            </div>
            <div class="ms-main-list-song-title">
                <h3 class="ms-ellipsis">
                    <a href="{ROW.album_link}" class="ms-so" title="{ROW.album_name}">{ROW.album_name}</a>
                </h3>
                <h4 class="ms-ellipsis ms-third-title">
                    <!-- BEGIN: show_singer -->
                    <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                    <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                    <!-- END: show_singer -->

                    <!-- BEGIN: va_singer -->
                    <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-mainlist-songs-singers-{ROW.album_code}" class="ms-sg">{VA_SINGERS}</a>
                    <span class="hidden" id="{UNIQUEID}-mainlist-songs-singers-{ROW.album_code}" title="{LANG.singer_list}">
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
<!-- END: main -->
