<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table ms-table">
        <thead>
            <tr>
                <th style="width: 80%;">{LANG.video_name}</th>
                <th style="width: 20%;" class="text-right">{LANG.action}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                    <div data-toggle="ellipsis"><h3 data-toggle="items"><a href="{ROW.video_link}" class="ms-title" target="_blank">{ROW.video_name}</a></h3></div>
                    <small class="text-muted">
                        <!-- BEGIN: show_singer -->
                        <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                        <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" target="_blank">{SINGER.artist_name}</a><!-- END: loop -->
                        <!-- END: show_singer -->
                        <!-- BEGIN: va_singer -->
                        <a href="javascript:void(0);" title="{VA_SINGERS_TITLE}">{VA_SINGERS}</a>
                        <!-- END: va_singer -->
                        <!-- BEGIN: no_singer -->{UNKNOW_SINGER}<!-- END: no_singer -->
                    </small>
                </td>
                <td class="text-right">
                    <a href="#" data-toggle="selPickVideo" data-selected="{ROW_SELECT1}" data-selected-mess="{LANG.selected}" data-id="{ROW.video_id}" data-title="{ROW.video_name}" data-des="{ROW_SINGER}" class="btn btn-success btn-sm ms-btn-in-list">{ROW_SELECT2}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="2">
                    <div class="pull-right">
                        {GENERATE_PAGE}
                    </div>
                </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: main -->
