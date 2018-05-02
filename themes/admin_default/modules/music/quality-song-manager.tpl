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
                    <th style="width:30%">{LANG.title}</th>
                    <th style="width:15%">{LANG.create}</th>
                    <th style="width:15%">{LANG.update}</th>
                    <th style="width:15%" class="text-center">{LANG.status}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.quality_id}" name="idcheck[]" />
                    </td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.quality_id}">
                            <span class="text" data-text="{ROW.weight}">{ROW.weight}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td>
                        <h3>{ROW.quality_name}</h3>
                        <ul class="list-unstyled list-inline ms-list-inline">
                            <!-- BEGIN: online_supported --><li><small class="text-muted"><i title="{LANG.qso_online_supported}" class="fa fa-microphone"></i></small></li><!-- END: online_supported -->
                            <!-- BEGIN: online_notsupported --><li><small class="text-muted"><i title="{LANG.qso_online_notsupported}" class="fa fa-microphone-slash"></i></small></li><!-- END: online_notsupported -->
                            <!-- BEGIN: is_default --><li><small class="text-muted"><i title="{LANG.qso_is_default}" class="fa fa-check-circle"></i></small></li><!-- END: is_default -->
                            <!-- BEGIN: no_default --><li><small class="text-muted"><i title="{LANG.qso_no_default}" class="fa fa-circle"></i></small></li><!-- END: no_default -->
                        </ul>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td class="text-center">
                        <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.quality_id}" class="ms-check-in-list" type="checkbox" value="1"{ROW.status}/>
                    </td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.quality_id}" data-name="{ROW.quality_name}" data-options="{ACTION_KEY}" data-langs="{ACTION_LANG}">
                            <span class="text" data-text="{LANG.select}">{LANG.select}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="">
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
                <h4 class="modal-title"><i class="fa fa-pencil"></i> <span class="tit" data-msgadd="{LANG.quality_add}" data-msgedit="{LANG.quality_edit}"></span></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" data-msgadd="{LANG.quality_add_mgs}" data-msgedit="{LANG.quality_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <div class="form-group">
                        <label for="quality_code" class="control-label">{LANG.quality_code} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                        <span class="help-block">{LANG.quality_code_rule}</span>
                        <input type="text" name="quality_code" id="quality_code" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="quality_name" class="control-label">{LANG.quality_name} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                        <input type="text" name="quality_name" id="quality_name" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="quality_alias" class="control-label">{LANG.alias}:</label>
                        <input type="text" name="quality_alias" id="quality_alias" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="quality_introtext" class="control-label">{LANG.introtext}:</label>
                        <input type="text" name="quality_introtext" id="quality_introtext" value="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="quality_keywords" class="control-label">{LANG.keywords}:</label>
                        <input type="text" name="quality_keywords" id="quality_keywords" value="" class="form-control"/>
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