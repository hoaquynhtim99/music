<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
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
                            <select class="form-select" name="cat_ids[]" id="cat_ids" multiple="multiple">
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
                <div class="row mb-3">
                    <label for="release_year" class="col-form-label text-sm-end col-sm-4">{LANG.album_release_year}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="number" name="release_year" id="release_year" value="{DATA.release_year}" maxlength="4">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="resource_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.resource_avatar} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_album_note}"><i class="fa fa-info-circle"></i></a>:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_avatar" id="resource_avatar" value="{DATA.resource_avatar}" maxlength="255" />
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_avatar" data-type="image" data-path="{RESOURCE_AVATAR_PATH}" data-currentpath="{RESOURCE_AVATAR_CURRPATH}">{GLANG.browse_image}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="resource_cover" class="col-form-label text-sm-end col-sm-4">{LANG.resource_cover}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_cover" id="resource_cover" value="{DATA.resource_cover}" maxlength="255" />
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_cover" data-type="image" data-path="{RESOURCE_COVER_PATH}" data-currentpath="{RESOURCE_COVER_CURRPATH}">{GLANG.browse_image}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 ms-row mb-3-last">
                    <div class="col-sm-offset-8 col-sm-8 col-md-5 col-lg-4">
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
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label for="album_name" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.album_name}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control required" type="text" name="album_name" id="album_name" value="{DATA.album_name}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="album_alias" class="col-form-label text-sm-end col-sm-4">{LANG.alias}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="album_alias" id="album_alias" value="{DATA.album_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="album_introtext" class="col-form-label text-sm-end col-sm-4">{LANG.introtext}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <textarea class="form-control" name="album_introtext" id="album_introtext" rows="3">{DATA.album_introtext}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="album_keywords" class="col-form-label text-sm-end col-sm-4">{LANG.keywords}:</label>
                    <div class="col-sm-8 col-md-5 col-lg-4">
                        <input class="form-control" type="text" name="album_keywords" id="album_keywords" value="{DATA.album_keywords}"/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="ckeditor">
                            <label class="col-form-label text-sm-end">{LANG.album_description}:</label>
                            <div class="clearfix">
                                {DATA.album_description}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-fw fa-music" aria-hidden="true"></i>{LANG.album_songs}:</h2>
        <div class="card">
            <div class="card-body">
                <label class="col-form-label text-sm-end col-sm-4 pt-0">
                    <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickSongs" data-multiple="true" data-title="{LANG.select_song}" data-list="#PickedSong" data-inputname="song_ids[]">{LANG.select}</button>
                </label>
                <div class="col-sm-8 col-md-5 col-lg-4">
                    <ul class="ms-content-picked-lists pl-0" id="PickedSong">
                        <!-- BEGIN: song -->
                        <li>
                            <input type="hidden" name="song_ids[]" value="{SONG.song_id}">
                            <a class="delitem" href="#" data-toggle="delPickedSong"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                            <strong class="val ms-ellipsis">{SONG.song_name}</strong>
                            <small class="sval ms-ellipsis">{SONG_SINGER}</small>
                        </li>
                        <!-- END: song -->
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-offset-8 col-sm-8">
                <input type="hidden" name="submitform" value="1"/>
                <input name="redirect" type="hidden" value="0" />
                <input name="submitcontinue" type="hidden" value="0" />
                <input id="msBtnSubmit" type="submit" value="{GLANG.save}" class="btn btn-primary"/>
                <!-- BEGIN: save_continue --><input id="msBtnSubmitCon" type="button" class="btn btn-success" value="{LANG.save_and_continue}"/><!-- END: save_continue -->
            </div>
        </div>
    </div>
</form>

{FILE "pick-artists.tpl"}
{FILE "pick-songs.tpl"}

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
});
</script>
<!-- END: main -->
