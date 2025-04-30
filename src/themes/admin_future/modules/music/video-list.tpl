<!-- BEGIN: main -->
<form method="get" action="{FORM_ACTION}">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="search_keyword">{LANG.keywords}:</label>
                <input type="text" class="form-control" name="q" id="search_keyword" value="{SEARCH.q}"/>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="search_cat">{LANG.cat}:</label>
                <select class="form-select" name="c">
                    <option value="0">--</option>
                    <!-- BEGIN: cat --><option value="{CAT.cat_id}"{CAT.selected}>{CAT.cat_name}</option><!-- END: cat -->
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="search_time">{LANG.time}:</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="f" id="search_from" value="{SEARCH.f}" placeholder="{LANG.from}" autocomplete="off"/>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" name="t" id="search_to" value="{SEARCH.t}" placeholder="{LANG.to}" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
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
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(function() {
    $('#search_from,#search_to').datepicker({
        dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showButtonPanel: true,
        showOn: 'focus',
        isRTL: $('html').attr('dir') == 'rtl'
    });
});
</script>
<form class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table ms-table ms-table-list-with-action-bottom table-sticky mb-0 mt-1">
                <thead>
                    <tr>
                        <th style="width:5%">
                            <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                        </th>
                        <th style="width:27%">{LANG.title}</th>
                        <th style="width:18%">{LANG.video_list_stcat}</th>
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
                            <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.video_id}" name="idcheck[]" />
                        </td>
                        <td>
                            <img src="{ROW.resource_avatar_thumb}" alt="{ROW.album_name}" height="32" class="pull-left ms-img"/>

                            <div data-toggle="ellipsis"><h3 data-toggle="items"><a href="{ROW.video_link}" class="ms-title" target="_blank">{ROW.video_name}</a></h3></div>
                            <small class="text-muted">
                                <!-- BEGIN: show_singer -->
                                <!-- BEGIN: loop --><!-- BEGIN: separate -->, <!-- END: separate -->
                                <a href="{SINGER.singer_link}" title="{SINGER.artist_name}" target="_blank">{SINGER.artist_name}</a><!-- END: loop -->
                                <!-- END: show_singer -->

                                <!-- BEGIN: va_singer -->
                                <a href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-singers-{ROW.video_code}">{VA_SINGERS}</a>
                                <span class="hidden" id="{UNIQUEID}-singers-{ROW.video_code}" title="{LANG.singer_list}">
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
                                <a class="ms-title" href="#" data-toggle="show-va-singer" data-target="#{UNIQUEID}-authors-{ROW.video_code}">{VA_AUTHORS}</a>
                                <span class="hidden" id="{UNIQUEID}-authors-{ROW.video_code}" title="{LANG.author_list}">
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
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.video_id}" data-name="{ROW.video_name}" data-options="{ACTION_STATUS}" data-langs="{LANG_STATUS}">
                                <span class="text" data-text="{ROW.state}">{ROW.state}</span>
                                <span class="caret"></span>
                            </button>
                        </td>
                        <td class="text-right">
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.video_id}" data-name="{ROW.video_name}" data-options="edit|delete" data-langs="{GLANG.edit}|{GLANG.delete}" data-urledit="{ROW.url_edit}">
                                <span class="text" data-text="{LANG.select}">{LANG.select}</span>
                                <span class="caret"></span>
                            </button>
                        </td>
                    </tr>
                    <!-- END: loop -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                <div class="form-group">
                    <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="active|deactive|delete" data-langs="{LANG.action_active}|{LANG.action_deactive}|{GLANG.delete}">
                        <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                        <span class="caret"></span>
                    </button>
                    <a href="{LINK_ADD}" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
                </div>
            </div>
            <div class="pagination-wrap">
                <!-- BEGIN: generate_page -->
                {GENERATE_PAGE}
                <!-- END: generate_page -->
            </div>
        </div>
    </div>
</form>

<!-- END: main -->
