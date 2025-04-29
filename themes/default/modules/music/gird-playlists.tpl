<!-- BEGIN: main -->
<div class="ms-gird-albums row">
    <!-- BEGIN: loop -->
    <div class="{CLASS_ITEM} ms-gird-albums-item">
        <article>
            <div class="ms-gird-albums-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-gird-albums-fw" href="{ROW.playlist_link}" title="{ROW.playlist_name}">
                    <span class="ms-gird-albums-mask">
                        <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-gird-albums-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-gird-albums-cover"/>
            </div>
            <div class="ms-gird-albums-description">
                <h3 class="ms-ellipsis ms-second-title">
                    <a href="{ROW.playlist_link}" title="{ROW.playlist_name}">{ROW.playlist_name}</a>
                </h3>
                <div class="ms-gird-albums-singers">
                    <div class="h4 ms-third-title">{ROW.num_songs} {LANG.song1}</div>
                </div>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->
