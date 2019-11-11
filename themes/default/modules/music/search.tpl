<!-- BEGIN: main -->
<div class="ms-full-search">
    <div class="result-mess alert alert-info">{LANG.search_result1} {SEARCH.totals} {LANG.search_result2} <strong class="s-key">{SEARCH.q}</strong></div>
    <!-- BEGIN: genre -->
    <div class="search-conditions">
        <!-- BEGIN: loop -->
        <a href="{GENRE.link}" class="label label-success">{GENRE.title} <i class="fa fa-times" aria-hidden="true"></i></a>
        <!-- END: loop -->
    </div>
    <!-- END: genre -->
    <div class="search-tabs">
        <ul class="nav nav-tabs">
            <!-- BEGIN: tab -->
            <li<!-- BEGIN: active --> class="active"<!-- END: active -->><a href="{TAB.link}">{TAB.title}</a></li>
            <!-- END: tab -->
        </ul>
        <div class="sfilter"><a role="button" data-toggle="collapse" href="#collapseMsSearchFilter" aria-expanded="true" aria-controls="collapseMsSearchFilter">{LANG.search_result_filter} <i class="fa fa-angle-down" aria-hidden="true"></i></a></div>
    </div>
    <div class="filter-list collapse in" id="collapseMsSearchFilter">
        <div class="row">
            <div class="col-md-18">
                <div class="f-heading">{LANG.categories}</div>
                <div class="row">
                    <!-- BEGIN: cat -->
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="f-item"><a href="{CAT.link}" class="ms-ellipsis">{CAT.cat_name}<!-- BEGIN: checked --> <i class="fa fa-check text-success"></i><!-- END: checked --></a></div>
                    </div>
                    <!-- END: cat -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="f-heading">{LANG.search_sortby}</div>
                <!-- BEGIN: sort -->
                <div class="f-item"><a href="{SORT.link}" class="ms-ellipsis">{SORT.title}<!-- BEGIN: checked --> <i class="fa fa-check text-success"></i><!-- END: checked --></a></div>
                <!-- END: sort -->
            </div>
        </div>
    </div>
    <div class="result-wrapper">
        <!-- BEGIN: result_songs -->
        <div class="result-songs">
            <div class="ms-title-section">
                <h2><a href="{ALL_TABS.song.link}">{LANG.song}</a></h2>
            </div>
            {SONG_HTML}
        </div>
        <!-- END: result_songs -->
        <!-- BEGIN: result_videos -->
        <div class="result-videos">
            <div class="ms-title-section">
                <h2><a href="{ALL_TABS.mv.link}">{LANG.video_alias}</a></h2>
            </div>
            {VIDEO_HTML}
        </div>
        <!-- END: result_videos -->
        <!-- BEGIN: result_albums -->
        <div class="result-albums">
            <div class="ms-title-section">
                <h2><a href="{ALL_TABS.album.link}">{LANG.album}</a></h2>
            </div>
            {ALBUM_HTML}
        </div>
        <!-- END: result_albums -->
        <!-- BEGIN: result_artists -->
        <div class="result-artists">
            <div class="ms-title-section">
                <h2><a href="{ALL_TABS.artist.link}">{LANG.artist}</a></h2>
            </div>
            <div class="ms-gird-singers row">
                <!-- BEGIN: loop -->
                <div class="col-xs-12 col-sm-8 col-md-6 ms-gird-singers-item">
                    <article>
                        <div class="ms-gird-singers-thumb ms-round" style="background-image:url({ROW.resource_avatar_thumb});">
                            <a class="ms-gird-singers-fw" href="{ROW.singer_link}" title="{ROW.artist_name}">
                                <span class="ms-gird-singers-mask">
                                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                                </span>
                            </a>
                            <img src="{ROW.resource_avatar_thumb}" class="ms-gird-singers-cover"/>
                        </div>
                        <div class="ms-gird-singers-description">
                            <h3 class="ms-ellipsis ms-second-title">
                                <a href="{ROW.singer_link}" title="{ROW.artist_name}">{ROW.artist_name}</a>
                            </h3>
                        </div>
                    </article>
                </div>
                <!-- END: loop -->
            </div>
        </div>
        <!-- END: result_artists -->
    </div>
</div>
<!-- END: main -->
