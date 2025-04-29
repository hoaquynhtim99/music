<!-- BEGIN: main -->
<div class="ms-gird-videos row">
    <!-- BEGIN: loop -->
    <div class="{CLASS_ITEM} ms-gird-videos-item">
        <article>
            <div class="ms-gird-videos-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
                <a class="ms-gird-videos-fw" href="{ROW.video_link}" title="{ROW.video_name}">
                    <span class="ms-gird-videos-mask">
                        <img src="{PIX_IMAGE}"/>
                    </span>
                    <span class="ms-gird-videos-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar_thumb}" class="ms-gird-videos-cover"/>
            </div>
            <div class="ms-gird-videos-description">
                <h3 class="ms-ellipsis ms-second-title">
                    <a href="{ROW.video_link}" title="{ROW.video_name}">{ROW.video_name}</a>
                </h3>
                <div class="ms-gird-videos-singers">
                    <!-- BEGIN: show_singer -->
                    <h4 class="ms-third-title ms-ellipsis">
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}">{SINGER.artist_name}</a><!-- END: loop -->
                    </h4>
                    <!-- END: show_singer -->

                    <!-- BEGIN: va_singer -->
                    <div class="h4 ms-third-title"><a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-gird-videos-singers-{ROW.video_code}">{VA_SINGERS}</a></div>
                    <div class="hidden" id="{UNIQUEID}-gird-videos-singers-{ROW.video_code}" title="{LANG.singer_list}">
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
