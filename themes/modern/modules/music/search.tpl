<!-- BEGIN: main -->
<div id="searchcontent">
	<div class="box-border m-bottom"> 
		<div class="header-block1"> 
			<h3><span>{LANG.search_song}</span></h3> 
		</div> 
		<div class="resuirt">
			<p><strong>{LANG.search_find} {GDATA.num} {LANG.results}.</strong></p>
			<!-- BEGIN: loop -->
			<div class="itemsearch {gray}">
				<div class="tool">
					<a name="{SONG.id}" class="adds add"></a>
					<a href="{URL_DOWN}{SONG.id}" class="down"></a>
					<a href="{SONG.url_listen}" class="play"></a>
				</div>
				<!-- BEGIN: hit -->
				<div  style="margin:8px 5px -20px -30px;float:right" class="hitsong"></div>
				<!-- END: hit -->
				<a href="{SONG.url_listen}" class="title" title="{SONG.name}">{SONG.name}</a>
				<p>{LANG.show}: <a href="{SONG.url_search_singer}">{SONG.singer}</a> | {LANG.upload}: <a href="{url_search_upload}" title="{SONG..upload}">{SONG.upload}</a></p>
				<p>{LANG.category_2}: <a href="{SONG.url_search_category}">{SONG.category}</a> | {LANG.view}: {SONG.view} | {SONG.bitrate}kb/s | {SONG.duration} | {SONG.size} MB</p>
			</div>
			<!-- BEGIN: sub -->
				<div id="subsearch">
				<!-- BEGIN: video -->
					<h4><strong>{LANG.resuitvideo}</strong></h4>
					<!-- BEGIN: loop -->
						<div style="margin-left:18px" class="videoitem">
							<a href="{VIDEO.videoview}" title="{VIDEO.videoname}"><img width="128" height="72" src="{VIDEO.thumb}" /></a><div style="height:6px;">&nbsp;</div>
							<a href="{VIDEO.videoview}" title="{VIDEO.videoname}">{VIDEO.videoname}</a><br />
							<a href="{VIDEO.s_video}" class="frontmin" >{VIDEO.videosinger}</a>
						</div>
					<!-- END: loop -->
					<div class="clear"></div>
					<p style="width: 480px;" class="aright">
						<a href="{allvideo}" class="more">{LANG.view_all}...</a>
					</p>
				<!-- END: video -->
				<!-- BEGIN: album -->
				<h4><strong>{LANG.resuitalbum}</strong></h4>
				<!-- BEGIN: loop -->
					<div class="salbum" style="margin-left:8px">
						<a href="{ALBUM.albumview}" title="{ALBUM.albumname}">			 
							<img class="item" src="{ALBUM.thumb}" width="90" height="90" alt="{ALBUM.albumname}" />
						</a>
						<a style="color:#000;" href="{ALBUM.albumview}" title="{ALBUM.albumname}">{ALBUM.albumname}</a><br />
						<a href="{ALBUM.url_search_singer}" title="{ALBUM.albumsinger}">{ALBUM.albumsinger}</a>
					</div>
				<!-- END: loop -->
				<div class="clear"></div>
				<p style="width: 480px;" class="aright">
					<a href="{allalbum}" class="more">{LANG.view_all}...</a>
				</p>
				<!-- END: album -->
				</div>
			<!-- END: sub -->
			<!-- END: loop -->
		</div>
		<div class="clear"></div>
	</div>
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