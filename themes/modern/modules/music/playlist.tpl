<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div id="listen_head">
	<h2>{LANG.listen_playlist}</h2>
</div>
<div id="listen_main">
	<div class="playercontainer">
		<!-- BEGIN: null -->
			<h2>{LANG.playlist_null}</h2>
		<!-- END: null -->
		<div id="player">Loading the player a1binhdinh.com ...</div>
		
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{song_url}", image: "{ads}", title: "{song_name} - ", description: " {song_singer}" },
			<!-- END: song -->
			],
			"playlist.position": "bottom",
			"playlist.size": 1,
			controlbar: "bottom",
			"logo.file": "{base_url}logo.png",
			"logo.position": "top-right",
			"logo.hide": "false",
			"logo.link": "{NV_MY_DOMAIN}",
			"logo.linktarget": "_top",
			volume: 100,
			height: 260,
			width: 470,
			skin: "{base_url}skewd.zip",
			autostart: "true",
			
			events: {
			onComplete: function(event) {
			jwplayer().playlistNext();
			}
			}
			
			});
		</script>
	</div>
</div>
<!-- END: main -->