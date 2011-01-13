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
		<a href="{ads.url}" onclick="window.open(this.href); return false;"><div style="float:left;background:black;cursor:pointer;width:470px;height:236px;">
		<!--[if !IE]> -->
		<object type="application/x-shockwave-flash" data="{ads.link}" width="470" height="236">
		<!-- <![endif]-->
		<!--[if IE]>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="479" height="236"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
			<param name="movie" value="{ads.link}" />
		<!--><!--dgx-->
			<param name="loop" value="true" />
			<param name="wmode" value="transparent" />
			<param name="menu" value="false" />
		</object>
		<!-- <![endif]-->
		</div></a>
		<div id="player">Loading the player a1binhdinh.com ...</div>
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{song_url}", image: "{base_url}logo.png", title: "{song_name} - ", description: " {song_singer}"},
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