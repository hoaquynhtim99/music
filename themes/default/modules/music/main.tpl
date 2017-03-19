<!-- BEGIN: albums -->
<div class="ms-title-section">
    <h2>
        <a href="{ALBUMS_LINK}">{LANG.album}</a>
    </h2>
</div>
{ALBUMS_HTML}
<!-- END: albums -->

<!-- BEGIN: singers -->
<div class="ms-title-section">
    <h2>
        <a href="{SINGERS_LINK}">{LANG.singer}</a>
    </h2>
</div>
<div class="row ms-main-singer">
    <!-- BEGIN: loop -->
    <div class="<!-- BEGIN: x1 -->col-xs-12 col-sm-8 col-md-4<!-- END: x1 --><!-- BEGIN: x2 -->col-sm-8 col-md-8 visible-sm visible-md visible-lg<!-- END: x2 --> ms-main-singer-item">
        <article>
            <div class="ms-main-singer-thumb" style="background-image:url({SINGER.resource_avatar});">
                <a class="ms-main-singer-fw" href="{SINGER.singer_link}">
                    <span class="ms-main-singer-mask">
                        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                </a>
                <h4><a href="{SINGER.singer_link}">{SINGER.singer_name}</a></h4>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
<!-- END: singers -->

<!-- BEGIN: songs -->
<div class="ms-title-section">
    <h2>
        <a href="{SONGS_LINK}">{LANG.song}</a>
    </h2>
</div>
{SONGS_HTML}
<!-- END: songs -->

<!-- BEGIN: videos -->
<div class="ms-title-section">
    <h2>
        <a href="{VIDEOS_LINK}">{LANG.video}</a>
    </h2>
</div>
{VIDEOS_HTML}
<!-- END: videos -->