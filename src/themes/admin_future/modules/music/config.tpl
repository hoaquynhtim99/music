<!-- BEGIN: main -->
<div class="alert alert-info" role="alert">{CONFIG_NOTE}.</div>
<div class="mb-3">
    <div class="btn-group d-flex">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {LANG.config_quick_select} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-fullwidth">
            <li><a class="dropdown-item" href="#config_display" data-toggle="msscrollto">{LANG.config_display}</a></li>
            <li><a class="dropdown-item" href="#config_view_singer" data-toggle="msscrollto">{LANG.config_view_singer}</a></li>
            <li><a class="dropdown-item" href="#config_urls_system" data-toggle="msscrollto">{LANG.config_urls_system}</a></li>
            <li><a class="dropdown-item" href="#config_others" data-toggle="msscrollto">{LANG.config_others}</a></li>
            <li><a class="dropdown-item" href="#config_mainpage" data-toggle="msscrollto">{LANG.config_mainpage}</a></li>
            <li><a class="dropdown-item" href="#config_list_albums" data-toggle="msscrollto">{LANG.config_list_albums}</a></li>
            <li><a class="dropdown-item" href="#config_list_videos" data-toggle="msscrollto">{LANG.config_list_videos}</a></li>
            <li><a class="dropdown-item" href="#config_structre_data_page_title" data-toggle="msscrollto">{LANG.config_structre_data_page_title}</a></li>
        </ul>
    </div>
