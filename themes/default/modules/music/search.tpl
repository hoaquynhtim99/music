<!-- BEGIN: main -->
<div class="alboxw mssearch-adv">
	<div class="alwrap">
		<div class="alheader"> 
			<span>{LANG.search_song1} &quot;{TITLE_SEARCH}&quot;</span>
			<div class="mss-advtool" id="mss-advtool" onclick="NVMS.advsearch.prosess();">
				{LANG.search_adv} <span class="musicicon madvance">&nbsp;</span>
			</div>
		</div>
	</div>
	<div class="ms-advwrap" id="ms-advwrap">
		<div class="ms-advcontent">
			<div class="col-33">
				<blockquote>{LANG.search_song1}</blockquote>
				<!-- BEGIN: wheresearch -->
				<a href="{URL}" class="musicicon mplay singer{CURRENT}">{TITLE}</a><br />
				<!-- END: wheresearch -->
			</div>
			<div class="col-33">
				<blockquote>{LANG.search_adv_search_with}</blockquote>
				<!-- BEGIN: typesearch -->
				<a href="{URL}" class="musicicon mplay singer{CURRENT}">{TITLE}</a><br />
				<!-- END: typesearch -->
			</div>
			<div class="col-33">
				<div class="fr">
					<a href="javascript:void(0);" onclick="NVMS.advsearch.prosess('close');" class="musicicon mcancel" title="{LANG.close}">&nbsp;</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<!-- BEGIN: singer_info -->
<div class="mg10 ms-shwrap">
	<div class="ms-shimg">
		<img class="fl musicsmalllalbum" src="{SDATA.thumb}" alt="{SDATA.tenthat}" width="100" height="100"/>
	</div>
	<div class="ms-shdetail" id="singersdetail">
		<h2 class="medium greencolor">{SDATA.tenthat}</h2>
		{SDATA.introduction}
	</div>
	<div class="ms-shshow">
		<a href="javascript:void(0);" rel="0|{LANG.view_expand}|{LANG.view_collapse}|singersdetail" class="musicicon zoomin greencolor ms-shd" title="{LANG.view_expand}">{LANG.view_expand}</a>
	</div>
	<div class="clear"></div>
</div>
<!-- END: singer_info -->
<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.search_find} {NUM_RESULT} {LANG.results}. {LANG.search_adv_info_use}.</div></div></div>
<div class="clear"></div>
<!-- BEGIN: typesong -->
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
<blockquote><strong>{LANG.resuitvideo} &quot;{QUERY_SEARCH.key}&quot;</strong></blockquote>
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
<blockquote><strong>{LANG.resuitalbum} &quot;{QUERY_SEARCH.key}&quot;</strong></blockquote>
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
<!-- END: typesong -->

<!-- BEGIN: typealbum -->
<!-- BEGIN: loop -->
	<a href="{ALBUM.albumview}" title="{LANG.listen_album} {ALBUM.albumname}">
		<img alt="{ALBUM.albumname}" class="musicsmalllalbum fl" width="100" height="100" src="{ALBUM.thumb}" />
	</a>
	<h2 class="medium">
		<a href="{ALBUM.albumview}" title="{LANG.listen_album} {ALBUM.albumname}">{ALBUM.albumname}</a> - 
		<a title="{ALBUM.albumsinger}" href="{ALBUM.url_search_singer}">{ALBUM.albumsinger}</a>
		<!-- BEGIN: hit --><span class="musicicon mhit hitsong">&nbsp;&nbsp;&nbsp;</span><!-- END: hit -->
	</h2>
	<p>{LANG.who_create_1}: <a class="singer" title="{ALBUM.upboi}" href="{ALBUM.url_search_upload}">{ALBUM.upboi}</a> | {LANG.view}: {ALBUM.numview}</p>
	{ALBUM.describe}
	<div class="clear"></div>
	<div class="hr"></div>
<!-- END: loop -->
<!-- END: typealbum -->

<!-- BEGIN: typeplaylist -->
<!-- BEGIN: loop -->
	<a href="{PLAYLIST.link}" title="{LANG.playlist_listen} {PLAYLIST.name}">
		<img alt="{PLAYLIST.name}" class="musicsmalllalbum fl" width="100" height="100" src="{PLAYLIST.thumb}" />
	</a>
	<h2 class="medium">
		<a href="{PLAYLIST.link}" title="{LANG.playlist_listen} {PLAYLIST.name}">{PLAYLIST.name}</a> - 
		<a title="{PLAYLIST.singer}" href="{PLAYLIST.url_search_singer}">{PLAYLIST.singer}</a>
	</h2>
	<p>{LANG.who_create_1}: <a class="singer" title="{PLAYLIST.username}" href="{PLAYLIST.url_search_upload}">{PLAYLIST.username}</a> | {LANG.view}: {PLAYLIST.view}</p>
	{PLAYLIST.message}
	<div class="clear"></div>
	<div class="hr"></div>
<!-- END: loop -->
<!-- END: typeplaylist -->

<!-- BEGIN: typevideo -->
<!-- BEGIN: loop -->
<a href="{VIDEO.videoview}" title="{VIDEO.videoname}"><img class="musicsmalllalbum fl" width="128" height="72" src="{VIDEO.thumb}" alt="{VIDEO.videoname}" /></a>
<h2 class="medium">
	<a href="{VIDEO.videoview}" title="{VIDEO.videoname}">{VIDEO.videoname}</a>
	<!-- BEGIN: hit --><span class="musicicon mhit hitsong">&nbsp;&nbsp;&nbsp;</span><!-- END: hit -->
</h2>
{LANG.show}: <a class="singer" href="{VIDEO.s_video}" title="{VIDEO.videosinger}">{VIDEO.videosinger}</a><br />
{LANG.playlist_creat}: <span class="greencolor">{VIDEO.dt}</span> | {LANG.view1}: <span class="greencolor">{VIDEO.view}</span>
<div class="clear"></div>
<div class="hr"></div>
<!-- END: loop -->
<!-- END: typevideo -->

<!-- BEGIN: gennerate_page -->
<div class="clear"></div>
<div class="hr"></div>
<div class="mcenter">{GENNERATE_PAGE}</div>
<!-- END: gennerate_page -->

<!-- END: main -->