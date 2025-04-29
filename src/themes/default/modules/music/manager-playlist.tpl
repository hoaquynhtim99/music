<!-- BEGIN: main -->
<div class="ms-manager-pl-head-row">
    <div class="ms-c-avatar">
        <div class="ms-manager-pl-avatar" style="background-image: url('{DATA.resource_avatar}');">
            <a href="#" class="ms-edit-icon"><i class="fa fa-pencil" aria-hidden="true"></i> Đổi ảnh</a>
        </div>
    </div>
    <div class="ms-c-info">
        <div class="ms-title">
            <h1 class="ms-val">{DATA.playlist_name}<a href="#" class="ms-edit-icon" data-toggle="editPlaylistField"><i class="fa fa-pencil" aria-hidden="true"></i></a></h1>
            <form method="post" class="ms-form" data-toggle="msInlineForm" action="{DATA.manager_link}" data-field="title" data-tokend="{DATA.tokend}">
                <input type="text" class="form-control" value="{DATA.playlist_name}" data-toggle="msInlineFormInput">
                <button type="submit" class="ms-button text-success"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
            </form>
        </div>
        <div class="ms-introtext">
            <div class="ms-val">{DATA.playlist_introtext}<a href="#" class="ms-edit-icon" data-toggle="editPlaylistField"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
            <form method="post" class="ms-form" data-toggle="msInlineForm" action="{DATA.manager_link}" data-field="introtext" data-tokend="{DATA.tokend}">
                <textarea rows="2" class="form-control" data-toggle="msInlineFormInput">{DATA.form_playlist_introtext}</textarea>
                <button type="submit" class="ms-button text-success"><i class="fa fa-check-circle" aria-hidden="true"></i></button>
            </form>
        </div>
        <div class="ms-stat"><strong>{DATA.num_songs} {LANG.song1}</strong></div>
        <div class="ms-btns">
            <a href="{DATA.playlist_link}" class="btn btn-success">{LANG.mymusic_playlist_play}</a>
            <a href="#" class="btn btn-danger" data-toggle="delMyPlaylist" data-url="{DATA.manager_link}" data-tokend="{DATA.tokend}">{LANG.mymusic_playlist_del}</a>
        </div>
    </div>
</div>
<!-- END: main -->
