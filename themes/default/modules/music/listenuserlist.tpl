<!-- BEGIN: main -->
<script type="text/javascript" src="{GDATA.base_url}jwplayer.js"></script>
<h2 class="medium greencolor mlotitle">{GDATA.pl_name} - {GDATA.pl_singer}</h2>
<p class="msmall">
	{LANG.who_post}:<a class="singer" href="{GDATA.pl_url_search_upload}" title="{GDATA.pl_who_post}">{GDATA.pl_who_post}</a> | {LANG.view}: <span class="greencolor">{GDATA.pl_numview}</span> | {LANG.playlist_creat}: <span class="greencolor">{GDATA.pl_date}</span
</p>
<!-- Player -->
<div class="alboxw">
	<div class="alwrap">
		<div id="music-ads" class="music-ads">
			<a onclick="this.target='_blank';" href="{GDATA.ads.url}" title="{GDATA.ads.name}">&nbsp;</a>
			<div id="adscontent"></div>
		</div>
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
		$(document).ready(function(){
			// Install ADS
			var ads_width = $('#music-ads').width();
			$('#adscontent').html(
				'<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,1,1,0" width="'+ads_width+'" height="120">' + 
					'<param name="src" value="{GDATA.ads.link}" />' +
					'<param name="loop" value="true" />' +
					'<param name="allowscriptaccess" value="always" />' +
					'<param name="wmode" value="transparent" />' +
					'<param name="menu" value="false" />' +
					'<embed src="{GDATA.ads.link}" pluginspage="http://www.adobe.com/shockwave/download/" width="'+ads_width+'" height="120" wmode="transparent" loop="true" menu="false" allowscriptaccess="always"></embed>' +
				'</object>'
			);
		});
		// Install Player
		var player_width = $('#player').width();
		var nv_num_song = {GDATA.numsong};
		var nv_current_song = 1;
		jwplayer("player").setup({ flashplayer: "{GDATA.base_url}player.swf", controlbar: "bottom", volume: 100, height: 24, width: player_width, autostart: true,menu:false, events: {onReady: function(){nv_start_player('player')}, onComplete: function(){nv_complete_song('player')}} });
		</script>
		<div id="playlist-container" class="plsong">
			<!-- BEGIN: song -->
			<div class="item" id="song-wrap-{SDATA.stt}">
				<ul class="mtool">
					<li><a title="{LANG.song_edit_listen1} {SDATA.song_name}" href="{SDATA.url_listen}" class="mplay">&nbsp;</a></li>
					<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{SDATA.id}" class="madd">&nbsp;</a></li>
					<li><a title="{LANG.down_song}" href="{URL_DOWN}{SDATA.id}" class="mdown">&nbsp;</a></li>
				</ul>
				<strong>{SDATA.stt}</strong><a id="song-{SDATA.stt}" class="nv-song-item" title="" name="{SDATA.song_url}" href="javascript:void(0);" onclick="play_song('player', this);return false;">{SDATA.song_names}</a> - <a onclick="this.target='_blank';" href="{SDATA.url_search_singer}" title="{SDATA.song_singer}">{SDATA.song_singers}</a>
				<div class="clear"></div>
			</div>
			<!-- END: song -->
		</div>	
	</div>
</div>
<div class="clear"></div>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: '{NV_LANG_INTERFACE}'}</script>
<script type="text/javascript">
var url_share = u=location.href;
var title_share = document.title;
function share_facebook(){window.open("http://www.facebook.com/share.php?u="+encodeURIComponent(url_share)+"&t="+encodeURIComponent(title_share))}
function share_yahoo(){window.open("http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u="+encodeURIComponent(url_share)+"&t="+encodeURIComponent(title_share)+"&d=")}
function share_zingme(){window.open("http://link.apps.zing.vn/share?url="+encodeURIComponent(url_share)+"&title="+encodeURIComponent(title_share))}
</script>
<ul class="mlo-tool fl">
	<li class="tool"><a href="javascript:void(0);">{LANG.send_to}</a></li>
</ul>
<ul class="mlo-tool fr">
	<li><a class="musicicon mfacebook" href="javascript:void(0);" onclick="share_facebook();"></a></li>
	<li><a class="musicicon mzingme" href="javascript:void(0);" onclick="share_zingme();"></a></li>
	<li><a class="musicicon myahoo" href="javascript:void(0);" onclick="share_yahoo();"></a></li>
	<li><g:plusone size="small" annotation="none"></g:plusone></li>
</ul>
<div class="clear"></div>
<!-- Gift Tab -->
<div class="alboxw mg10 tab_content" id="tabs1">
	<div class="alwrap alcontent">
		<table cellpadding="0" cellspacing="0" class="musictable">
			<tr>
				<td class="left">Link Album:</td>
				<td><input class="txt-full" id="albumlink" onclick="Select_all('albumlink')" type="text" value="{GDATA.selfurl_base}" readonly="readonly"/></td>
			</tr>
		</table>
	</div>
</div>
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"><span>{LANG.playlist_info}</span></div>
		<div class="alcontent">
			<img class="musicsmalllalbum fl" src="{GDATA.playlist_img}" width="100" height="100" alt="{GDATA.pl_name}" />
			<strong class="greencolor">{GDATA.pl_name} - {GDATA.pl_singer}</strong><br />
			<span class="greencolor">{LANG.who_create}</span>: {GDATA.pl_who_post}<br />
			<span class="greencolor">{LANG.view}</span>: {GDATA.pl_numview}<br />
			<span class="greencolor">{LANG.message}</span>: {GDATA.pl_message}
			<div class="clear"></div>
		</div>
	</div>
</div>
<!-- END: main -->