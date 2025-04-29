<div class="ms-main-list-song ms-main-list-song-no-tools">
    <!-- BEGIN: loop -->
    <div class="ms-main-list-song-item">
        <article class="clearfix">
            <div class="ms-main-list-song-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-main-list-song-fw" href="{ROW.playlist_link}" title="{ROW.playlist_name}">
                    <span class="ms-main-list-song-mask">
                        <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-main-list-song-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-main-list-song-cover"/>
            </div>
            <div class="ms-main-list-song-title">
                <h3 class="ms-ellipsis">
                    <a href="{ROW.playlist_link}" class="ms-so" title="{ROW.playlist_name}">{ROW.playlist_name}</a>
                </h3>
                <h4 class="ms-ellipsis ms-third-title">
                    <span class="ms-sg">{ROW.num_songs} {LANG.song1}</span>
                </h4>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
