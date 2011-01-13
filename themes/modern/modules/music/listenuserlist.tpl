<!-- BEGIN: main -->
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
		<div id="player">Loading the player ...</div>	
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{song_url}", image: "{base_url}logo.png", title: "{song_name} - ", description: " {song_singer}", },
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