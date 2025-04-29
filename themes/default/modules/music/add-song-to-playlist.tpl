<!-- BEGIN: main -->
<!-- BEGIN: notlogin -->
<div class="px-2">
    <a href="{LINK_LOGIN}"><strong class="text-danger">{LANG.addtolist_require_login}</strong></a>.
</div>
<!-- END: notlogin -->
<!-- BEGIN: data -->
<!-- BEGIN: playlist -->
<div class="ms-playlist-popover-ctn">
    <ul class="ms-playlist-popover px-2">
        <!-- BEGIN: loop -->
        <li class="clearfix">
            <span class="pull-right text-center"><!-- BEGIN: public --><i class="fa fa-globe fa-fw fa-lg" aria-hidden="true"></i><!-- END: public --><!-- BEGIN: private --><i class="fa fa-lock fa-fw fa-lg" aria-hidden="true"></i><!-- END: private --></span>
            <div class="playlist-name">
                <label class="ms-ellipsis" title="{PLAYLIST.playlist_name}"><input data-toggle="addOrRemove" data-code="{SONG.song_code}" data-tokend="{SONG.tokend}" data-playlist="{PLAYLIST.playlist_code}" type="checkbox"<!-- BEGIN: added --> checked="checked"<!-- END: added -->> {PLAYLIST.playlist_name}</label>
            </div>
        </li>
        <!-- END: loop -->
    </ul>
</div>
<hr class="my-2"/>
<!-- END: playlist -->
<div class="px-2">
    <a href="#" data-toggle="creatNew"<!-- BEGIN: hide_add_btn --> class="hidden"<!-- END: hide_add_btn -->><i class="fa fa-plus" aria-hidden="true"></i> {LANG.addtolist_new}</a>
    <form<!-- BEGIN: hide_add_form --> class="hidden"<!-- END: hide_add_form -->>
        <div class="form-group">
            <label>{LANG.pl_name}</label>
            <input type="text" class="form-control" name="playlist_name" value="">
        </div>
        <div class="form-group">
            <label>{LANG.pl_privacy}</label>
            <select class="form-control" name="privacy">
                <option value="1">{LANG.pl_privacy_public}</option>
                <option value="0">{LANG.pl_privacy_private}</option>
            </select>
        </div>
        <div class="text-center">
            <a href="#" class="btn btn-primary btn-block" data-toggle="creatNewSubmit"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{LANG.create}</a>
        </div>
        <input type="hidden" name="creatNewPlaylist" value="1">
        <input type="hidden" name="auto_add_song" value="1">
        <input type="hidden" name="song_code" value="{SONG.song_code}">
        <input type="hidden" name="tokend" value="{SONG.tokend}">
    </form>
</div>
<!-- END: data -->
<!-- END: main -->
