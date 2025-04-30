<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table ms-table">
        <thead>
            <tr>
                <th style="width: 80%;">{LANG.artist_name1}</th>
                <th style="width: 20%;" class="text-right">{LANG.action}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>
                    <img src="{ROW.resource_avatar_thumb}" alt="{ROW.album_name}" height="32" class="pull-left ms-img"/>
                    <div data-toggle="ellipsis"><h3 data-toggle="items"><a href="{ROW.artist_link}" class="ms-title" target="_blank">{ROW.artist_name}</a></h3></div>
                    <small class="text-muted">{ROW.real_artist_type}</small>
                </td>
                <td class="text-right">
                    <a href="#" data-toggle="selPickArtist" data-selected="{ROW_SELECT1}" data-selected-mess="{LANG.selected}" data-id="{ROW.artist_id}" data-title="{ROW.artist_name}" class="btn btn-success btn-sm ms-btn-in-list">{ROW_SELECT2}</a>
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
