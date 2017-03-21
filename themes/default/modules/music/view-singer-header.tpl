<!-- BEGIN: main -->
<div class="ms-artist-header">
    <div class="ms-artist-header-top" style="background-image:url({SINGER.resource_cover});">
        <div class="ms-artist-header-info">
            <h1>{SINGER.artist_name}</h1>
            <!-- BEGIN: headtext -->
            <p>{HEADTEXT} <a href="{LINK_PROFILE_TAB}">{LANG.view_biography}</a></p>
            <!-- END: headtext -->
        </div>
        <div class="ms-artist-header-mask"></div>
    </div>
    <div class="ms-artist-header-nav">
        <div class="ms-artist-avatar">
            <span style="background-image:url({SINGER.resource_avatar});">
                <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
            </span>
        </div>
        <ul>
            <li><a href="{LINK_DEFAULT_TAB}"<!-- BEGIN: active_default_tab --> class="active"<!-- END: active_default_tab -->>{LANG.view_singer_tab_default}</a></li>
            <!-- BEGIN: tabloop -->
            <li><a href="{TAB_LINK}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{TAB_TITLE}</a></li>
            <!-- END: tabloop -->
        </ul>
    </div>
</div>
<!-- END: main -->