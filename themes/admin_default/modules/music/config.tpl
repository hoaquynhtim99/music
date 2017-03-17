<!-- BEGIN: main -->
<div class="alert alert-info">{CONFIG_NOTE}.</div>
<form action="{FORM_ACTION}" method="post" role="form" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="panel panel-info">
            <div class="panel-heading"><strong>{LANG.config_mainpage}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="home_albums_display" id="home_albums_display" value="1"{DATA.home_albums_display} /> {LANG.home_albums_display}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="home_singers_display" id="home_singers_display" value="1"{DATA.home_singers_display} /> {LANG.home_singers_display}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="home_songs_display" id="home_songs_display" value="1"{DATA.home_songs_display} /> {LANG.home_songs_display}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="home_videos_display" id="home_videos_display" value="1"{DATA.home_videos_display} /> {LANG.home_videos_display}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="home_albums_weight" class="control-label col-sm-8">{LANG.home_albums_weight}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <select name="home_albums_weight" id="home_albums_weight" class="form-control" data-toggle="mscfgmainweight" data-value="{DATA.home_albums_weight}">
                            <!-- BEGIN: home_albums_weight -->
                            <option value="{WEIGHT}"{HOME_ALBUMS_WEIGHT}>{WEIGHT}</option>
                            <!-- END: home_albums_weight -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="home_singers_weight" class="control-label col-sm-8">{LANG.home_singers_weight}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <select name="home_singers_weight" id="home_singers_weight" class="form-control" data-toggle="mscfgmainweight" data-value="{DATA.home_singers_weight}">
                            <!-- BEGIN: home_singers_weight -->
                            <option value="{WEIGHT}"{HOME_SINGERS_WEIGHT}>{WEIGHT}</option>
                            <!-- END: home_singers_weight -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="home_songs_weight" class="control-label col-sm-8">{LANG.home_songs_weight}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <select name="home_songs_weight" id="home_songs_weight" class="form-control" data-toggle="mscfgmainweight" data-value="{DATA.home_songs_weight}">
                            <!-- BEGIN: home_songs_weight -->
                            <option value="{WEIGHT}"{HOME_SONGS_WEIGHT}>{WEIGHT}</option>
                            <!-- END: home_songs_weight -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="home_videos_weight" class="control-label col-sm-8">{LANG.home_videos_weight}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <select name="home_videos_weight" id="home_videos_weight" class="form-control" data-toggle="mscfgmainweight" data-value="{DATA.home_videos_weight}">
                            <!-- BEGIN: home_videos_weight -->
                            <option value="{WEIGHT}"{HOME_VIDEOS_WEIGHT}>{WEIGHT}</option>
                            <!-- END: home_videos_weight -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="home_albums_nums" class="control-label col-sm-8">{LANG.home_albums_nums}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <input class="form-control required" type="text" name="home_albums_nums" id="home_albums_nums" value="{DATA.home_albums_nums}"/>
                        <i class="help-block">{LANG.config_available_if_choose}</i>    
                    </div>
                </div>    
                <div class="form-group">
                    <label for="home_singers_nums" class="control-label col-sm-8">{LANG.home_singers_nums}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <input class="form-control required" type="text" name="home_singers_nums" id="home_singers_nums" value="{DATA.home_singers_nums}"/>
                        <i class="help-block">{LANG.config_available_if_choose}</i>    
                    </div>
                </div>    
                <div class="form-group">
                    <label for="home_songs_nums" class="control-label col-sm-8">{LANG.home_songs_nums}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <input class="form-control required" type="text" name="home_songs_nums" id="home_songs_nums" value="{DATA.home_songs_nums}"/>
                        <i class="help-block">{LANG.config_available_if_choose}</i>    
                    </div>
                </div>    
                <div class="form-group">
                    <label for="home_videos_nums" class="control-label col-sm-8">{LANG.home_videos_nums}:</label>
                    <div class="col-sm-16 col-lg-6">
                        <input class="form-control required" type="text" name="home_videos_nums" id="home_videos_nums" value="{DATA.home_videos_nums}"/>
                        <i class="help-block">{LANG.config_available_if_choose}</i>    
                    </div>
                </div>    
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
                <input type="hidden" name="submit" value="1"/>
                <input type="submit" value="{LANG.save}" class="btn btn-primary"/>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->