<!-- BEGIN: main -->
<form class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card">
            <table class="table ms-table ms-table-list-with-action-bottom table-sticky mb-0 mt-1">
                <thead>
                    <tr>
                        <th style="width:5%">
                            <input class="form-check-input" name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                        </th>
                        <th style="width:10%">{LANG.weight}</th>
                        <th style="width:30%">{LANG.title}</th>
                        <th style="width:15%">{LANG.create}</th>
                        <th style="width:15%">{LANG.update}</th>
                        <th style="width:15%" class="text-center">{LANG.status}</th>
                        <th style="width:10%" class="text-end">{LANG.action}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr>
                        <td>
                            <input class="ms-check-in-list form-check-input" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.quality_id}" name="idcheck[]" />
                        </td>
                        <td>
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.quality_id}">
                                <span class="text" data-text="{ROW.weight}">{ROW.weight}</span>
                                <span class="caret"></span>
                            </button>
                        </td>
                        <td>
                            <h5>{ROW.quality_name}</h5>
                            <ul class="list-inline mb-0">
                                <!-- BEGIN: online_supported --><li class="list-inline-item"><span class="text-muted"><i title="{LANG.qso_online_supported}" class="fa fa-microphone"></i></span></li><!-- END: online_supported -->
                                <!-- BEGIN: online_notsupported --><li class="list-inline-item"><span class="text-muted"><i title="{LANG.qso_online_notsupported}" class="fa fa-microphone-slash"></i></span></li><!-- END: online_notsupported -->
                                <!-- BEGIN: is_default --><li class="list-inline-item"><span class="text-muted"><i title="{LANG.qso_is_default}" class="fa fa-check-circle"></i></span></li><!-- END: is_default -->
                                <!-- BEGIN: no_default --><li class="list-inline-item"><span class="text-muted"><i title="{LANG.qso_no_default}" class="fa fa-circle"></i></span></li><!-- END: no_default -->
                            </ul>
                        </td>
                        <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                        <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                        <td class="text-center">
                            <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.quality_id}" class="ms-check-in-list form-check-input" type="checkbox" value="1"{ROW.status}/>
                        </td>
                        <td class="text-end">
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.quality_id}" data-name="{ROW.quality_name}" data-options="{ACTION_KEY}" data-langs="{ACTION_LANG}">
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
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center gap-2">
                <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm" data-type="actions" data-op="{OP}" data-msg="{LANG.error_check_row}" data-target="[name='idcheck[]']" data-options="active|deactive|delete" data-langs="{LANG.action_active}|{LANG.action_deactive}|{GLANG.delete}">
                    <span class="text" data-text="{LANG.with_selected}">{LANG.with_selected}</span>
                    <span class="caret"></span>
                </button>
                <a href="#" data-toggle="trigerformmodal" class="btn btn-sm btn-success"><i class="fa fa-fw fa-plus"></i>{LANG.add_new}</a>
            </div>
            <div class="pagination-wrap">
            </div>
        </div>
    </div>
</form>

<div class="modal" tabindex="-1" role="dialog" id="formmodal" data-bs-backdrop="static" data-changed="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-pencil"></i> <span class="tit" data-msgadd="{LANG.qso_add}" data-msgedit="{LANG.qso_edit}"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{LANG.close}"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert" data-msgadd="{LANG.qso_add_mgs}" data-msgedit="{LANG.qso_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <h5><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h5>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" id="checkc606C73t" type="checkbox" name="online_supported" value="1" data-checked="1"/>
                            <label class="form-check-label" for="checkc606C73t">{LANG.qso_online_supported}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="checki4A9oj2I" type="checkbox" name="is_default" value="1" data-checked="0"/>
                            <label class="form-check-label" for="checki4A9oj2I">{LANG.qso_is_default}</label>
                        </div>
                    </div>
                    <h5><i class="fa fa-fw fa-info-circle"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h5>
                    <div class="mb-3">
                        <label for="quality_name" class="form-label">{LANG.title} <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small>:</label>
                        <input type="text" name="quality_name" id="quality_name" value="" class="form-control"/>
                    </div>
                    <div class="mb-0">
                        <label for="quality_alias" class="form-label">{LANG.alias}:</label>
                        <input type="text" name="quality_alias" id="quality_alias" value="" class="form-control"/>
                    </div>
                    <input type="submit" class="hidden" name="submitform" value="submit"/>
                    <input type="hidden" name="id" value="0"/>
                    <input type="hidden" name="submittype" value="back"/>
                </form>
            </div>
            <div class="modal-footer">
                <div class="float-start">
                     <small class="text-danger">(<i class="fa fa-asterisk"></i>)</small> {LANG.is_required}.
                </div>
                <button type="button" class="btn btn-success" id="formmodalsaveandcon"><i class="fa fa-angle-double-right" aria-hidden="true"></i> {LANG.save_and_continue}</button>
                <button type="button" class="btn btn-primary" id="formmodalsaveandback"><i class="fa fa-floppy-o" aria-hidden="true"></i> {LANG.save}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> {LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<!-- END: main -->
