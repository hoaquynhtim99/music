<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<script type="text/javascript" src="{base_url}player.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_playlist}</strong>
		</div>
	</div>
<div id="listen_main">
	<div class="playercontainer">
		<!-- BEGIN: null -->
			<strong>{LANG.playlist_null}</strong>
		<!-- END: null -->
		<div class="music-ads">
			<a onclick="this.target='_blank';" class="fixads" href="{ads.url}" title="">&nbsp;</a>
			<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,1,1,0" width="470" height="120">
				<param name="src" value="{ads.link}" />
				<param name="loop" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="transparent" />
				<param name="menu" value="false" />
				<embed src="{ads.link}" pluginspage="http://www.adobe.com/shockwave/download/" width="470" height="120" wmode="transparent" loop="true" menu="false" allowscriptaccess="always"></embed>
			</object>
		</div>
		<div id="player">Loading the player ...</div>	
		<script type="text/javascript">
			var nv_num_song = {GDATA.num};
			var nv_current_song = 1;
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",			
			controlbar: "bottom",
			volume: 100,
			height: 24,
			width: 470,
			autostart: true,
			events: {
				onReady: function(){nv_start_player('player')},
				onComplete: function(){nv_complete_song('player')}
			}
			});
		</script>
		<div class="clear"></div>
		<div id="playlist-container">
			<!-- BEGIN: song -->
			<div class="item" id="song-wrap-{ROW.stt}">
				<strong>{ROW.stt}. </strong><a id="song-{ROW.stt}" class="nv-song-item" title="" name="{ROW.song_url}" href="javascript:void(0);" onclick="play_song('player', this);return false;">{ROW.song_name}</a> - <a onclick="this.target='_blank';" href="{ROW.url_search_singer}" title="">{ROW.song_singer}</a>
				<div class="fr">
					<div class="tool" style="margin-top:4px">
						<a name="{ROW.id}" class="adds add"></a>
						<a href="{URL_DOWN}{ROW.id}" class="down"></a>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- END: song -->
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("a.adds").click(function() {
		$(this).removeClass("add"); 
		$(this).addClass("addedtolist"); 
		var songid = $(this).attr("name");
		addplaylist(songid);
	});
});
</script>
<!-- END: main -->