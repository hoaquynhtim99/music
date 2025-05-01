<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>

<form id="msAjForm" method="post" action="{FORM_ACTION}" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <h2><i class="fa fa-fw fa-info-circle"></i>{LANG.info_all}:</h2>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="cat_ids" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.cat}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="select2 required">
                            <select class="form-select" name="cat_ids[]" id="cat_ids" multiple="multiple" size="1">
                                <!-- BEGIN: cat -->
                                <option value="{CAT.cat_id}"{CAT.selected}>{CAT.cat_name}</option>
                                <!-- END: cat -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.artist_type_singer}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="hiddeninputlist required clearfix">
                            <div class="btn-group float-start">
                                <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="singer" data-title="{LANG.select_singer}" data-list="#PickedArtistsList" data-inputname="singer_ids[]">{LANG.select}</button>
                                <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{LINK_ADD_ARTIST_SINGER}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
                                    <!-- BEGIN: choose_last_singers -->
                                    <li><a class="dropdown-item" href="#" data-toggle="PickArtistFromLastTime" data-source="#LastPickedSingers" data-target="#PickedArtistsList"><i class="fa fa-history" aria-hidden="true"></i> {LANG.choose_from_last_time}</a></li>
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
                <div class="row mb-3">
                    <label class="col-form-label text-sm-end col-sm-4">{LANG.artist_type_author}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="btn-group float-start">
                            <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="author" data-title="{LANG.select_author}" data-list="#PickedArtistsListAuthor" data-inputname="author_ids[]">{LANG.select}</button>
                            <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{LINK_ADD_ARTIST_AUTHOR}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
                                <!-- BEGIN: choose_last_author -->
                                <li><a class="dropdown-item" href="#" data-toggle="PickArtistFromLastTime" data-source="#LastPickedAuthors" data-target="#PickedArtistsListAuthor"><i class="fa fa-history" aria-hidden="true"></i> {LANG.choose_from_last_time}</a></li>
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
                <div class="row mb-3">
                    <label class="col-form-label text-sm-end col-sm-4">{LANG.song_video_id}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <button tabindex="-1" type="button" class="btn btn-success float-start" data-toggle="modalPickVideos" data-multiple="false" data-title="{LANG.select_video}" data-list="#PickedVideo" data-inputname="video_id">{LANG.select}</button>
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
                <div class="row mb-3">
                    <label for="resource_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.resource_avatar} <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-title="{LANG.resource_song_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_avatar" id="resource_avatar" value="{DATA.resource_avatar}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_avatar" data-type="image" data-path="{RESOURCE_AVATAR_PATH}" data-currentpath="{RESOURCE_AVATAR_CURRPATH}">{GLANG.browse_image}</button>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="resource_cover" class="col-form-label text-sm-end col-sm-4">{LANG.resource_cover}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
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
                    <label for="song_name" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.song_name}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control required" type="text" name="song_name" id="song_name" value="{DATA.song_name}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="song_alias" class="col-form-label text-sm-end col-sm-4">{LANG.alias}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="song_alias" id="song_alias" value="{DATA.song_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="song_introtext" class="col-form-label text-sm-end col-sm-4">{LANG.introtext}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="song_introtext" id="song_introtext" rows="3">{DATA.song_introtext}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="song_keywords" class="col-form-label text-sm-end col-sm-4">{LANG.keywords}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="song_keywords" id="song_keywords" value="{DATA.song_keywords}"/>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-file-audio-o" aria-hidden="true"></i> {LANG.song_files}:</h2>
        <div class="card">
            <div class="card-body">
                <!-- BEGIN: soquality -->
                <div class="row mb-3">
                    <label for="resource_path_{SOQUALITY.quality_id}" class="col-form-label text-sm-end col-sm-4">{SOQUALITY.quality_name}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_path[{SOQUALITY.quality_id}]" id="resource_path_{SOQUALITY.quality_id}" value="{RESOURCE_PATH}" maxlength="255">
                            <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="resource_path_{SOQUALITY.quality_id}" data-type="file" data-path="{RESOURCE_DATA_PATH}" data-currentpath="{RESOURCE_DATA_CURRPATH}">{GLANG.browse_file}</button>
                        </div>
                    </div>
                </div>
                <!-- END: soquality -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="fs-5 fw-medium">
                    <a class="d-block" role="button" data-toggle="collapse" href="#collapseSongAdv" aria-expanded="{SHOW_ADV_ACTIVE}" aria-controls="collapseSongAdv">{LANG.adv}</a>
                </div>
            </div>
        </div>
        <div class="collapse{SHOW_ADV_CLASS}" id="collapseSongAdv">
            <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_files} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="caption_file" class="col-form-label text-sm-end col-sm-4">{LANG.mana_cc_webvtt}:</label>
                        <div class="col-sm-8 col-md-5 col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="caption_file" name="caption_file" value="{DATA.caption_file}">
                                <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="caption_file" data-type="file" data-path="{RESOURCE_CAPTION_PATH}" data-currentpath="{RESOURCE_CAPTION_CURRPATH}">{GLANG.browse_file}</button>
                            </div>
                            <i class="form-text text-muted">{LANG.mana_cc_webvtt_help}.</i>
                        </div>
                    </div>
                    <div class="row">
                        <label for="caption_pdf" class="col-form-label text-sm-end col-sm-4">{LANG.mana_cc_pdf}:</label>
                        <div class="col-sm-8 col-md-5 col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="caption_pdf" name="caption_pdf" value="{DATA.caption_pdf}">
                                <button class="btn btn-success" type="button" data-toggle="selectfile" data-target="caption_pdf" data-type="file" data-path="{RESOURCE_CAPTION_PATH}" data-currentpath="{RESOURCE_CAPTION_CURRPATH}">{GLANG.browse_file}</button>
                            </div>
                            <i class="form-text text-muted">{LANG.mana_cc_pdf_help}.</i>
                        </div>
                    </div>
                </div>
            </div>
            <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_text} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
            <div class="card">
                <div class="card-body">
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
        <div class="row mb-3">
            <div class="offset-sm-4 col-sm-8">
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
