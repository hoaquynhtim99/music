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
                <label for="search_cat">{LANG.cat}:</label>
                <select class="form-control" name="c">
                    <option value="0">--</option>
                    <!-- BEGIN: cat --><option value="{CAT.cat_id}"{CAT.selected}>{CAT.cat_name}</option><!-- END: cat -->
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="search_time">{LANG.time}:</label>
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" name="f" id="search_from" value="{SEARCH.f}" placeholder="{LANG.from}"/>
                    </div>
                    <div class="col-xs-12">
                        <input type="text" class="form-control" name="t" id="search_to" value="{SEARCH.t}" placeholder="{LANG.to}"/>
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
                    <th style="width:25%">{LANG.title}</th>
                    <th style="width:20%">{LANG.song_list_stcat}</th>
                    <th style="width:10%">{LANG.listen_hits}</th>
                    <th style="width:10%">{LANG.create}</th>
                    <th style="width:10%">{LANG.update}</th>
                    <th style="width:10%">{LANG.state}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.song_id}" name="idcheck[]" />
                    </td>
                    <td>
                        <div data-toggle="ellipsis"><h3 data-toggle="items"><a href="{ROW.song_link}" class="ms-title" target="_blank">{ROW.song_name}</a></h3></div>
                        <small class="text-muted">
                            <!-- BEGIN: show_singer -->
                            <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                            <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" target="_blank">{SINGER.artist_name}</a><!-- END: loop -->
                            <!-- END: show_singer -->

                            <!-- BEGIN: va_singer -->
                            <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-song-singers-{SONG.song_code}">{VA_SINGERS}</a>
                            <span class="hidden" id="{UNIQUEID}-song-singers-{SONG.song_code}" title="{LANG.singer_list}">
                                <span class="list-group ms-singer-listgr-modal">
                                    <!-- BEGIN: loop -->
                                    <a href="{SINGER.singer_link}" class="list-group-item">{SINGER.artist_name}</a>
                                    <!-- END: loop -->
                                </span>
                            </span>
                            <!-- END: va_singer -->

                            <!-- BEGIN: no_singer -->{UNKNOW_SINGER}<!-- END: no_singer -->
                        </small>
                    </td>
                    <td>
                        <h3>
                            <!-- BEGIN: show_author -->
                            <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->{AUTHOR.artist_name}<!-- END: loop -->
                            <!-- END: show_author -->

                            <!-- BEGIN: va_author -->
                            <a class="ms-title" href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-song-authors-{SONG.song_code}">{VA_AUTHORS}</a>
                            <span class="hidden" id="{UNIQUEID}-song-authors-{SONG.song_code}" title="{LANG.author_list}">
                                <span class="list-group ms-author-listgr-modal">
                                    <!-- BEGIN: loop -->
                                    <a href="{AUTHOR.author_link}" class="list-group-item">{AUTHOR.artist_name}</a>
                                    <!-- END: loop -->
                                </span>
                            </span>
                            <!-- END: va_author -->

                            <!-- BEGIN: no_author -->{UNKNOW_AUTHOR}<!-- END: no_author -->
                        </h3>
                        <span class="text-muted">
                            <!-- BEGIN: show_cat -->
                            <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->{CAT.cat_name}<!-- END: loop -->
                            <!-- END: show_cat -->

                            <!-- BEGIN: no_cat -->{UNKNOW_CAT}<!-- END: no_cat -->
                        </span>
                    </td>
                    <td>
                        <h3><i class="fa fa-headphones fa-fw" aria-hidden="true"></i>{ROW.stat_views}</h3>
                        <span class="text-muted"><i class="fa fa-comments fa-fw" aria-hidden="true"></i>{ROW.stat_comments}</span>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.song_id}" data-name="{ROW.song_name}" data-options="ajedit|delete" data-langs="{GLANG.edit}|{GLANG.delete}">
                            <span class="text" data-text="{ROW.state}">{ROW.state}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.song_id}" data-name="{ROW.song_name}" data-options="ajedit|delete" data-langs="{GLANG.edit}|{GLANG.delete}">
                            <span class="text" data-text="{LANG.select}">{LANG.select}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="">
                        <div class="row">
                            <div class="col-sm-10 form-inline">
                                <div class="form-group">
                                    <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="delete" data-langs="{GLANG.delete}">
                                        <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                                        <span class="caret"></span>
                                    </button>
                                    <a href="#" data-toggle="trigerformmodal" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
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