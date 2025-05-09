<!-- BEGIN: main -->
<link href="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
<script type="text/javascript" src="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/admin_default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<form id="msAjForm" method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="artist_type" class="control-label col-sm-8">{LANG.type}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <select class="form-control" name="artist_type" id="artist_type">
                            <!-- BEGIN: artist_type -->
                            <option value="{ARTIST_TYPE.key}"{ARTIST_TYPE.selected}>{ARTIST_TYPE.title}</option>
                            <!-- END: artist_type -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="artist_birthday" class="control-label col-sm-8">{LANG.artist_birthday}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="artist_birthday" id="artist_birthday" value="{DATA.artist_birthday}" maxlength="10" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="artist_birthday_lev" class="control-label col-sm-8">{LANG.artist_birthday_lev}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <select class="form-control" name="artist_birthday_lev" id="artist_birthday_lev">
                            <!-- BEGIN: artist_birthday_lev -->
                            <option value="{ARTIST_BIRTHDAY_LEV.key}"{ARTIST_BIRTHDAY_LEV.selected}>{ARTIST_BIRTHDAY_LEV.title}</option>
                            <!-- END: artist_birthday_lev -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nation_id" class="control-label col-sm-8">{LANG.nation}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <select class="form-control" name="nation_id" id="nation_id">
                            <option value="0">--</option>
                            <!-- BEGIN: nation -->
                            <option value="{NATION.nation_id}"{NATION.selected}>{NATION.nation_name}</option>
                            <!-- END: nation -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="resource_avatar" class="control-label col-sm-8">{LANG.resource_avatar} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_avatar_artist_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_avatar" id="resource_avatar" value="{DATA.resource_avatar}" maxlength="255" />
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_avatar" data-type="image" data-path="{RESOURCE_AVATAR_PATH}" data-currentpath="{RESOURCE_AVATAR_CURRPATH}">{GLANG.browse_image}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="resource_cover" class="control-label col-sm-8">{LANG.resource_cover} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_cover_artist_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group ms-form-group-last">
                    <div class="col-sm-offset-8 col-sm-16 col-md-10 col-lg-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show_inhome" id="show_inhome" value="1"{DATA.show_inhome}/>
                                {LANG.show_inhome}.
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_by_lang} <strong>{LANG_DATA_NAME}</strong>:</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="artist_name" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.artist_name}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control required" type="text" name="artist_name" id="artist_name" value="{DATA.artist_name}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="artist_alias" class="control-label col-sm-8">{LANG.alias}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="artist_alias" id="artist_alias" value="{DATA.artist_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="artist_realname" class="control-label col-sm-8">{LANG.artist_realname}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="artist_realname" id="artist_realname" value="{DATA.artist_realname}" maxlength="255" />
                    </div>
                </div>
                <div class="form-group ms-form-group-last">
                    <label for="artist_hometown" class="control-label col-sm-8">{LANG.artist_hometown}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="artist_hometown" id="artist_hometown" value="{DATA.artist_hometown}" maxlength="255" />
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-offset-8 col-sm-16">
                        <h3><strong>{LANG.artist_info_as_singer}</strong></h3>
                    </div>
                </div>
                <div class="form-group">
                    <label for="singer_nickname" class="control-label col-sm-8">{LANG.artist_nickname}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="singer_nickname" id="singer_nickname" value="{DATA.singer_nickname}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="singer_prize" class="control-label col-sm-8">{LANG.prize}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="singer_prize" id="singer_prize" rows="3">{DATA.singer_prize}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="singer_introtext" class="control-label col-sm-8">{LANG.introtext}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="singer_introtext" id="singer_introtext" rows="3">{DATA.singer_introtext}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="singer_keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="singer_keywords" id="singer_keywords" value="{DATA.singer_keywords}"/>
                    </div>
                </div>
                <div class="form-group ms-form-group-last">
                    <div class="col-xs-24">
                        <div class="ckeditor">
                            <label class="control-label">{LANG.bodytext}:</label>
                            <div class="clearfix">
                                {DATA.singer_info}
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-offset-8 col-sm-16">
                        <h3><strong>{LANG.artist_info_as_author}</strong></h3>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author_nickname" class="control-label col-sm-8">{LANG.artist_nickname}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="author_nickname" id="author_nickname" value="{DATA.author_nickname}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="author_prize" class="control-label col-sm-8">{LANG.prize}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="author_prize" id="author_prize" rows="3">{DATA.author_prize}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author_introtext" class="control-label col-sm-8">{LANG.introtext}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="author_introtext" id="author_introtext" rows="3">{DATA.author_introtext}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author_keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="author_keywords" id="author_keywords" value="{DATA.author_keywords}"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-24">
                        <div class="ckeditor">
                            <label class="control-label">{LANG.bodytext}:</label>
                            <div class="clearfix">
                                {DATA.author_info}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
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
