<!-- BEGIN: main -->
<div class="ms-gird-albums row">
    <!-- BEGIN: loop -->
    <div class="col-xs-12 col-sm-8 col-md-6 ms-gird-albums-item">
        <article>
            <div class="ms-gird-albums-thumb" style="background-image:url({ROW.resource_avatar});">
                <a class="ms-gird-albums-fw" href="#" title="{ROW.album_name}">
                    <span class="ms-gird-albums-mask">
                        <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif"/>
                    </span>
                    <span class="ms-gird-albums-iconplay"><i class="fa fa-play-circle-o"></i></span>
                </a>
                <img src="{ROW.resource_avatar}" class="ms-gird-cover"/>
            </div>
            <div class="ms-gird-albums-description">
                <h3 class="ms-ellipsis ms-second-title">
                    <a href="{ROW.link}" title="{ROW.album_name}">{ROW.album_name}</a>
                </h3>
                <div class="ms-ellipsis">
                    <h4 class="ms-third-title">
                        <a href="{ROW.singer_link}" title="{ROW.singer_name}">Khắc Việt</a>
                    </h4>
                </div>
            </div>
        </article>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->