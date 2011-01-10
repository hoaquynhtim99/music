﻿<!-- BEGIN: main -->
<!--<meta name="verify-v1" content="nPO+sJiorgxine7U88iTPkF/yVTxi3t9QOi9Wi8z60w=" />-->
<link rel="image_src" href="{base_url}ember.jpg" />

<meta name="video_height" content="60" /> 
<meta name="video_width" content="400" /> 
<meta name="video_type" content="application/x-shockwave-flash" />

<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_playlist}</strong>
		</div>
	</div>
<div id="listen_main">
	<strong>{name} - {singer}</strong>
	<p>{LANG.who_post}:<a href="{url_search_upload}"> {who_post}&nbsp;</a> | {LANG.view}: {numview} | {LANG.playlist_creat}: {date}</p>
	<div class="playercontainer">
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
<ul class="tool">
	<li class="active"><a class="give">{LANG.send_to}</a></li>
</ul>
	<div style="width:498px;" id="boderfull" class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="sendtool">
				<a target="_blank" href="http://www.facebook.com/sharer.php?u={URL_PL}&t={name}-{singer}" class="facebook">Facebook</a>
			</div>
			<form action="#" method="post">
				<p>Link Playlist:
				<input id="songforum" onClick="SelectAll('songforum');" type="text" value="{URL_PL}" /> </p>
			</form>
			<script type="text/javascript">
				function SelectAll(id)
				{
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
	</div> 
	</div>
<div class="box-border-shadow m-bottom">
<div class="cat-box-header"> 
<div class="cat-nav"> 
<strong>{LANG.playlist_info}</strong>
</div>
</div>
<div id="album_info">
	<img border="0" src="{playlist_img}" width="90px" height="90px" />
	<p><span><strong>{name} - {singer}</strong><br />
	{LANG.who_create}: {who_post}<br />
	{LANG.view}: {numview}</span><br />
	{LANG.message}: {message}</p>
</div>	
<div class="clear"> </div>
</div>

<!-- END: main -->