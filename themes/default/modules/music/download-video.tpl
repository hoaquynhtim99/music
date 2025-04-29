<!-- BEGIN: main -->
<!-- BEGIN: nothing -->
<div class="px-2">
    <strong class="text-danger">{LANG.download_video_error_empty}.</strong>
</div>
<!-- END: nothing -->
<!-- BEGIN: data -->
<div class="px-2">
    {LANG.download_select_quality}
</div>
<hr class="mt-2 mb-2" />
<div class="px-2">
    <ul class="list-unstyled mb-0 ms-list-download-song">
        <!-- BEGIN: loop -->
        <li class="clearfix">
            {QUALITY.quality_name}
            <div class="pull-right">
                <a class="btn btn-success btn-xs" href="{RESOURCE.link_download}"<!-- BEGIN: target_blank --> target="_blank" title="{LANG.download_tip_link}"<!-- END: target_blank --><!-- BEGIN: direct --> download title="{LANG.download_tip_direct}"<!-- END: direct -->><!-- BEGIN: icon_link --><i class="fa fa-external-link" aria-hidden="true"></i><!-- END: icon_link --><!-- BEGIN: icon_down --><i class="fa fa-download" aria-hidden="true"></i><!-- END: icon_down --> {LANG.download}</a>
            </div>
        </li>
        <!-- END: loop -->
    </ul>
</div>
<!-- END: data -->
<!-- END: main -->
