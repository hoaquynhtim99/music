<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
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
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{ROW.song_url}", title: "{ROW.stt}. {ROW.song_name} - ", description: " {ROW.song_singer}"},
			<!-- END: song -->
			],
			controlbar: "bottom",
			volume: 100,
			height: 24,
			width: 470,
			autostart: "true",
			events: {
			onComplete: function(event) {
			jwplayer().playlistNext();
			}
			}
			});
		</script>
		<div class="clear"></div>
	</div>
</div>
</div>
<!-- END: main -->