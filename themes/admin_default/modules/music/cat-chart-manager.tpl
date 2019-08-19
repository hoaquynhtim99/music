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
                        <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" class="ms-check-in-list" type="checkbox" value="1"{ROW.status}/>
                    </td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.cat_id}" data-name="{ROW.cat_name}" data-options="ajedit|delete" data-langs="{GLANG.edit}|{GLANG.delete}" data-others="|{LANG.chart_delete_confirm}">
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
                                    <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="active|deactive|delete" data-langs="{LANG.action_active}|{LANG.action_deactive}|{GLANG.delete}" data-others="||{LANG.chart_delete_confirm}">
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

<div class="modal" tabindex="-1" role="dialog" id="formmodal" data-backdrop="static" data-changed="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> <span class="tit" data-msgadd="{LANG.chart_add}" data-msgedit="{LANG.chart_edit}"></span></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" data-msgadd="{LANG.chart_add_mgs}" data-msgedit="{LANG.chart_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="resource_cover" class="control-label">{LANG.chart_resource_cover}:</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{LANG.chart_choose_cat}:</label>
                                <div class="row">
                                    <!-- BEGIN: cat -->
                                    <div class="col-xs-12 col-sm-6">
                                        <label><input type="checkbox" name="cat_ids[]" id="cat_ids_{CAT.cat_id}" value="{CAT.cat_id}"> {CAT.cat_name}</label>
                                    </div>
                                    <!-- END: cat -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h2>
                    <div class="panel panel-default ms-form-group-last">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="cat_name" class="control-label">{LANG.title} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                                <input type="text" name="cat_name" id="cat_name" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="cat_alias" class="control-label">{LANG.alias}:</label>
                                <input type="text" name="cat_alias" id="cat_alias" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="cat_absitetitle" class="control-label">{LANG.chart_absitetitle}:</label>
                                <span class="help-block">{LANG.cat_get_default}</span>
                                <input type="text" name="cat_absitetitle" id="cat_absitetitle" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="cat_abintrotext" class="control-label">{LANG.chart_abintrotext}:</label>
                                <textarea name="cat_abintrotext" id="cat_abintrotext" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_abkeywords" class="control-label">{LANG.chart_abkeywords}:</label>
                                <textarea name="cat_abkeywords" id="cat_abkeywords" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_abbodytext" class="control-label">{LANG.chart_abbodytext}:</label>
                                <textarea name="cat_abbodytext" id="cat_abbodytext" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="cat_mvsitetitle" class="control-label">{LANG.chart_mvsitetitle}:</label>
                                <span class="help-block">{LANG.cat_get_default}</span>
                                <input type="text" name="cat_mvsitetitle" id="cat_mvsitetitle" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="cat_mvintrotext" class="control-label">{LANG.chart_mvintrotext}:</label>
                                <textarea name="cat_mvintrotext" id="cat_mvintrotext" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_mvkeywords" class="control-label">{LANG.chart_mvkeywords}:</label>
                                <textarea name="cat_mvkeywords" id="cat_mvkeywords" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_mvbodytext" class="control-label">{LANG.chart_mvbodytext}:</label>
                                <textarea name="cat_mvbodytext" id="cat_mvbodytext" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="cat_sositetitle" class="control-label">{LANG.chart_sositetitle}:</label>
                                <span class="help-block">{LANG.cat_get_default}</span>
                                <input type="text" name="cat_sositetitle" id="cat_sositetitle" value="" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="cat_sointrotext" class="control-label">{LANG.chart_sointrotext}:</label>
                                <textarea name="cat_sointrotext" id="cat_sointrotext" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_sokeywords" class="control-label">{LANG.chart_sokeywords}:</label>
                                <textarea name="cat_sokeywords" id="cat_sokeywords" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cat_sobodytext" class="control-label">{LANG.chart_sobodytext}:</label>
                                <textarea name="cat_sobodytext" id="cat_sobodytext" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
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
