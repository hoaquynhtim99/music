<!-- BEGIN: main -->
<!-- BEGIN: header -->
<div class="margin-bottom">
    {HEADER_HTML}
</div>
<!-- END: header -->

<!-- BEGIN: profile -->
<div class="ms-title-section">
    <h2>
        <span>{LANG.biography} {SINGER.artist_name}</span>
    </h2>
</div>
<div class="margin-top-sm">
    <!-- BEGIN: artist_realname --><p class="ms-inline-paragraph"><strong>{LANG.realname}:</strong> {SINGER.artist_realname}</p><!-- END: artist_realname -->
    <p class="ms-inline-paragraph"><strong>{LANG.artist_name}:</strong> {SINGER.artist_name}</p>
    <!-- BEGIN: singer_nickname --><p class="ms-inline-paragraph"><strong>{LANG.nickname}:</strong> {SINGER.singer_nickname}</p><!-- END: singer_nickname -->
    <!-- BEGIN: artist_hometown --><p class="ms-inline-paragraph"><strong>{LANG.hometown}:</strong> {SINGER.artist_hometown}</p><!-- END: artist_hometown -->
    <!-- BEGIN: artist_birthday --><p class="ms-inline-paragraph"><strong>{LANG.birthday}:</strong> {artist_birthday}</p><!-- END: artist_birthday -->
    <!-- BEGIN: nation --><p class="ms-inline-paragraph"><strong>{LANG.nation}:</strong> {NATION_NAME}</p><!-- END: nation -->
    
    <!-- BEGIN: singer_prize -->
    <p class="ms-inline-paragraph"><strong>{LANG.prize}:</strong></p>
    <div class="margin-bottom">
        {SINGER.singer_prize}
    </div>
    <!-- END: singer_prize -->
    
    <!-- BEGIN: singer_info -->
    <p class="ms-inline-paragraph"><strong>{LANG.more_info}:</strong></p>
    <div class="margin-bottom">
        {SINGER.singer_info}
    </div>
    <!-- END: singer_info -->
    
    <!-- BEGIN: empty -->
    <div class="alert alert-info">{EMPTY_MESSAGE}</div>
    <!-- END: empty -->
</div>
<!-- END: profile -->

<!-- BEGIN: songs -->
<div class="ms-title-section">
    <h2>
        <span>{LANG.song}</span>
    </h2>
</div>
{SONG_HTML}
<!-- END: songs -->

<!-- BEGIN: albums -->
<div class="ms-title-section">
    <h2>
        <span>{LANG.album}</span>
    </h2>
</div>
{ALBUM_HTML}
<!-- END: albums -->

<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<!-- END: main -->