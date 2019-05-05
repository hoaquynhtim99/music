<!-- BEGIN: main -->
<div class="ms-d-flex ms-mymusic-head ms-justify-content-center mb-4 mt-1">
    <div class="ms-mymusic-head-ct clearfix">
        <div class="ms-d-flex ms-align-items-center">
            <div class="mr-2">
                <img class="img-circle" alt="{USER_FULL_NAME}" width="80" height="80" src="{USER_PHOTO}">
            </div>
            <div>
                <h1 class="ms-mymusic-myname">{USER_FULL_NAME}</h1>
                <p class="ms-mymusic-mysig">@{USER_USERNAME}</p>
            </div>
        </div>
    </div>
</div>
<div class="ms-d-flex ms-mymusic-tab ms-justify-content-center mb-4">
    <div class="ms-mymusic-tab-ct">
        <ul class="nav nav-tabs">
            <li<!-- BEGIN: active_overview --> class="active"<!-- END: active_overview -->><a href="{TAB_LINK}">{LANG.view_singer_tab_default}</a></li>
            <li<!-- BEGIN: active_song --> class="active"<!-- END: active_song -->><a href="{TAB_LINK}/song">{LANG.mymusic_song}</a></li>
            <li<!-- BEGIN: active_album --> class="active"<!-- END: active_album -->><a href="{TAB_LINK}/album">{LANG.mymusic_album}</a></li>
            <li<!-- BEGIN: active_video --> class="active"<!-- END: active_video -->><a href="{TAB_LINK}/mv">{LANG.mymusic_video}</a></li>
            <li<!-- BEGIN: active_playlist --> class="active"<!-- END: active_playlist -->><a href="{TAB_LINK}/playlist">{LANG.mymusic_playlist}</a></li>
        </ul>
    </div>
</div>

<!-- BEGIN: tab_overview -->
<div class="row">
    <div class="col-xs-24 col-sm-24 col-md-16">
        <div class="ms-title-section">
            <h2>
                <a href="{TAB_LINK}/song">{LANG.mymusic_song}</a>
            </h2>
        </div>
        <div class="mb-2">
            <!-- BEGIN: song_data -->
            {SONG_HTML}
            <!-- END: song_data -->
            <!-- BEGIN: song_empty -->
            <div class="alert alert-info">
                {LANG.mymusic_song_empty}
            </div>
            <!-- END: song_empty -->
        </div>
        <div class="ms-title-section">
            <h2>
                <a href="{TAB_LINK}/mv">{LANG.mymusic_video}</a>
            </h2>
        </div>
        <div class="mb-2">
            <!-- BEGIN: video_data -->
            {VIDEOS_HTML}
            <!-- END: video_data -->
            <!-- BEGIN: video_empty -->
            <div class="alert alert-info">
                {LANG.mymusic_video_empty}
            </div>
            <!-- END: video_empty -->
        </div>
    </div>
    <div class="col-xs-24 col-sm-24 col-md-8">
        <div class="ms-title-section">
            <h2>
                <a href="{TAB_LINK}/album">{LANG.mymusic_album}</a>
            </h2>
        </div>
        <div class="mb-2">
            <!-- BEGIN: album_data -->
            {ALBUM_HTML}
            <!-- END: album_data -->
            <!-- BEGIN: album_empty -->
            <div class="alert alert-info">
                {LANG.mymusic_album_empty}
            </div>
            <!-- END: album_empty -->
        </div>
        <div class="ms-title-section">
            <h2>
                <a href="{TAB_LINK}/playlist">{LANG.mymusic_playlist}</a>
            </h2>
        </div>
        <div class="mb-2">
            <!-- BEGIN: playlist_data -->
            {PLAYLIST_HTML}
            <!-- END: playlist_data -->
            <!-- BEGIN: playlist_empty -->
            <div class="alert alert-info">
                {LANG.mymusic_playlist_empty}
            </div>
            <!-- END: playlist_empty -->
        </div>
    </div>
</div>
<!-- END: tab_overview -->

<!-- BEGIN: tab_song -->
<!-- BEGIN: data -->
{SONG_HTML}
<!-- END: data -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
    {LANG.mymusic_song_empty}
</div>
<!-- END: empty -->
<!-- END: tab_song -->

<!-- BEGIN: tab_album -->
<!-- BEGIN: data -->
{ALBUM_HTML}
<!-- END: data -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
    {LANG.mymusic_album_empty}
</div>
<!-- END: empty -->
<!-- END: tab_album -->

<!-- BEGIN: tab_video -->
<!-- BEGIN: data -->
{VIDEOS_HTML}
<!-- END: data -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
    {LANG.mymusic_video_empty}
</div>
<!-- END: empty -->
<!-- END: tab_video -->

<!-- BEGIN: tab_playlist -->
<!-- BEGIN: data -->
{PLAYLIST_HTML}
<!-- END: data -->
<!-- BEGIN: empty -->
<div class="alert alert-info">
    {LANG.mymusic_playlist_empty}
</div>
<!-- END: empty -->
<!-- END: tab_playlist -->

<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<!-- END: main -->
