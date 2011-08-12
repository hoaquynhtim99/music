<!-- BEGIN: main -->
<script type="text/javascript" src="{GDATA.base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{GDATA.base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_playlist}</strong>
		</div>
	</div>
<div id="listen_main">
	<strong>{GDATA.pl_name} - {GDATA.pl_singer}</strong>
	<p>{LANG.who_post}:<a href="{GDATA.pl_url_search_upload}"> {GDATA.pl_who_post}&nbsp;</a> | {LANG.view}: {GDATA.pl_numview} | {LANG.playlist_creat}: {GDATA.pl_date}</p>
	<div class="playercontainer">
		<div style="float:left;background:black;cursor:pointer;width:470px;height:236px;">
		<!--[if !IE]> -->
		<object onclick="window.open('{GDATA.ads.url}'); return false;" type="application/x-shockwave-flash" data="{GDATA.ads.link}" width="470" height="236">
		<!-- <![endif]-->
		<!--[if IE]>
		<object onclick="window.open('{GDATA.ads.url}'); return false;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="479" height="236"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
			<param name="movie" value="{GDATA.ads.link}" />
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
			flashplayer: "{GDATA.base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{SDATA.song_url}", image: "{GDATA.base_url}logo.png", title: "{SDATA.song_name} - ", description: " {SDATA.song_singer}" },
			<!-- END: song -->
			],
			controlbar: "bottom",
			volume: 100,
			height: 24,
			width: 470,
			autostart: "true",
			events: {
			onComplete: function(event) {
			//jwplayer().playlistNext();
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
				<a target="_blank" href="http://www.facebook.com/sharer.php?u={GDATA.selfurl_encode}&t={GDATA.pl_name}-{GDATA.pl_singer}" class="facebook">Facebook</a>
			</div>
			<form action="#" method="post">
				<p>Link Playlist:
				<input id="songforum" onClick="SelectAll('songforum');" type="text" value="{GDATA.selfurl_base}" /> </p>
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
	<img border="0" src="{GDATA.playlist_img}" width="90px" height="90px" />
	<p><span><strong>{GDATA.pl_name} - {GDATA.pl_singer}</strong><br />
	{LANG.who_create}: {GDATA.pl_who_post}<br />
	{LANG.view}: {GDATA.pl_numview}</span><br />
	{LANG.message}: {GDATA.pl_message}</p>
</div>	
<div class="clear"> </div>
</div>

<!-- END: main -->