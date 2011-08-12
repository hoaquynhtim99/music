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
		<div style="float:left;background:black;cursor:pointer;width:470px;height:236px;">
		<!--[if !IE]> -->
		<object onclick="window.open('{ads.url}'); return false;" type="application/x-shockwave-flash" data="{ads.link}" width="470" height="236">
		<!-- <![endif]-->
		<!--[if IE]>
		<object onclick="window.open('{ads.url}'); return false;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="479" height="236"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
			<param name="movie" value="{ads.link}" />
		<!--><!--dgx-->
			<param name="loop" value="true" />
			<param name="wmode" value="transparent" />
			<param name="menu" value="false" />
		</object>
		<!-- <![endif]-->
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