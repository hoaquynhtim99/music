<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="clear"></div>
<h2 class="medium greencolor mlotitle">{ALBUM.name} - <a class="singer" href="{ALBUM.url_search_singer}" title="{ALBUM.singer}">{ALBUM.singer}</a></h2>
<p class="msmall">{LANG.who_post}: <a class="singer" href="{ALBUM.url_search_upload}" title="{ALBUM.who_post}">{ALBUM.who_post}</a> | {LANG.view}: {ALBUM.numview}</p>
<!-- Player -->
<div class="alboxw">
	<div class="alwrap">
		<div id="music-ads" class="music-ads">
			<a onclick="this.target='_blank';" href="{ads.url}" title="{ads.name}">&nbsp;</a>
			<div id="adscontent"></div>
		</div>
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
		$(document).ready(function(){
			// Install ADS
			var ads_width = $('#music-ads').width();
			$('#adscontent').html(
				'<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,1,1,0" width="'+ads_width+'" height="120">' + 
					'<param name="src" value="{ads.link}" />' +
					'<param name="loop" value="true" />' +
					'<param name="allowscriptaccess" value="always" />' +
					'<param name="wmode" value="transparent" />' +
					'<param name="menu" value="false" />' +
					'<embed src="{ads.link}" pluginspage="http://www.adobe.com/shockwave/download/" width="'+ads_width+'" height="120" wmode="transparent" loop="true" menu="false" allowscriptaccess="always"></embed>' +
				'</object>'
			);
		});
		// Install Player
		var player_width = $('#player').width();
		var nv_num_song = {ALBUM.numsong};
		var nv_current_song = 1;
		jwplayer("player").setup({ flashplayer: "{base_url}player.swf", controlbar: "bottom", volume: 100, height: 24, width: player_width, autostart: true, events: {onReady: function(){nv_start_player('player')}, onComplete: function(){nv_complete_song('player')}} });
		</script>
		<div id="playlist-container" class="plsong">
			<!-- BEGIN: song -->
			<div class="item" id="song-wrap-{SONG.stt}">
				<ul class="mtool">
					<li><a title="{LANG.song_edit_listen1} {SONG.song_name}" href="{SONG.url_listen}" class="mplay">&nbsp;</a></li>
					<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{SONG.id}" class="madd">&nbsp;</a></li>
					<li><a title="{LANG.down_song}" href="{URL_DOWN}{SONG.id}" class="mdown">&nbsp;</a></li>
				</ul>
				<strong>{SONG.stt}</strong><a id="song-{SONG.stt}" class="nv-song-item" title="" name="{SONG.song_url}" href="javascript:void(0);" onclick="play_song('player', this);return false;">{SONG.song_names}</a> - <a onclick="this.target='_blank';" href="{SONG.url_search_singer}" title="{SONG.song_singer}">{SONG.song_singers}</a>
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
	<li class="tool"><a href="#tabs1">{LANG.send_to}</a></li>
	<li class="tool"><a href="#tabs2">{LANG.give_error}</a></li>
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
				<td><input class="txt-full" id="albumlink" onclick="Select_all('albumlink')" type="text" value="{ALBUM.URL_ALBUM}" readonly="readonly"/></td>
			</tr>
			<tr>
				<td class="left">{LANG.blog_song}:</td>
				<td><input class="txt-full" id="blogsong" onclick="Select_all('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;60&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{playerurl}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;playlistfile={ALBUM.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{playerurl}player.swf&quot; width=&quot;500&quot; height=&quot;60&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;playlistfile={ALBUM.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly" /></td>
			</tr>
			<tr>
				<td class="left">{LANG.forum_song}:</td>
				<td><input class="txt-full" id="songforum" onclick="Select_all('songforum');" type="text" value="[FLASH]{playerurl}player.swf?playlistfile={ALBUM.creat_link_url}[/FLASH]" readonly="readonly"/></td>
			</tr>
		</table>
	</div>
