<!-- BEGIN: main -->
<div class="ms-gird-albums row">
    <!-- BEGIN: loop -->
    <div class="{CLASS_ITEM} ms-gird-albums-item">
        <article>
            <div class="ms-gird-albums-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-gird-albums-fw" href="{ROW.album_link}" title="{ROW.album_name}">
                    <span class="ms-gird-albums-mask">
                        <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-gird-albums-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-gird-albums-cover"/>
            </div>
            <div class="ms-gird-albums-description">
                <h3 class="ms-ellipsis ms-second-title">
                    <a href="{ROW.album_link}" title="{ROW.album_name}">{ROW.album_name}</a>
                </h3>
                <div class="ms-gird-albums-singers">
                    <!-- BEGIN: show_singer -->
                    <h4 class="ms-third-title ms-ellipsis">
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}">{SINGER.artist_name}</a><!-- END: loop -->
                    </h4>
                    <!-- END: show_singer -->

                    <!-- BEGIN: va_singer -->
                    <div class="h4 ms-third-title"><a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-gird-albums-singers-{ROW.album_code}">{VA_SINGERS}</a></div>
                    <div class="hidden" id="{UNIQUEID}-gird-albums-singers-{ROW.album_code}" title="{LANG.singer_list}">
                        <div class="list-group ms-singer-listgr-modal">
                            <!-- BEGIN: loop -->
                            <a href="{SINGER.singer_link}" class="list-group-item">{SINGER.artist_name}</a>
                            <!-- END: loop -->
                        </div>
                    </div>
                    <!-- END: va_singer -->

                    <!-- BEGIN: no_singer -->
                    <div class="h4 ms-third-title">{UNKNOW_SINGER}</div>
                    <!-- END: no_singer -->
                </div>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->
