<!-- BEGIN: main -->
<link href="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
<script type="text/javascript" src="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<form id="msAjForm" method="post" action="{FORM_ACTION}" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="artist_type" class="col-form-label text-sm-end col-sm-4">{LANG.type}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <select class="form-select" name="artist_type" id="artist_type">
                            <!-- BEGIN: artist_type -->
                            <option value="{ARTIST_TYPE.key}"{ARTIST_TYPE.selected}>{ARTIST_TYPE.title}</option>
                            <!-- END: artist_type -->
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="artist_birthday" class="col-form-label text-sm-end col-sm-4">{LANG.artist_birthday}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="artist_birthday" id="artist_birthday" value="{DATA.artist_birthday}" maxlength="10" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="artist_birthday_lev" class="col-form-label text-sm-end col-sm-4">{LANG.artist_birthday_lev}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <select class="form-select" name="artist_birthday_lev" id="artist_birthday_lev">
                            <!-- BEGIN: artist_birthday_lev -->
                            <option value="{ARTIST_BIRTHDAY_LEV.key}"{ARTIST_BIRTHDAY_LEV.selected}>{ARTIST_BIRTHDAY_LEV.title}</option>
                            <!-- END: artist_birthday_lev -->
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nation_id" class="col-form-label text-sm-end col-sm-4">{LANG.nation}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <select class="form-select" name="nation_id" id="nation_id">
                            <option value="0">--</option>
                            <!-- BEGIN: nation -->
                            <option value="{NATION.nation_id}"{NATION.selected}>{NATION.nation_name}</option>
                            <!-- END: nation -->
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="resource_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.resource_avatar} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_avatar_artist_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_avatar" id="resource_avatar" value="{DATA.resource_avatar}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_avatar" data-type="image" data-path="{RESOURCE_AVATAR_PATH}" data-currentpath="{RESOURCE_AVATAR_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="resource_cover" class="col-form-label text-sm-end col-sm-4">{LANG.resource_cover} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_cover_artist_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 ms-row mb-3-last">
                    <div class="offset-sm-4 col-sm-8 col-md-5 col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_inhome" id="show_inhome" value="1"{DATA.show_inhome}/>
                            <label class="form-check-label" for="show_inhome">{LANG.show_inhome}.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h2>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="artist_name" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.artist_name1}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control required" type="text" name="artist_name" id="artist_name" value="{DATA.artist_name}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="artist_alias" class="col-form-label text-sm-end col-sm-4">{LANG.alias}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="artist_alias" id="artist_alias" value="{DATA.artist_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="artist_realname" class="col-form-label text-sm-end col-sm-4">{LANG.artist_realname}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="artist_realname" id="artist_realname" value="{DATA.artist_realname}" maxlength="255" />
                    </div>
                </div>
                <div class="row mb-3 ms-row mb-3-last">
                    <label for="artist_hometown" class="col-form-label text-sm-end col-sm-4">{LANG.artist_hometown}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="artist_hometown" id="artist_hometown" value="{DATA.artist_hometown}" maxlength="255" />
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="offset-sm-4 col-sm-8">
                        <h3><strong>{LANG.artist_info_as_singer}</strong></h3>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="singer_nickname" class="col-form-label text-sm-end col-sm-4">{LANG.artist_nickname}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="singer_nickname" id="singer_nickname" value="{DATA.singer_nickname}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="singer_prize" class="col-form-label text-sm-end col-sm-4">{LANG.prize}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="singer_prize" id="singer_prize" rows="3">{DATA.singer_prize}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="singer_introtext" class="col-form-label text-sm-end col-sm-4">{LANG.introtext}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="singer_introtext" id="singer_introtext" rows="3">{DATA.singer_introtext}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="singer_keywords" class="col-form-label text-sm-end col-sm-4">{LANG.keywords}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="singer_keywords" id="singer_keywords" value="{DATA.singer_keywords}"/>
                    </div>
                </div>
                <div class="row mb-3 ms-row mb-3-last">
                    <div class="col-12">
                        <div class="ckeditor">
                            <label class="col-form-label text-sm-end">{LANG.bodytext}:</label>
                            <div class="clearfix">
                                {DATA.singer_info}
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="offset-sm-4 col-sm-8">
                        <h3><strong>{LANG.artist_info_as_author}</strong></h3>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="author_nickname" class="col-form-label text-sm-end col-sm-4">{LANG.artist_nickname}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="author_nickname" id="author_nickname" value="{DATA.author_nickname}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="author_prize" class="col-form-label text-sm-end col-sm-4">{LANG.prize}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="author_prize" id="author_prize" rows="3">{DATA.author_prize}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="author_introtext" class="col-form-label text-sm-end col-sm-4">{LANG.introtext}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="author_introtext" id="author_introtext" rows="3">{DATA.author_introtext}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="author_keywords" class="col-form-label text-sm-end col-sm-4">{LANG.keywords}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="author_keywords" id="author_keywords" value="{DATA.author_keywords}"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="ckeditor">
                            <label class="col-form-label text-sm-end">{LANG.bodytext}:</label>
                            <div class="clearfix">
                                {DATA.author_info}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="offset-sm-4 col-sm-8">
                <input type="hidden" name="submitform" value="1"/>
                <input name="redirect" type="hidden" value="0" />
                <input name="submitcontinue" type="hidden" value="0" />
                <input id="msBtnSubmit" type="submit" value="{GLANG.save}" class="btn btn-primary"/>
                <!-- BEGIN: save_continue --><input id="msBtnSubmitCon" type="button" class="btn btn-success" value="{LANG.save_and_continue}"/><!-- END: save_continue -->
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
$(function() {
    $('#artist_birthday').datepicker({
        format: "dd-mm-yyyy",
        language: "{NV_LANG_INTERFACE}",
        autoclose: true,
        todayHighlight: true
    });
    $('#msBtnSubmit').click(function() {
        $('[name="submitcontinue"]').val(0);
    });
    $('#msBtnSubmitCon').click(function() {
        $('[name="submitcontinue"]').val(1);
        $('#msAjForm').submit();
    });
});
</script>
<!-- END: main -->
