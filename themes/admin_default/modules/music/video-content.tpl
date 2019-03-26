<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>

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
                            <select class="form-control" name="cat_ids" id="cat_ids" multiple="multiple">
                                <!-- BEGIN: cat -->
                                <option value="{CAT.cat_id}"{CAT.selected}>{CAT.cat_name}</option>
                                <!-- END: cat -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="artist_birthday" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.artist_type_singer}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="hiddeninputlist required">
                            <div class="btn-group pull-left">
                                <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="singer" data-title="{LANG.select_singer}" data-list="#PickedArtistsList" data-inputname="singer_ids[]">{LANG.select}</button>
                                <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{LINK_ADD_ARTIST_SINGER}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
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
                    <label for="artist_birthday_lev" class="control-label col-sm-8">{LANG.artist_type_author}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="btn-group pull-left">
                            <button tabindex="-1" type="button" class="btn btn-success" data-toggle="modalPickArtists" data-mode="author" data-title="{LANG.select_author}" data-list="#PickedArtistsListAuthor" data-inputname="author_ids[]">{LANG.select}</button>
                            <button tabindex="-1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="{LINK_ADD_ARTIST_AUTHOR}" target="_blank"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_new}</a></li>
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
                    <label for="artist_birthday_lev" class="control-label col-sm-8">{LANG.video_song_id}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <button tabindex="-1" type="button" class="btn btn-success pull-left" data-toggle="modalPickSongs" data-multiple="false" data-title="{LANG.select_song}" data-list="#PickedSong" data-inputname="song_id">{LANG.select}</button>
                        <ul class="ms-content-picked-lists" id="PickedSong">
                            <!-- BEGIN: song -->
                            <li>
                                <input type="hidden" name="song_id" value="{SONG.song_id}">
                                <a class="delitem" href="#" data-toggle="delPickedSong"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                                <strong class="val ms-ellipsis">{SONG.song_name}</strong>
                                <small class="sval ms-ellipsis">{SONG_SINGER}</small>
                            </li>
                            <!-- END: song -->
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="resource_avatar" class="control-label col-sm-8">{LANG.resource_avatar} <a href="javascript:void(0);" data-toggle="tooltip" data-title="{LANG.resource_video_note}"><i class="fa fa-info-circle"></i></a>:</label>
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
                    <label for="video_name" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.video_name}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control required" type="text" name="video_name" id="video_name" value="{DATA.video_name}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="video_alias" class="control-label col-sm-8">{LANG.alias}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="video_alias" id="video_alias" value="{DATA.video_alias}" maxlength="250" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="video_introtext" class="control-label col-sm-8">{LANG.introtext}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <textarea class="form-control" name="video_introtext" id="video_introtext" rows="3">{DATA.video_introtext}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="video_keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <input class="form-control" type="text" name="video_keywords" id="video_keywords" value="{DATA.video_keywords}"/>
                    </div>
                </div>
            </div>
        </div>
        <h2><i class="fa fa-file-video-o" aria-hidden="true"></i> {LANG.video_files}:</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- BEGIN: mvquality -->
                <div class="form-group">
                    <label for="resource_avatar" class="control-label col-sm-8">{MVQUALITY.quality_name}:</label>
                    <div class="col-sm-16 col-md-10 col-lg-8">
                        <div class="input-group">
                            <input class="form-control" type="text" name="resource_path[{MVQUALITY.quality_id}]" id="resource_path_{MVQUALITY.quality_id}" value="" maxlength="255">
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" data-toggle="browse" data-area="resource_path_{MVQUALITY.quality_id}" data-type="file" data-path="{RESOURCE_DATA_PATH}" data-currentpath="{RESOURCE_DATA_CURRPATH}">{GLANG.browse_file}</button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- END: mvquality -->
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
                <input type="hidden" name="submit" value="1"/>
                <input name="redirect" type="hidden" value="0" />
                <input name="submitcontinue" type="hidden" value="0" />
                <input id="msBtnSubmit" type="submit" value="{GLANG.save}" class="btn btn-primary"/>
                <!-- BEGIN: save_continue --><input id="msBtnSubmitCon" type="button" class="btn btn-success" value="{LANG.save_and_continue}"/><!-- END: save_continue -->
            </div>
        </div>
    </div>
</form>

<div class="modal modalPickItems" tabindex="-1" role="dialog" id="modalPickArtists" data-backdrop="static" data-changed="false" data-list="" data-inputname="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" name="q" value="" class="form-control" placeholder="{LANG.enter_keyword}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="nation_id" class="form-control">
                                <option value="0">{LANG.search_all_nation}</option>
                                <!-- BEGIN: nation -->
                                <option value="{NATION.nation_id}">{NATION.nation_name}</option>
                                <!-- END: nation -->
                            </select>
                        </div>
                        <input type="hidden" name="artist_id_selected" value="">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="mode" value="">
                        <button type="button" name="submit" class="btn btn-primary" data-allowedpage="false"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{GLANG.search}</button>
                    </div>
                </div>
                <div class="item-lists"></div>
                <h2>{LANG.picked_list}</h2>
                <p class="text-info"><i>{LANG.drag_and_drop_to_sort}</i></p>
                <ul class="item-selected"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="completePickArtist">{LANG.save}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modalPickItems" tabindex="-1" role="dialog" id="modalPickSongs" data-backdrop="static" data-changed="false" data-list="" data-inputname="" data-multiple="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" name="q" value="" class="form-control" placeholder="{LANG.enter_keyword}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="cat_id" class="form-control">
                                <option value="0">{LANG.search_all_cat}</option>
                                <!-- BEGIN: cat1 -->
                                <option value="{CAT.cat_id}">{CAT.cat_name}</option>
                                <!-- END: cat1 -->
                            </select>
                        </div>
                        <input type="hidden" name="song_id_selected" value="">
                        <input type="hidden" name="page" value="1">
                        <button type="button" name="submit" class="btn btn-primary" data-allowedpage="false"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{GLANG.search}</button>
                    </div>
                </div>
                <div class="item-lists"></div>
                <h2>{LANG.picked_list}</h2>
                <p class="text-info"><i>{LANG.drag_and_drop_to_sort}</i></p>
                <ul class="item-selected"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="completePickSong">{LANG.save}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.close}</button>
            </div>
        </div>
    </div>
</div>

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
