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
		<div id="player">Loading the player a1binhdinh.com ...</div>
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{song_url}", image: "{base_url}logo.png", title: "{song_name} - ", description: " {song_singer}", "adv.enable": "true",
			"adv.link": "{ads.url}",
			"adv.file": "{ads.link}?bid=wrawpjhwx" },
			<!-- END: song -->
			],
			controlbar: "bottom",
			volume: 100,
			height: 260,
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