</div>
<form action="{FORM_ACTION}" method="post" role="form" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="row g-3">
            <div class="col-md-6 col-lg-6 vstack gap-3">
                <div class="card" id="config_display">
                    <div class="card-header"><strong>{LANG.config_display}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="limit_singers_displayed" class="col-form-label text-sm-end col-sm-4">{LANG.limit_singers_displayed}:</label>
                            <div class="col-sm-8">
                                <select name="limit_singers_displayed" id="limit_singers_displayed" class="form-select">
                                    <!-- BEGIN: limit_singers_displayed -->
                                    <option value="{LIMIT_SINGERS_DISPLAYED.key}"{LIMIT_SINGERS_DISPLAYED.selected}>{LIMIT_SINGERS_DISPLAYED.title}</option>
                                    <!-- END: limit_singers_displayed -->
                                </select>
                                <i class="form-text">{LANG.limit_singers_displayed_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="various_artists" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.various_artists}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="various_artists" id="various_artists" value="{DATA.various_artists}"/>
                                <i class="form-text">{LANG.various_artists_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="unknow_singer" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.unknow_singer}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="unknow_singer" id="unknow_singer" value="{DATA.unknow_singer}"/>
                                <i class="form-text">{LANG.unknow_singer_help}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_view_singer">
                    <div class="card-header"><strong>{LANG.config_view_singer}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="offset-sm-4 col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="view_singer_show_header" id="view_singer_show_header" value="1"{DATA.view_singer_show_header} />
                                    <label class="form-check-label" for="view_singer_show_header">{LANG.view_singer_show_header}</label>
                                </div>
                                <i class="form-text">{LANG.view_singer_show_header_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_headtext_length" class="col-form-label text-sm-end col-sm-4">{LANG.view_singer_headtext_length}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_headtext_length" id="view_singer_headtext_length" value="{DATA.view_singer_headtext_length}" data-pattern="^[0-9]+$" data-mess="{LANG.validate_number_min0}"/>
                                <i class="form-text">{LANG.view_singer_headtext_length_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_view_singer_tabs_alias_song" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_view_singer_tabs_alias_song}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_view_singer_tabs_alias_song" id="arr_view_singer_tabs_alias_song" value="{DATA.arr_view_singer_tabs_alias_song}" data-pattern="^[a-z\-]{1,30}$" data-mess="{LANG.validate_alias_lowercase_min1max30}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_view_singer_tabs_alias_video" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_view_singer_tabs_alias_video}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_view_singer_tabs_alias_video" id="arr_view_singer_tabs_alias_video" value="{DATA.arr_view_singer_tabs_alias_video}" data-pattern="^[a-z\-]{1,30}$" data-mess="{LANG.validate_alias_lowercase_min1max30}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_view_singer_tabs_alias_album" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_view_singer_tabs_alias_album}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_view_singer_tabs_alias_album" id="arr_view_singer_tabs_alias_album" value="{DATA.arr_view_singer_tabs_alias_album}" data-pattern="^[a-z\-]{1,30}$" data-mess="{LANG.validate_alias_lowercase_min1max30}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_view_singer_tabs_alias_profile" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_view_singer_tabs_alias_profile}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_view_singer_tabs_alias_profile" id="arr_view_singer_tabs_alias_profile" value="{DATA.arr_view_singer_tabs_alias_profile}" data-pattern="^[a-z\-]{1,30}$" data-mess="{LANG.validate_alias_lowercase_min1max30}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_main_num_songs" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_main_num_songs}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_main_num_songs" id="view_singer_main_num_songs" value="{DATA.view_singer_main_num_songs}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_main_num_videos" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_main_num_videos}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_main_num_videos" id="view_singer_main_num_videos" value="{DATA.view_singer_main_num_videos}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_main_num_albums" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_main_num_albums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_main_num_albums" id="view_singer_main_num_albums" value="{DATA.view_singer_main_num_albums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_detail_num_songs" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_detail_num_songs}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_detail_num_songs" id="view_singer_detail_num_songs" value="{DATA.view_singer_detail_num_songs}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_detail_num_videos" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_detail_num_videos}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_detail_num_videos" id="view_singer_detail_num_videos" value="{DATA.view_singer_detail_num_videos}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="view_singer_detail_num_albums" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.view_singer_detail_num_albums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="view_singer_detail_num_albums" id="view_singer_detail_num_albums" value="{DATA.view_singer_detail_num_albums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_urls_system">
                    <div class="card-header"><strong>{LANG.config_urls_system}</strong></div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;{LANG.config_alert_change}</div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_singer" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_singer}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_singer" id="arr_code_prefix_singer" value="{DATA.arr_code_prefix_singer}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_playlist" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_playlist}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_playlist" id="arr_code_prefix_playlist" value="{DATA.arr_code_prefix_playlist}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_album" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_album}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_album" id="arr_code_prefix_album" value="{DATA.arr_code_prefix_album}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_video" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_video}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_video" id="arr_code_prefix_video" value="{DATA.arr_code_prefix_video}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_cat" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_cat}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_cat" id="arr_code_prefix_cat" value="{DATA.arr_code_prefix_cat}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_code_prefix_song" class="col-form-label text-sm-end col-sm-4"><i class="fa fa-asterisk"></i> {LANG.arr_code_prefix_song}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="arr_code_prefix_song" id="arr_code_prefix_song" value="{DATA.arr_code_prefix_song}" data-pattern="^[a-z]{OPEN_BRACKET}2{CLOSE_BRACKET}$" data-mess="{LANG.validate_alias_lowercase_len2}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_op_alias_prefix_song" class="col-form-label text-sm-end col-sm-4">{LANG.arr_op_alias_prefix_song}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_op_alias_prefix_song" id="arr_op_alias_prefix_song" value="{DATA.arr_op_alias_prefix_song}" data-pattern="(^(?!\-)[a-z\-]+$|^$)" data-mess="{LANG.validate_alias_lowercase_max50}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_op_alias_prefix_album" class="col-form-label text-sm-end col-sm-4">{LANG.arr_op_alias_prefix_album}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_op_alias_prefix_album" id="arr_op_alias_prefix_album" value="{DATA.arr_op_alias_prefix_album}" data-pattern="(^(?!\-)[a-z\-]+$|^$)" data-mess="{LANG.validate_alias_lowercase_max50}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_op_alias_prefix_video" class="col-form-label text-sm-end col-sm-4">{LANG.arr_op_alias_prefix_video}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_op_alias_prefix_video" id="arr_op_alias_prefix_video" value="{DATA.arr_op_alias_prefix_video}" data-pattern="(^(?!\-)[a-z\-]+$|^$)" data-mess="{LANG.validate_alias_lowercase_max50}"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_op_alias_prefix_playlist" class="col-form-label text-sm-end col-sm-4">{LANG.arr_op_alias_prefix_playlist}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_op_alias_prefix_playlist" id="arr_op_alias_prefix_playlist" value="{DATA.arr_op_alias_prefix_playlist}" data-pattern="(^(?!\-)[a-z\-]+$|^$)" data-mess="{LANG.validate_alias_lowercase_max50}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_others">
                    <div class="card-header"><strong>{LANG.config_others}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="fb_share_image" class="col-form-label text-sm-end col-sm-4">{LANG.fb_share_image}:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="fb_share_image" id="fb_share_image" value="{DATA.fb_share_image}" readonly="readonly"/>
                                    <button class="btn btn-secondary" type="button" title="{LANG.view_image}" data-toggle="msviewimg" data-target="#fb_share_image" data-title="{LANG.image}"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-secondary" type="button" title="{LANG.delete_value}" data-toggle="msclearval" data-target="#fb_share_image"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <button class="btn btn-success" type="button" data-toggle="msbrserver" data-area="fb_share_image" data-alt="" data-path="{UPLOAD_DIR}" data-currentpath="{UPLOAD_DIR}" data-type="image">{LANG.browse_server}</button>
                                </div>
                                <i class="form-text">{LANG.fb_share_image_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="res_default_album_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.res_default_album_avatar}:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="res_default_album_avatar" id="res_default_album_avatar" value="{DATA.res_default_album_avatar}" readonly="readonly"/>
                                    <button class="btn btn-secondary" type="button" title="{LANG.view_image}" data-toggle="msviewimg" data-target="#res_default_album_avatar" data-title="{LANG.image}"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-secondary" type="button" title="{LANG.delete_value}" data-toggle="msclearval" data-target="#res_default_album_avatar"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <button class="btn btn-success" type="button" data-toggle="msbrserver" data-area="res_default_album_avatar" data-alt="" data-path="{UPLOAD_DIR}/albums" data-currentpath="{UPLOAD_DIR}/albums" data-type="image">{LANG.browse_server}</button>
                                </div>
                                <i class="form-text">{LANG.res_default_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="res_default_singer_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.res_default_singer_avatar}:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="res_default_singer_avatar" id="res_default_singer_avatar" value="{DATA.res_default_singer_avatar}" readonly="readonly"/>
                                    <button class="btn btn-secondary" type="button" title="{LANG.view_image}" data-toggle="msviewimg" data-target="#res_default_singer_avatar" data-title="{LANG.image}"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-secondary" type="button" title="{LANG.delete_value}" data-toggle="msclearval" data-target="#res_default_singer_avatar"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <button class="btn btn-success" type="button" data-toggle="msbrserver" data-area="res_default_singer_avatar" data-alt="" data-path="{UPLOAD_DIR}/artists" data-currentpath="{UPLOAD_DIR}/artists" data-type="image">{LANG.browse_server}</button>
                                </div>
                                <i class="form-text">{LANG.res_default_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="res_default_author_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.res_default_author_avatar}:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="res_default_author_avatar" id="res_default_author_avatar" value="{DATA.res_default_author_avatar}" readonly="readonly"/>
                                    <button class="btn btn-secondary" type="button" title="{LANG.view_image}" data-toggle="msviewimg" data-target="#res_default_author_avatar" data-title="{LANG.image}"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-secondary" type="button" title="{LANG.delete_value}" data-toggle="msclearval" data-target="#res_default_author_avatar"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <button class="btn btn-success" type="button" data-toggle="msbrserver" data-area="res_default_author_avatar" data-alt="" data-path="{UPLOAD_DIR}/artists" data-currentpath="{UPLOAD_DIR}/artists" data-type="image">{LANG.browse_server}</button>
                                </div>
                                <i class="form-text">{LANG.res_default_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="res_default_video_avatar" class="col-form-label text-sm-end col-sm-4">{LANG.res_default_video_avatar}:</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="res_default_video_avatar" id="res_default_video_avatar" value="{DATA.res_default_video_avatar}" readonly="readonly"/>
                                    <button class="btn btn-secondary" type="button" title="{LANG.view_image}" data-toggle="msviewimg" data-target="#res_default_video_avatar" data-title="{LANG.image}"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    <button class="btn btn-secondary" type="button" title="{LANG.delete_value}" data-toggle="msclearval" data-target="#res_default_video_avatar"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <button class="btn btn-success" type="button" data-toggle="msbrserver" data-area="res_default_video_avatar" data-alt="" data-path="{UPLOAD_DIR}/videos" data-currentpath="{UPLOAD_DIR}/videos" data-type="image">{LANG.browse_server}</button>
                                </div>
                                <i class="form-text">{LANG.res_default_help}</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 vstack gap-3">
                <div class="card" id="config_mainpage">
                    <div class="card-header"><strong>{LANG.config_mainpage}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="offset-sm-4 col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="home_albums_display" id="home_albums_display" value="1"{DATA.home_albums_display} />
                                    <label class="form-check-label" for="home_albums_display">{LANG.home_albums_display}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="offset-sm-4 col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="home_singers_display" id="home_singers_display" value="1"{DATA.home_singers_display} />
                                    <label class="form-check-label" for="home_singers_display">{LANG.home_singers_display}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="offset-sm-4 col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="home_songs_display" id="home_songs_display" value="1"{DATA.home_songs_display} />
                                    <label class="form-check-label" for="home_songs_display">{LANG.home_songs_display}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="offset-sm-4 col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="home_videos_display" id="home_videos_display" value="1"{DATA.home_videos_display} />
                                    <label class="form-check-label" for="home_videos_display">{LANG.home_videos_display}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_albums_weight" class="col-form-label text-sm-end col-sm-4">{LANG.home_albums_weight}:</label>
                            <div class="col-sm-8">
                                <select name="home_albums_weight" id="home_albums_weight" class="form-select" data-toggle="mscfgmainweight" data-value="{DATA.home_albums_weight}">
                                    <!-- BEGIN: home_albums_weight -->
                                    <option value="{WEIGHT}"{HOME_ALBUMS_WEIGHT}>{WEIGHT}</option>
                                    <!-- END: home_albums_weight -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_singers_weight" class="col-form-label text-sm-end col-sm-4">{LANG.home_singers_weight}:</label>
                            <div class="col-sm-8">
                                <select name="home_singers_weight" id="home_singers_weight" class="form-select" data-toggle="mscfgmainweight" data-value="{DATA.home_singers_weight}">
                                    <!-- BEGIN: home_singers_weight -->
                                    <option value="{WEIGHT}"{HOME_SINGERS_WEIGHT}>{WEIGHT}</option>
                                    <!-- END: home_singers_weight -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_songs_weight" class="col-form-label text-sm-end col-sm-4">{LANG.home_songs_weight}:</label>
                            <div class="col-sm-8">
                                <select name="home_songs_weight" id="home_songs_weight" class="form-select" data-toggle="mscfgmainweight" data-value="{DATA.home_songs_weight}">
                                    <!-- BEGIN: home_songs_weight -->
                                    <option value="{WEIGHT}"{HOME_SONGS_WEIGHT}>{WEIGHT}</option>
                                    <!-- END: home_songs_weight -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_videos_weight" class="col-form-label text-sm-end col-sm-4">{LANG.home_videos_weight}:</label>
                            <div class="col-sm-8">
                                <select name="home_videos_weight" id="home_videos_weight" class="form-select" data-toggle="mscfgmainweight" data-value="{DATA.home_videos_weight}">
                                    <!-- BEGIN: home_videos_weight -->
                                    <option value="{WEIGHT}"{HOME_VIDEOS_WEIGHT}>{WEIGHT}</option>
                                    <!-- END: home_videos_weight -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_albums_nums" class="col-form-label text-sm-end col-sm-4">{LANG.home_albums_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="home_albums_nums" id="home_albums_nums" value="{DATA.home_albums_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.config_available_if_choose}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_singers_nums" class="col-form-label text-sm-end col-sm-4">{LANG.home_singers_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="home_singers_nums" id="home_singers_nums" value="{DATA.home_singers_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.config_available_if_choose}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_songs_nums" class="col-form-label text-sm-end col-sm-4">{LANG.home_songs_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="home_songs_nums" id="home_songs_nums" value="{DATA.home_songs_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.config_available_if_choose}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="home_videos_nums" class="col-form-label text-sm-end col-sm-4">{LANG.home_videos_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="home_videos_nums" id="home_videos_nums" value="{DATA.home_videos_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.config_available_if_choose}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_list_albums">
                    <div class="card-header"><strong>{LANG.config_list_albums}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="gird_albums_percat_nums" class="col-form-label text-sm-end col-sm-4">{LANG.gird_albums_percat_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="gird_albums_percat_nums" id="gird_albums_percat_nums" value="{DATA.gird_albums_percat_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.gird_albums_percat_nums_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gird_albums_incat_nums" class="col-form-label text-sm-end col-sm-4">{LANG.gird_albums_incat_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="gird_albums_incat_nums" id="gird_albums_incat_nums" value="{DATA.gird_albums_incat_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.gird_albums_incat_nums_help}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_list_videos">
                    <div class="card-header"><strong>{LANG.config_list_videos}</strong></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="gird_videos_percat_nums" class="col-form-label text-sm-end col-sm-4">{LANG.gird_videos_percat_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="gird_videos_percat_nums" id="gird_videos_percat_nums" value="{DATA.gird_videos_percat_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.gird_videos_percat_nums_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gird_videos_incat_nums" class="col-form-label text-sm-end col-sm-4">{LANG.gird_videos_incat_nums}:</label>
                            <div class="col-sm-8">
                                <input class="form-control required" type="text" name="gird_videos_incat_nums" id="gird_videos_incat_nums" value="{DATA.gird_videos_incat_nums}" data-pattern="^(?!0)[0-9]+$" data-mess="{LANG.validate_number_min1}"/>
                                <i class="form-text">{LANG.gird_videos_incat_nums_help}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="config_structre_data_page_title">
                    <div class="card-header"><strong>{LANG.config_structre_data_page_title}</strong></div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;{LANG.funcs_note}</div>
                        <strong>{LANG.funcs_album}:</strong>
                        <hr class="sm"/>
                        <div class="row mb-3">
                            <label for="arr_funcs_sitetitle_album" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_sitetitle}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_sitetitle_album" id="arr_funcs_sitetitle_album" value="{DATA.arr_funcs_sitetitle_album}"/>
                                <i class="form-text">{LANG.funcs_sitetitle_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_keywords_album" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_keywords}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_keywords_album" id="arr_funcs_keywords_album" value="{DATA.arr_funcs_keywords_album}"/>
                                <i class="form-text">{LANG.funcs_keywords_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_description_album" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_description}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_description_album" id="arr_funcs_description_album" value="{DATA.arr_funcs_description_album}"/>
                                <i class="form-text">{LANG.funcs_description_help}</i>
                            </div>
                        </div>
                        <strong>{LANG.funcs_video}:</strong>
                        <hr class="sm"/>
                        <div class="row mb-3">
                            <label for="arr_funcs_sitetitle_video" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_sitetitle}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_sitetitle_video" id="arr_funcs_sitetitle_video" value="{DATA.arr_funcs_sitetitle_video}"/>
                                <i class="form-text">{LANG.funcs_sitetitle_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_keywords_video" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_keywords}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_keywords_video" id="arr_funcs_keywords_video" value="{DATA.arr_funcs_keywords_video}"/>
                                <i class="form-text">{LANG.funcs_keywords_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_description_video" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_description}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_description_video" id="arr_funcs_description_video" value="{DATA.arr_funcs_description_video}"/>
                                <i class="form-text">{LANG.funcs_description_help}</i>
                            </div>
                        </div>
                        <strong>{LANG.funcs_singer}:</strong>
                        <hr class="sm"/>
                        <div class="row mb-3">
                            <label for="arr_funcs_sitetitle_singer" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_sitetitle}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_sitetitle_singer" id="arr_funcs_sitetitle_singer" value="{DATA.arr_funcs_sitetitle_singer}"/>
                                <i class="form-text">{LANG.funcs_sitetitle_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_keywords_singer" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_keywords}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_keywords_singer" id="arr_funcs_keywords_singer" value="{DATA.arr_funcs_keywords_singer}"/>
                                <i class="form-text">{LANG.funcs_keywords_help}</i>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="arr_funcs_description_singer" class="col-form-label text-sm-end col-sm-4">{LANG.funcs_description}:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="arr_funcs_description_singer" id="arr_funcs_description_singer" value="{DATA.arr_funcs_description_singer}"/>
                                <i class="form-text">{LANG.funcs_description_help}</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <input type="hidden" name="submitform" value="1"/>
            <input type="submit" value="{LANG.save}" class="btn btn-primary"/>
        </div>
    </div>
</form>
<!-- END: main -->
