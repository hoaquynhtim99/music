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
                    <th style="width:20%">{LANG.stat}</th>
                    <th style="width:15%">{LANG.create}</th>
                    <th style="width:15%">{LANG.update}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.nation_id}" name="idcheck[]" />
                    </td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.nation_id}">
                            <span class="text" data-text="{ROW.weight}">{ROW.weight}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td>
                        <h3>{ROW.nation_name}</h3>
                        <small class="text-muted">{ROW.nation_code}</small>
                    </td>
                    <td>
                        <span title="{LANG.stat_singers}"><span class="text-muted">{LANG.acr_singer}</span>&nbsp;{ROW.stat_singers}</span><br />
                        <span title="{LANG.stat_authors}"><span class="text-muted">{LANG.acr_author}</span>&nbsp;{ROW.stat_authors}</span>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.nation_id}" data-name="{ROW.nation_name}" data-options="ajedit|delete" data-langs="{GLANG.edit}|{GLANG.delete}">
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
                            <div class="col-sm-5 form-inline">
                                <div class="form-group">
                                    <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="delete" data-langs="{GLANG.delete}">
                                        <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                                        <span class="caret"></span>
                                    </button>
                                    <a href="#" data-toggle="trigerformmodal" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
                                </div>
                            </div>
                            <div class="col-sm-7">
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

<div class="modal" tabindex="-1" role="dialog" id="formmodal" data-backdrop="static" data-changed="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> <span class="tit" data-msgadd="{LANG.nation_add}" data-msgedit="{LANG.nation_edit}"></span></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert" data-msgadd="{LANG.nation_add_mgs}" data-msgedit="{LANG.nation_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group ms-form-group-last">
                                <label for="nation_code" class="control-label">{LANG.nation_code} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                                <span class="help-block">{LANG.nation_code_rule}</span>
                                <input type="text" name="nation_code" id="nation_code" value="" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h2>
                    <div class="panel panel-default ms-form-group-last">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="nation_name" class="control-label">{LANG.nation_name} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                                <input type="text" name="nation_name" id="nation_name" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="nation_alias" class="control-label">{LANG.alias}:</label>
                                <input type="text" name="nation_alias" id="nation_alias" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="nation_introtext" class="control-label">{LANG.introtext}:</label>
                                <input type="text" name="nation_introtext" id="nation_introtext" value="" class="form-control"/>
                            </div>
                            <div class="form-group ms-form-group-last">
                                <label for="nation_keywords" class="control-label">{LANG.keywords}:</label>
                                <input type="text" name="nation_keywords" id="nation_keywords" value="" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="hidden" name="submitform" value="submit"/>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<!-- END: main -->
