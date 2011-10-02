<!-- BEGIN: main -->
<script type="text/javascript">
function nv_tool_music_main_tabalbum(cid){
	if( $('#music-main-tabs-'+cid).attr('class') == 'current-cat' ) return;
	$('#music-main-tabs a').removeClass('current-cat');
	nv_music_main_show_loader(1);
	$('#music-main-load').load(
		'{URL_LOAD}'+cid,
		function(){
			$('#music-main-tabs-'+cid).addClass('current-cat');
			nv_music_main_show_loader(0);
		}
	);
}
function nv_music_main_show_loader(show){
	if( show == 1 ){
		$('#music-main-tabs').append('<span class="nv-loader main-loader">&nbsp;</span>');
	}
	else{
		$('#music-main-tabs .nv-loader').remove();
	}
}
</script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div id="music-main-tabs" class="cat-nav nv-relative"> 
			<a id="music-main-tabs-1" onclick="nv_tool_music_main_tabalbum(1);" class="current-cat" href="javascript:void(0);">{LANG.album_hotest}</a>
			<a id="music-main-tabs-2" onclick="nv_tool_music_main_tabalbum(2);" href="javascript:void(0);">{LANG.album_newest}</a>
		</div>
	</div>
	<div id="music-main-load">
		<!-- BEGIN: data -->
		<div class="tab_container">
			<div class="tab_content">
				<!-- BEGIN: first -->
				<div class="picleft">&nbsp;</div>
				<a href="{ALBUM.url_album}" title="{ALBUM.tname} - {ALBUM.casi}">
					<img class="first" src="{ALBUM.thumb}" width="90" height="90" alt="{ALBUM.tname}" />
				</a>
				<div class="picright">
					<h2><a href="{ALBUM.url_album}" title="{ALBUM.tname}">{ALBUM.tname}</a></h2>
					<p>{LANG.show}: <a href="{ALBUM.url_search_singer}" title="{ALBUM.casi}">{ALBUM.casi}</a></p>
				</div>
				<div class="first_a_song">
					<!-- BEGIN: song -->
					<a href="{SONG.url}" title="{SONG.tenthat}">{SONG.stt}. {SONG.tenthat1}</a>
					<!-- END: song -->
					<div class="clear"></div>
					<a class="listenall" href="{ALBUM.url_album}" title="{ALBUM.tname} - {ALBUM.casi}">{LANG.listen_all_album}</a>
				</div>
				<!-- END: first -->
				<div class="clear"></div>
				<!-- BEGIN: old -->
				<div class="topalbum_item">
				   <a href="{ALBUM.url_album}" title="{ALBUM.tname} - {ALBUM.casi}">			 
					   <img class="item" src="{ALBUM.thumb}" width="90" height="90" alt="{ALBUM.tname}"/>
				   </a>
				   <a style="color:#000;" href="{ALBUM.url_album}" title="{ALBUM.tname}">{ALBUM.tname}</a>
				   <a href="{ALBUM.url_search_singer}" title="{ALBUM.casi}">{ALBUM.casi}</a>
				</div>
				<!-- END: old -->
			</div> 
		</div>
		<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{DATA.url_more}" >» {LANG.view_all}</a>
		<div class="clear"></div>
		<!-- END: data -->
	</div>
</div>
<!-- END: main -->