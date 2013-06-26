<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<h2 class="medium greencolor mlotitle">{LANG.listen_playlist}</h2>
<!-- BEGIN: null --><div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.playlist_null}<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div><!-- END: null -->
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
		var nv_num_song = {GDATA.num};
		var nv_current_song = 1;
		jwplayer("player").setup({ flashplayer: "{base_url}player.swf", controlbar: "bottom", volume: 100, height: 24, width: player_width,menu:false, autostart: true, events: {onReady: function(){nv_start_player('player')}, onComplete: function(){nv_complete_song('player')}} });
		</script>
		<div id="playlist-container" class="plsong">
			<!-- BEGIN: song -->
			<div class="item" id="song-wrap-{ROW.stt}">
				<ul class="mtool">
					<li><a title="{LANG.song_edit_listen1} {ROW.song_name}" href="{ROW.url_listen}" class="mplay">&nbsp;</a></li>
					<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{ROW.id}" class="madd">&nbsp;</a></li>
					<li><a title="{LANG.down_song}" href="{URL_DOWN}{ROW.id}" class="mdown">&nbsp;</a></li>
				</ul>
				<strong>{ROW.stt}</strong><a id="song-{ROW.stt}" class="nv-song-item" title="" name="{ROW.song_url}" href="javascript:void(0);" onclick="play_song('player', this);return false;">{ROW.song_names}</a> - <a onclick="this.target='_blank';" href="{ROW.url_search_singer}" title="{ROW.song_singer}">{ROW.song_singers}</a>
				<div class="clear"></div>
			</div>
			<!-- END: song -->
		</div>	
	</div>
</div>
<!-- END: main -->