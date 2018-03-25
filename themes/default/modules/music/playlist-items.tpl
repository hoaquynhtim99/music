<!-- BEGIN: playlist -->
<div class="ms-detailab-playlist">
    <div class="ms-detailab-solists<!-- BEGIN: embed --> embed<!-- END: embed -->" id="soplaylists" data-mousein="false">
        <div class="ctn">
            <ul>
                <!-- BEGIN: loop -->
                <li class="plitem" data-plindex="{PLSO_INDEX}" data-sotitle="{PLSO_DATA.song_name_data}" data-socode="{PLSO_DATA.song_code}" data-tokend="{PLSO_LRTTOKEND}">
                    <span class="_stt">{PLSO_STT}</span>
                    <h4 class="ms-ellipsis">
                        <a href="{PLSO_DATA.song_link}" data-control="playsong" data-index="{PLSO_INDEX}" class="ms-so" title="{PLSO_DATA.song_name}">{PLSO_DATA.song_name}</a> -
                        <!-- BEGIN: show_singer -->
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{PLSO_SINGER.singer_link}" title="{PLSO_SINGER.artist_name}" class="ms-sg"{PLSO_LINK_TARGET}>{PLSO_SINGER.artist_name}</a><!-- END: loop -->
                        <!-- END: show_singer -->

                        <!-- BEGIN: va_singer -->
                        <a href="#" data-toggle="show-va-singer" data-target="#{PLUNIQUEID}-mainlist-songs-singers-{PLSO_DATA.song_code}" class="ms-sg">{PLSO_VA_SINGERS}</a>
                        <span class="hidden" id="{PLUNIQUEID}-mainlist-songs-singers-{PLSO_DATA.song_code}" title="{LANG.singer_list}">
                            <span class="list-group ms-singer-listgr-modal">
                                <!-- BEGIN: loop -->
                                <a href="{PLSO_SINGER.singer_link}" class="list-group-item"{PLSO_LINK_TARGET}>{PLSO_SINGER.artist_name}</a>
                                <!-- END: loop -->
                            </span>
                        </span>
                        <!-- END: va_singer -->

                        <!-- BEGIN: no_singer -->
                        <span class="ms-sg">{PLSO_UNKNOW_SINGER}</span>
                        <!-- END: no_singer -->
                    </h4>
                    <!-- BEGIN: actions -->
                    <div class="_actions">
                        <ul>
                            <li><a href="#" title="{LANG.add_song_tolikelist}"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.share_song_fb}"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                            <li><a href="#" title="{LANG.download_this_song}"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                            <li><a href="{PLSO_DATA.song_link}" title="{LANG.listen_this_song}"><i class="fa fa-play" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <!-- END: actions -->
                </li>
                <!-- END: loop -->
            </ul>
        </div>
    </div>
</div>
<!-- END: playlist -->
