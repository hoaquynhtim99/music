<!-- BEGIN: main -->
<form>
    <div class="table-responsive">
        <table class="table ms-table ms-table-list-with-action-bottom">
            <thead>
                <tr>
                    <th style="width:5%">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                    </th>
                    <th style="width:10%">{LANG.weight}</th>
                    <th style="width:25%">{LANG.title}</th>
                    <th style="width:10%">{LANG.create}</th>
                    <th style="width:10%">{LANG.update}</th>
                    <th style="width:10%" class="text-center">{LANG.cat_s_show_inalbum}</th>
                    <th style="width:10%" class="text-center">{LANG.cat_s_show_invideo}</th>
                    <th style="width:10%" class="text-center">{LANG.status}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.cat_id}" name="idcheck[]" />
                    </td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.cat_id}">
                            <span class="text" data-text="{ROW.weight}">{ROW.weight}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td>
                        <h3>{ROW.cat_name}</h3>
                        <ul class="list-unstyled list-inline ms-list-inline">
                            <li><small class="text-muted"><i title="{LANG.stat_albums}" class="fa fa-fw fa-file-archive-o"></i>{ROW.stat_albums}</small></li>
                            <li><small class="text-muted"><i title="{LANG.stat_songs}" class="fa fa-fw fa-file-audio-o"></i>{ROW.stat_songs}</small></li>
                            <li><small class="text-muted"><i title="{LANG.stat_videos}" class="fa fa-fw fa-file-video-o"></i>{ROW.stat_videos}</small></li>
                        </ul>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td class="text-center">
                        <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" class="ms-check-in-list" type="checkbox" value="1"{ROW.show_inalbum}/>
                    </td>
                    <td class="text-center">
                        <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" class="ms-check-in-list" type="checkbox" value="1"{ROW.show_invideo}/>
                    </td>
                    <td class="text-center">
                        <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" class="ms-check-in-list" type="checkbox" value="1"{ROW.status}/>
                    </td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.cat_id}" data-name="{ROW.cat_name}" data-options="{ACTION_KEY}" data-langs="{ACTION_LANG}">
                            <span class="text" data-text="{LANG.select}">{LANG.select}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="">
                        <div class="row">
                            <div class="col-sm-10 form-inline">
                                <div class="form-group">
                                    <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="active|deactive|delete" data-langs="{LANG.action_active}|{LANG.action_deactive}|{GLANG.delete}">
                                        <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                                        <span class="caret"></span>
                                    </button>
                                    <a href="#" data-toggle="trigerformmodal" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
                                </div>
                            </div>
                            <div class="col-sm-14">
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

<div class="modal fade" tabindex="-1" role="dialog" id="formmodal" data-backdrop="static" data-changed="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> <span class="tit" data-msgadd="{LANG.qso_add}" data-msgedit="{LANG.qso_edit}"></span></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" data-msgadd="{LANG.qso_add_mgs}" data-msgedit="{LANG.qso_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <div class="form-group">
                        <label for="cat_name" class="control-label">{LANG.title} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                        <input type="text" name="cat_name" id="cat_name" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="cat_alias" class="control-label">{LANG.alias}:</label>
                        <input type="text" name="cat_alias" id="cat_alias" value="" class="form-control"/>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="online_supported" value="1" data-checked="1"/>
                            {LANG.qso_online_supported}
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_default" value="1" data-checked="0"/>
                            {LANG.qso_is_default}
                        </label>
                    </div>
                    <input type="submit" class="hidden" name="submit" value="submit"/>
                    <input type="hidden" name="id" value="0"/>
                    <input type="hidden" name="submittype" value="back"/>
                </form>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                     <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small> {LANG.is_required}.
                </div>
                <button type="button" class="btn btn-success" id="formmodalsaveandcon"><i class="fa fa-angle-double-right" aria-hidden="true"></i> {LANG.save_and_continue}</button>
                <button type="button" class="btn btn-primary" id="formmodalsaveandback"><i class="fa fa-floppy-o" aria-hidden="true"></i> {LANG.save}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> {LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<!-- END: main -->