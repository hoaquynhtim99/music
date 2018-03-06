<link type="text/css" href="{PLUGINS_DIR}jscrollpane/jquery.jscrollpane.css" rel="stylesheet" media="all"/>
<script type="text/javascript" src="{PLAYER_DIR}jwplayer.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/mwheelIntent.js"></script>
<script type="text/javascript" src="{PLUGINS_DIR}jscrollpane/jquery.jscrollpane.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var jScrollPane = $('#soplaylists').jScrollPane({
        animateScroll: true
    });
    var jScrollPaneAPI = jScrollPane.data('jsp');
    $('#soplaylists').mouseenter(function() {
        $(this).data('mousein', true);
    });
    $('#soplaylists').mouseleave(function() {
        $(this).data('mousein', false);
    });
    var playlistplayer = jwplayer('playlistplayer').setup({
        width: '100%',
        height: '219',
        stretching: 'fill',
        image: '{ALBUM.resource_avatar}',
        <!-- BEGIN: playlist_js -->playlist: [<!-- BEGIN: loop -->{
            sources: [
                <!-- BEGIN: filesdata -->{"file": "{FILESDATA.resource_path}", "label": "{FILESDATA.quality_name}"}<!-- BEGIN: comma -->, <!-- END: comma --><!-- END: filesdata -->
            ],
            image: "{PLSO_DATA.resource_cover}",
            title: ""
        }<!-- BEGIN: comma -->, <!-- END: comma --><!-- END: loop -->],
        <!-- END: playlist_js -->
        autostart: true,
        repeat: true,
        skin: {name: "nvmsso"},
        localization: {
            fullscreen: '{LANG.player_lang_fullscreen}',
            settings: '{LANG.player_lang_settings}',
            hd: '{LANG.player_lang_hd}'
        },
        displaytitle: true
    });
    // Play một bài hát mới trong playlist
    playlistplayer.on('playlistItem', function(item) {
        var thisitem = $('#soplaylists').find('li.plitem[data-plindex="' + item.index + '"]');
        // Cuộn playlist nếu không để chuột vào playlist
        if ($('#soplaylists').data('mousein') == false) {
            jScrollPaneAPI.scrollToY(item.index * thisitem.outerHeight());
        }
        msLoadLyric(thisitem.data('socode'), thisitem.data('sotitle'), thisitem.data('tokend'), '#solrtName', '#detail-song-lrt');
    });
    // Click vào tên một bài hát trong playlist
    $('#soplaylists [data-control="playsong"]').click(function(e) {
        e.preventDefault();
        playlistplayer.playlistItem($(this).data('index'));
    });
    // Chạy/Tạm dừng phát
    playlistplayer.on('play', function(event) {
        var index = playlistplayer.getPlaylistIndex();
        $('#soplaylists').find('li.plitem').removeClass('playing pause');
        var thisitem = $('#soplaylists').find('li.plitem[data-plindex="' + index + '"]');
        thisitem.addClass('playing');
    });
    playlistplayer.on('pause', function(event) {
        var index = playlistplayer.getPlaylistIndex();
        $('#soplaylists').find('li.plitem').removeClass('playing pause');
        var thisitem = $('#soplaylists').find('li.plitem[data-plindex="' + index + '"]');
        thisitem.addClass('pause');
    });
});
</script>
