<!-- BEGIN: main -->
<form method="get" action="{FORM_ACTION}">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="search_keyword">{LANG.keywords}:</label>
                <input type="text" class="form-control" name="q" id="search_keyword" value="{SEARCH.q}"/>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="search_cat">{LANG.type}:</label>
                <select class="form-control" name="tp">
                    <option value="-1">--</option>
                    <!-- BEGIN: artist_type --><option value="{ARTIST_TYPE.key}"{ARTIST_TYPE.selected}>{ARTIST_TYPE.title}</option><!-- END: artist_type -->
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="search_time">{LANG.time}:</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" name="f" id="search_from" value="{SEARCH.f}" placeholder="{LANG.from}" autocomplete="off"/>
                    </div>
                    <div class="col-xs-12">
                        <input type="text" class="form-control" name="t" id="search_to" value="{SEARCH.t}" placeholder="{LANG.to}" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="search_submit">&nbsp;</label>
                <div class="clearfix">
                    <input id="search_submit" type="submit" value="{GLANG.search}" class="btn btn-primary"/>
                    <div class="pull-right">
                        <button type="button" class="btn btn-info hidden">{LANG.adv}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="ms-search-hr"/>
</form>
<link href="{NV_BASE_SITEURL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<script type="text/javascript">
$(function() {
    $('#search_from,#search_to').datepicker({
        format: "dd-mm-yyyy",
        language: "{NV_LANG_INTERFACE}",
        autoclose: true,
        todayHighlight: true
    });
});
</script>
<form>
    <div class="table-responsive">
        <table class="table ms-table ms-table-list-with-action-bottom">
            <thead>
                <tr>
                    <th style="width:5%">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                    </th>
                    <th style="width:40%">{LANG.title}</th>
                    <th style="width:15%">{LANG.create}</th>
                    <th style="width:15%">{LANG.update}</th>
                    <th style="width:15%">{LANG.state}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.artist_id}" name="idcheck[]" />
                    </td>
                    <td>
                        <img src="{ROW.resource_avatar_thumb}" alt="{ROW.album_name}" height="32" class="pull-left ms-img"/>
                        <div data-toggle="ellipsis"><h3 data-toggle="items"><a href="{ROW.artist_link}" class="ms-title" target="_blank">{ROW.artist_name}</a></h3></div>
                        <small class="text-muted">{ROW.real_artist_type}</small>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.artist_id}" data-name="{ROW.artist_name}" data-options="{ACTION_STATUS}" data-langs="{LANG_STATUS}">
                            <span class="text" data-text="{ROW.state}">{ROW.state}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.artist_id}" data-name="{ROW.artist_name}" data-options="edit|delete" data-langs="{GLANG.edit}|{GLANG.delete}" data-urledit="{ROW.url_edit}">
                            <span class="text" data-text="{LANG.select}">{LANG.select}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="">
                        <div class="row">
                            <div class="col-sm-10 form-inline">
                                <div class="form-group">
                                    <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="active|deactive|delete" data-langs="{LANG.action_active}|{LANG.action_deactive}|{GLANG.delete}">
                                        <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                                        <span class="caret"></span>
                                    </button>
                                    <a href="{LINK_ADD}" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
                                </div>
                            </div>
                            <div class="col-sm-14">
                                <div class="pull-right">
                                    <!-- BEGIN: generate_page -->
                                    {GENERATE_PAGE}
                                    <!-- END: generate_page -->
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

<!-- END: main -->
