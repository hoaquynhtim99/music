<!-- BEGIN: main -->
<div class="table-responsive-lg">
    <table class="table ms-table table-sticky">
        <thead>
            <tr>
                <th style="width: 80%;">{LANG.song_name}</th>
                <th style="width: 20%;" class="text-end">{LANG.action}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                    <div data-toggle="ellipsis"><h5 data-toggle="items" class="mb-0"><a href="{ROW.song_link}" class="ms-title" target="_blank">{ROW.song_name}</a></h5></div>
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
                <td class="text-end">
                    <a href="#" data-toggle="selPickSong" data-selected="{ROW_SELECT1}" data-selected-mess="{LANG.selected}" data-id="{ROW.song_id}" data-title="{ROW.song_name}" data-des="{ROW_SINGER}" class="btn btn-success btn-sm ms-btn-in-list">{ROW_SELECT2}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <!-- BEGIN: generate_page -->
        <tfoot>
            <tr>
                <td colspan="2">
                    <div class="float-end">
                        {GENERATE_PAGE}
                    </div>
                </td>
            </tr>
        </tfoot>
        <!-- END: generate_page -->
    </table>
</div>
<!-- END: main -->
