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
                        <th style="width:25%">{LANG.title}</th>
                        <th style="width:10%">{LANG.create}</th>
                        <th style="width:10%">{LANG.update}</th>
                        <th style="width:10%" class="text-center">{LANG.cat_s_show_inalbum}</th>
                        <th style="width:10%" class="text-center">{LANG.cat_s_show_invideo}</th>
                        <th style="width:10%" class="text-center">{LANG.status}</th>
                        <th style="width:10%" class="text-end">{LANG.action}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr>
                        <td>
                            <input class="ms-check-in-list form-check-input" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.cat_id}" name="idcheck[]" />
                        </td>
                        <td>
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.cat_id}">
                                <span class="text" data-text="{ROW.weight}">{ROW.weight}</span>
                                <span class="caret"></span>
                            </button>
                        </td>
                        <td>
                            <h5>{ROW.cat_name}</h5>
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><span class="text-muted"><i title="{LANG.stat_albums}" class="fa-solid fa-file-zipper fa-fw"></i>{ROW.stat_albums}</span></li>
                                <li class="list-inline-item"><span class="text-muted"><i title="{LANG.stat_songs}" class="fa-solid fa-file-audio fa-fw"></i>{ROW.stat_songs}</span></li>
                                <li class="list-inline-item"><span class="text-muted"><i title="{LANG.stat_videos}" class="fa-solid fa-file-video fa-fw"></i>{ROW.stat_videos}</span></li>
                            </ul>
                        </td>
                        <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                        <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                        <td class="text-center">
                            <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" data-action="unactiveinalbum|activeinalbum" class="ms-check-in-list form-check-input" type="checkbox" value="1"{ROW.show_inalbum}/>
                        </td>
                        <td class="text-center">
                            <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" data-action="unactiveinvideo|activeinvideo" class="ms-check-in-list form-check-input" type="checkbox" value="1"{ROW.show_invideo}/>
                        </td>
                        <td class="text-center">
                            <input data-toggle="msactive" data-op="{OP}" data-id="{ROW.cat_id}" class="ms-check-in-list form-check-input" type="checkbox" value="1"{ROW.status}/>
                        </td>
                        <td class="text-end">
                            <button data-toggle="mscallpop" type="button" class="btn btn-secondary btn-sm ms-btn-in-list" data-type="action" data-op="{OP}" data-id="{ROW.cat_id}" data-name="{ROW.cat_name}" data-options="ajedit|delete" data-langs="{GLANG.edit}|{GLANG.delete}">
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
                <a href="#" data-toggle="trigerformmodal" class="btn btn-sm btn-success"><i class="fa-solid fa-plus fa-fw"></i>{LANG.add_new}</a>
            </div>
        </div>
    </div>
</form>

<div class="modal" tabindex="-1" role="dialog" id="formmodal" data-bs-backdrop="static" data-changed="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-pencil"></i> <span class="tit" data-msgadd="{LANG.cat_add}" data-msgedit="{LANG.cat_edit}"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{LANG.close}"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert" data-msgadd="{LANG.cat_add_mgs}" data-msgedit="{LANG.cat_edit_mgs}">&nbsp;</div>
                <form id="formmodalctn" action="" method="post" data-busy="false" data-op="{OP}">
                    <h5><i class="fa-solid fa-circle-info fa-fw"></i>{LANG.info_all}:</h5>
                    <div class="mb-3">
                        <label for="resource_avatar" class="form-label">{LANG.resource_avatar_cat} <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="{LANG.resource_avatar_artist_note}"><i class="fa-solid fa-circle-info"></i></a>:</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_avatar" id="resource_avatar" value="{DATA.resource_avatar}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_avatar" data-type="image" data-path="{RESOURCE_AVATAR_PATH}" data-currentpath="{RESOURCE_AVATAR_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="resource_cover" class="form-label">{LANG.resource_cover_cat} <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="{LANG.resource_cover_artist_note}"><i class="fa-solid fa-circle-info"></i></a>:</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="resource_video" class="form-label">{LANG.resource_video_cat} <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="{LANG.resource_video_note}"><i class="fa-solid fa-circle-info"></i></a>:</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_video" id="resource_video" value="{DATA.resource_video}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_video" data-type="image" data-path="{RESOURCE_VIDEO_PATH}" data-currentpath="{RESOURCE_VIDEO_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" id="checkr3H7dNLR" type="checkbox" name="show_inalbum" value="1" data-checked="0"/>
                            <label class="form-check-label" for="checkr3H7dNLR">{LANG.cat_show_inalbum}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="checkd51i579a" type="checkbox" name="show_invideo" value="1" data-checked="0"/>
                            <label class="form-check-label" for="checkd51i579a">{LANG.cat_show_invideo}</label>
                        </div>
                    </div>
                    <h5><i class="fa-solid fa-circle-info fa-fw"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h5>
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">{LANG.title} <small class="text-danger">(<i class="fa-solid fa-asterisk"></i>)</small>:</label>
                        <input type="text" name="cat_name" id="cat_name" value="" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="cat_alias" class="form-label">{LANG.alias}:</label>
                        <input type="text" name="cat_alias" id="cat_alias" value="" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="cat_absitetitle" class="form-label">{LANG.cat_absitetitle}:</label>
                        <span class="form-text">{LANG.cat_get_default}</span>
                        <input type="text" name="cat_absitetitle" id="cat_absitetitle" value="" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="cat_abintrotext" class="form-label">{LANG.cat_abintrotext}:</label>
                        <textarea name="cat_abintrotext" id="cat_abintrotext" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cat_abkeywords" class="form-label">{LANG.cat_abkeywords}:</label>
                        <textarea name="cat_abkeywords" id="cat_abkeywords" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cat_mvsitetitle" class="form-label">{LANG.cat_mvsitetitle}:</label>
                        <span class="form-text">{LANG.cat_get_default}</span>
                        <input type="text" name="cat_mvsitetitle" id="cat_mvsitetitle" value="" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label for="cat_mvintrotext" class="form-label">{LANG.cat_mvintrotext}:</label>
                        <textarea name="cat_mvintrotext" id="cat_mvintrotext" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-0">
                        <label for="cat_mvkeywords" class="form-label">{LANG.cat_mvkeywords}:</label>
                        <textarea name="cat_mvkeywords" id="cat_mvkeywords" class="form-control" rows="2"></textarea>
                    </div>
                    <input type="submit" class="hidden" name="submitform" value="submit"/>
                    <input type="hidden" name="id" value="0"/>
                    <input type="hidden" name="submittype" value="back"/>
                </form>
            </div>
            <div class="modal-footer">
                <div class="float-start">
                     <small class="text-danger">(<i class="fa-solid fa-asterisk"></i>)</small> {LANG.is_required}.
                </div>
                <button type="button" class="btn btn-success" id="formmodalsaveandcon"><i class="fa-solid fa-angles-right" aria-hidden="true"></i> {LANG.save_and_continue}</button>
                <button type="button" class="btn btn-primary" id="formmodalsaveandback"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> {LANG.save}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> {LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<!-- END: main -->
