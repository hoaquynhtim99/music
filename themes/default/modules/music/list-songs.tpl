<!-- BEGIN: main -->
<ul class="ms-list-song">
    <!-- BEGIN: loop -->
    <li>
        <div class="ms-list-song-action">
            <ul>
                <li><a rel="nofollow" href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                <li><a rel="nofollow" href="{ROW.song_link_full}" title="{LANG.share_song_fb}" data-toggle="share-song-fb" data-code="{ROW.song_code}" data-tokend="{ROW.tokend}"><i class="fa fa-spin fa-spinner" aria-hidden="true"></i></a></li>
                <li><a rel="nofollow" href="#" title="{LANG.download_this_song}" data-toggle="mscallpop" data-mode="downloadsong" data-code="{ROW.song_code}" data-tokend="{ROW.tokend}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                <li><a href="{ROW.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
            </ul>
        </div>
        <div class="ms-list-song-title">
            <h3 class="ms-ellipsis">
                <a href="{ROW.song_link}" class="ms-so" title="{ROW.song_name}">{ROW.song_name}</a><span class="ms-br"> - </span>
                <!-- BEGIN: show_singer -->
                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" class="ms-sg">{SINGER.artist_name}</a><!-- END: loop -->
                <!-- END: show_singer -->

                <!-- BEGIN: va_singer -->
                <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-list-songs-singers-{ROW.song_code}" class="ms-sg">{VA_SINGERS}</a>
                <span class="hidden" id="{UNIQUEID}-list-songs-singers-{ROW.song_code}" title="{LANG.singer_list}">
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
            </h3>
        </div>
    </li>
    <!-- END: loop -->
</ul>
<!-- END: main -->
