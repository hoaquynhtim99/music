<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>

<form id="msAjForm" method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="cat_ids" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.cat}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="select2 required">
                            <select class="form-control" name="cat_ids[]" id="cat_ids" multiple="multiple">
                                <!-- BEGIN: cat -->
                                <option value="{CAT.cat_id}"{CAT.selected}>{CAT.cat_name}</option>
                                <!-- END: cat -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.artist_type_singer}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="hiddeninputlist required clearfix">
                            <div class="btn-group pull-left">
                                <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="singer" data-title="{LANG.select_singer}" data-list="#PickedArtistsList" data-inputname="singer_ids[]">{LANG.select}</button>
                                <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{LINK_ADD_ARTIST_SINGER}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
                                    <!-- BEGIN: choose_last_singers -->
                                    <li><a href="#" data-toggle="PickArtistFromLastTime" data-source="#LastPickedSingers" data-target="#PickedArtistsList"><i class="fa fa-history" aria-hidden="true"></i> {LANG.choose_from_last_time}</a></li>
                                    <!-- END: choose_last_singers -->
                                </ul>
                            </div>
                            <ul class="ms-content-picked-lists" id="PickedArtistsList">
                                <!-- BEGIN: singer -->
                                <li>
                                    <input type="hidden" name="singer_ids[]" value="{SINGER.artist_id}">
                                    <a class="delitem" href="#" data-toggle="delPickedArtist"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                                    <strong class="val ms-ellipsis">{SINGER.artist_name}</strong>
                                </li>
                                <!-- END: singer -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-8">{LANG.artist_type_author}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="btn-group pull-left">
                            <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="author" data-title="{LANG.select_author}" data-list="#PickedArtistsListAuthor" data-inputname="author_ids[]">{LANG.select}</button>
                            <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="{LINK_ADD_ARTIST_AUTHOR}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
                                <!-- BEGIN: choose_last_author -->
                                <li><a href="#" data-toggle="PickArtistFromLastTime" data-source="#LastPickedAuthors" data-target="#PickedArtistsListAuthor"><i class="fa fa-history" aria-hidden="true"></i> {LANG.choose_from_last_time}</a></li>
                                <!-- END: choose_last_author -->
                            </ul>
                        </div>
                        <ul class="ms-content-picked-lists" id="PickedArtistsListAuthor">
                            <!-- BEGIN: author -->
                            <li>
                                <input type="hidden" name="author_ids[]" value="{AUTHOR.artist_id}">
                                <a class="delitem" href="#" data-toggle="delPickedArtist"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                                <strong class="val ms-ellipsis">{AUTHOR.artist_name}</strong>
                            </li>
                            <!-- END: author -->
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-8">{LANG.song_video_id}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <button tabindex="-1" type="button" class="btn btn-success pull-left" data-toggle="modalPickVideos" data-multiple="false" data-title="{LANG.select_video}" data-list="#PickedVideo" data-inputname="video_id">{LANG.select}</button>
                        <ul class="ms-content-picked-lists" id="PickedVideo">
                            <!-- BEGIN: video -->
                            <li>
                                <input type="hidden" name="video_id" value="{VIDEO.video_id}">
                                <a class="delitem" href="#" data-toggle="delPickedVideo"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                                <strong class="val ms-ellipsis">{VIDEO.video_name}</strong>
                                <small class="sval ms-ellipsis">{VIDEO_SINGER}</small>
                            </li>
                            <!-- END: video -->
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="resource_avatar" class="control-label col-sm-8">{LANG.resource_avatar} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_song_note}"><i class="fa fa-info-circle"></i></a>:</label>
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
                    <label for="resource_cover" class="control-label col-sm-8">{LANG.resource_cover}:</label>
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
                    <label for="song_name" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.song_name}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control required" type="text" name="song_name" id="song_name" value="{DATA.song_name}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="song_alias" class="control-label col-sm-8">{LANG.alias}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="song_alias" id="song_alias" value="{DATA.song_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="song_introtext" class="control-label col-sm-8">{LANG.introtext}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="song_introtext" id="song_introtext" rows="3">{DATA.song_introtext}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="song_keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="song_keywords" id="song_keywords" value="{DATA.song_keywords}"/>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-file-audio-o" aria-hidden="true"></i> {LANG.song_files}:</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- BEGIN: soquality -->
                <div class="form-group">
                    <label for="resource_path_{SOQUALITY.quality_id}" class="control-label col-sm-8">{SOQUALITY.quality_name}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_path[{SOQUALITY.quality_id}]" id="resource_path_{SOQUALITY.quality_id}" value="{RESOURCE_PATH}" maxlength="255">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_path_{SOQUALITY.quality_id}" data-type="file" data-path="{RESOURCE_DATA_PATH}" data-currentpath="{RESOURCE_DATA_CURRPATH}">{GLANG.browse_file}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- END: soquality -->
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <a class="d-block" role="button" data-toggle="collapse" href="#collapseSongAdv" aria-expanded="{SHOW_ADV_ACTIVE}" aria-controls="collapseSongAdv">{LANG.adv}</a>
                </div>
            </div>
        </div>
        <div class="collapse{SHOW_ADV_CLASS}" id="collapseSongAdv">
            <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_files} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label for="caption_file" class="control-label col-sm-8">{LANG.mana_cc_webvtt}:</label>
                        <div class="col-sm-16 col-md-10 col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="caption_file" name="caption_file" value="{DATA.caption_file}">
                                <div class="input-group-btn">
                                    <button class="btn btn-success" type="button" data-toggle="browse" data-area="caption_file" data-type="file" data-path="{RESOURCE_CAPTION_PATH}" data-currentpath="{RESOURCE_CAPTION_CURRPATH}">{GLANG.browse_file}</button>
                                </div>
                            </div>
                            <i class="form-text text-muted">{LANG.mana_cc_webvtt_help}.</i>
                        </div>
                    </div>
                    <div class="row">
                        <label for="caption_pdf" class="control-label col-sm-8">{LANG.mana_cc_pdf}:</label>
                        <div class="col-sm-16 col-md-10 col-lg-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="caption_pdf" name="caption_pdf" value="{DATA.caption_pdf}">
                                <div class="input-group-btn">
                                    <button class="btn btn-success" type="button" data-toggle="browse" data-area="caption_pdf" data-type="file" data-path="{RESOURCE_CAPTION_PATH}" data-currentpath="{RESOURCE_CAPTION_CURRPATH}">{GLANG.browse_file}</button>
                                </div>
                            </div>
                            <i class="form-text text-muted">{LANG.mana_cc_pdf_help}.</i>
                        </div>
                    </div>
                </div>
            </div>
            <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_text} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <div>
                            <div class="ckeditor">
                                {DATA.caption_data}
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
                <input type="hidden" name="show_adv" value="{SHOW_ADV}"/>
            </div>
        </div>
    </div>
</form>

{FILE "pick-artists.tpl"}
{FILE "pick-videos.tpl"}

<script type="text/javascript">
$(function() {
    $("#cat_ids").select2();
    $('#msBtnSubmit').click(function() {
        $('[name="submitcontinue"]').val(0);
    });
    $('#msBtnSubmitCon').click(function() {
        $('[name="submitcontinue"]').val(1);
        $('#msAjForm').submit();
    });
    $('#collapseSongAdv').on('hide.bs.collapse', function () {
        $('[name="show_adv"]').val('0');
    });
    $('#collapseSongAdv').on('show.bs.collapse', function () {
        $('[name="show_adv"]').val('1');
    });
});
</script>
<!-- END: main -->
