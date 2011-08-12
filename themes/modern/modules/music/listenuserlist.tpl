<!-- BEGIN: main -->
<script type="text/javascript" src="{GDATA.base_url}jwplayer.js"></script>
<script type="text/javascript" src="{GDATA.base_url}player.js"></script>
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
		<div style="float:left;background:black;cursor:pointer;width:470px;height:120px;">
		<!--[if !IE]> -->
		<object onclick="window.open('{GDATA.ads.url}'); return false;" type="application/x-shockwave-flash" data="{GDATA.ads.link}" width="470" height="120">
		<!-- <![endif]-->
		<!--[if IE]>
		<object onclick="window.open('{GDATA.ads.url}'); return false;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="479" height="120"
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
			var nv_num_song = {GDATA.numsong};
			var nv_current_song = 1;
			jwplayer("player").setup({
			flashplayer: "{GDATA.base_url}player.swf",			
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
			<div class="item" id="song-wrap-{SDATA.stt}">
				<strong>{SDATA.stt}. </strong><a id="song-{SDATA.stt}" class="nv-song-item" title="" name="{SDATA.song_url}" href="javascript:void(0);" onclick="play_song('player', this);return false;">{SDATA.song_name}</a> - <a onclick="this.target='_blank';" href="{SDATA.url_search_singer}" title="">{SDATA.song_singer}</a>
				<div class="fr">
					<div class="tool" style="margin-top:4px">
						<a name="{SDATA.id}" class="adds add"></a>
						<a href="{URL_DOWN}{SDATA.id}" class="down"></a>
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