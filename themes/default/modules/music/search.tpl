<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span>{LANG.search_song1}</span>
		</div>
	</div>
</div>
<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.search_find} {GDATA.num} {LANG.results}.<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
<div class="clear"></div>
<!-- BEGIN: loop -->
<ul class="mtool">
	<!-- BEGIN: hit --><li><a title="{LANG.hit_song}" href="javascript:void(0);" class="mstar">&nbsp;</a></li><!-- END: hit -->
	<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{SONG.id}" class="madd">&nbsp;</a></li>
	<li><a title="{LANG.down_song}" href="{URL_DOWN}{SONG.id}" class="mdown">&nbsp;</a></li>
</ul>
<a class="musicicon mplay" title="{LANG.song_edit_listen1} {SONG.name}" href="{SONG.url_listen}"><strong>{SONG.name}</strong></a><br />
{LANG.show}: <a href="{SONG.url_search_singer}" title="{LANG.search_song_by_singer} {SONG.singer}" class="singer">{SONG.singer}</a><br />
<div class="smalltext">{LANG.upload}: <a class="singer" href="{SONG.url_search_upload}" title="{LANG.search_song_by_uploader} {SONG.upload}">{SONG.upload}</a> | {LANG.category_2}: <a class="singer" href="{SONG.url_search_category}" title="{LANG.search_song_by_cat} {SONG.category}">{SONG.category}</a> | {LANG.view}: {SONG.view} | {SONG.bitrate}kb/s | {SONG.duration} | {SONG.size} MB</div>
<div class="clear"></div>
<div class="hr"></div>
<!-- BEGIN: sub -->
<!-- BEGIN: video -->
<blockquote><strong>{LANG.resuitvideo}</strong></blockquote>
<!-- BEGIN: loop -->
<div class="gv-wrap fixwrap">
	<div class="vcontent">
		<a href="{VIDEO.videoview}" title="{VIDEO.videoname}"><img alt="{VIDEO.videoname}" class="musicsmalllalbum" src="{VIDEO.thumb}" width="128" height="72"/></a>
		<a href="{VIDEO.videoview}" title="{VIDEO.videoname}">{VIDEO.videonames}</a><br />
		<a class="singer" href="{VIDEO.s_video}" title="{VIDEO.videosinger}">{VIDEO.videosinger}</a>
	</div>
</div>
<!-- END: loop -->
<div class="clear"></div>
<div class="hr"></div>
<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{allvideo}" >&nbsp;{LANG.view_all}</a></p>
<div class="clear"></div>
<div class="hr"></div>
<!-- END: video -->
<!-- BEGIN: album -->
<blockquote><strong>{LANG.resuitalbum}</strong></blockquote>
<!-- BEGIN: loop -->
<div class="topalbum_item">
	<div class="alcontent">
		<a href="{ALBUM.albumview}" title="{ALBUM.albumname} - {ALBUM.albumsinger}">			 
			<img class="musicsmalllalbum mmimgalbum" src="{ALBUM.thumb}" width="90" height="90" alt="{ALBUM.albumname}"/>
		</a>
		<div class="alcontent">
			<a href="{ALBUM.albumview}" title="{ALBUM.albumname}">{ALBUM.albumnames}</a><br />
			<a class="singer" href="{ALBUM.url_search_singer}" title="{ALBUM.albumsinger}">{ALBUM.albumsinger}</a>
		</div>
	</div>
</div>
<!-- END: loop -->
<div class="clear"></div>
<div class="hr"></div>
<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{allalbum}" >&nbsp;{LANG.view_all}</a></p>
<div class="clear"></div>
<div class="hr"></div>
<!-- END: album -->
<!-- END: sub -->
<!-- END: loop -->
<!-- END: main -->