</div>
<!-- SEND ERROR -->
<div class="alboxw mg10 tab_content" id="tabs2">
	<div class="alwrap alcontent">
		<p><strong><span class="musicicon merror">&nbsp;</span>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
		<table cellpadding="0" cellspacing="0" class="musictable">
			<tr>
				<td colspan="2">
					<select class="txt-full" id="root_error" name="root_error">
						<option value="">{LANG.error_choose}</option>
						<option>{LANG.error_a_1}</option>
						<option>{LANG.error_a_2}</option>
						<option>{LANG.error_a_3}</option>
						<option>{LANG.error_a_4}</option>
						<option>{LANG.error_a_5}</option>
						<option>{LANG.error_a_6}</option>
						<option>{LANG.error_a_7}</option>
						<option>{LANG.error_a_8}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="left">{LANG.enter_name}:</td>
				<td><input class="txt-full" id="user" type="text" value="{USER_NAME}" {NO_CHANGE}/></td>
			</tr>
			<tr>
				<td colspan="2">{LANG.give_error_diff}</td>
			</tr>
			<tr>
				<td colspan="2"><textarea class="txt-full" id="bodyerror" name="message" style="height:50px"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" class="mcenter"><input class="mbutton" onclick="senderror('{ID}', 'album');" type="button" value="{LANG.send}"/></td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide(); 
	$("ul.mlo-tool li.tool:first").addClass("active").show();
	$(".tab_content:first").show(); 
	$("ul.mlo-tool li.tool").click(function(){
		$("ul.mlo-tool li.tool").removeClass("active");
		$(this).addClass("active"); 
		$(".tab_content").hide(); 
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).show(); 
		return !1;
	});
});
</script>
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"><span>{LANG.album_info}</span></div>
		<div class="alcontent">
			<img class="musicsmalllalbum fl" src="{ALBUM.album_thumb}" width="100" height="100" alt="{ALBUM.name}" />
			<strong class="greencolor">{ALBUM.name} - {ALBUM.singer}</strong><br />
			<span class="greencolor">{LANG.who_create}</span>: {ALBUM.who_post}<br />
			<span class="greencolor">{LANG.view}</span>: {ALBUM.numview}<br />
			<span class="greencolor">{LANG.message}</span>: {ALBUM.describe}
		</div>
	</div>
</div>
<!-- COMMENT -->
<div class="alboxw mg10">
	<div class="alwrap">
		<div class="alheader">
			<span>{LANG.comment}</span>
		</div>
		<div class="alcontent">
			<div id="comment-content"></div>
			<!-- BEGIN: nocomment -->
			<div class="alboxw">
				<div class="alwrap alcontent infoerror">
					<div>
						{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}
					</div>
				</div>
			</div>
			<!-- END: nocomment -->
			<!-- BEGIN: stopcomment -->
			<div class="alboxw">
				<div class="alwrap alcontent infoerror">
					<div>
						{LANG.setting_stop}
					</div>
				</div>
			</div>
			<!-- END: stopcomment -->
			<!-- BEGIN: comment -->
			<table cellpadding="0" cellspacing="0" class="musictable">
				<tr>
					<td class="left">{LANG.your_name}:</td>
					<td><input class="txt-full" type="text" name="name" id="name" value="{GDATA.username}" {CDATA.no_change}/></td>
				</tr>
				<tr>
					<td class="left">{LANG.content}:</td>
					<td><textarea class="txt-full" name="body" id="commentbody" style="height:50px"></textarea></td>
				</tr>
				<tr>
					<td class="mcenter" colspan="2">
						<input class="mbutton" id="button-comment" type="button" value="{LANG.send}" onclick="sendcommment('{ID}' , 'album');"/>
						<input class="mbutton" type="button" onclick="nv_show_emotions('emotion-content');" value="{LANG.emotion}"/>
						<script type="text/javascript" src="{base_url}showemotion.js"></script>
						<div class="wrap-emotion"><div class="emotion-content" id="emotion-content"></div></div>
					</td>
				</tr>
			</table>
			<div class="clear"></div>
			<script type="text/javascript">
			$(document).ready(function(){show_comment('{ID}','album',0);});
			</script>
			<!-- END: comment -->
		</div>
	</div>
</div>
<!-- END: main -->