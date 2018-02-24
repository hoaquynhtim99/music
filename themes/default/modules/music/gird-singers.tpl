<!-- BEGIN: main -->
<h1 class="ms-singers-alltitle">{LANG.singer_list}</h1>

<!-- BEGIN: nav -->
<ul class="ms-singers-nation-nav">
    <li><a href="{NATION_ALL_LINK}"<!-- BEGIN: all_active --> class="active"<!-- END: all_active -->>{LANG.all}</a></li>
    <!-- BEGIN: loop --><li><a href="{NATION.nation_link}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{NATION.nation_name}</a></li><!-- END: loop -->
</ul>
<!-- END: nav -->

<!-- BEGIN: alphabet -->
<div class="margin-bottom">
    <ul class="ms-singers-alphabet">
        <li><a href="{ALPHABET_ALL_LINK}"<!-- BEGIN: all_active --> class="active"<!-- END: all_active -->>{ALPHABET_ALL_TITLE}</a></li>
        <!-- BEGIN: loop -->
        <li><a href="{ALPHABET_LINK}"<!-- BEGIN: active --> class="active"<!-- END: active -->>{ALPHABET_TITLE}</a></li>
        <!-- END: loop -->
    </ul>
</div>
<!-- END: alphabet -->

<div class="ms-gird-singers row">
    <!-- BEGIN: loop -->
    <div class="col-xs-12 col-sm-8 col-md-6 ms-gird-singers-item">
        <article>
            <div class="ms-gird-singers-thumb" style="background-image:url({ROW.resource_avatar_thumb});">
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

<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<!-- END: main -->