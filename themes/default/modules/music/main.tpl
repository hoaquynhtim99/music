<!-- BEGIN: main -->
<script type="text/javascript">
function nv_tool_music_main_tabalbum(cid){
	if( $('#music-main-tabs-'+cid).attr('class') == 'boldcolor' ) return;
	$('#music-main-tabs a').removeClass('boldcolor');
	nv_music_main_show_loader(1);
	$('#music-main-load').load(
		'{URL_LOAD}'+cid,
		function(){
			$('#music-main-tabs-'+cid).addClass('boldcolor');
			nv_music_main_show_loader(0);
		}
	);
}
function nv_music_main_show_loader(show){
	if( show == 1 ){
		$('#music-main-tabs').append('<span class="nv-loader">&nbsp;</span>');
	}else{
		$('#music-main-tabs .nv-loader').remove();
	}
}
</script>
<div class="alboxw">
	<div class="alwrap">
		<div id="music-main-tabs" class="alheader"> 
			<!-- BEGIN: type_tab1 -->
			<a id="music-main-tabs-1" onclick="nv_tool_music_main_tabalbum(1);" class="boldcolor" href="javascript:void(0);">{LANG.album_hotest}</a>
			&nbsp;|&nbsp;
			<a id="music-main-tabs-2" onclick="nv_tool_music_main_tabalbum(2);" href="javascript:void(0);">{LANG.album_newest}</a>
			<!-- END: type_tab1 -->
			<!-- BEGIN: type_tab2 -->
			<a id="music-main-tabs-2" onclick="nv_tool_music_main_tabalbum(2);" class="boldcolor" href="javascript:void(0);">{LANG.album_newest}</a>
			&nbsp;|&nbsp;
			<a id="music-main-tabs-1" onclick="nv_tool_music_main_tabalbum(1);" href="javascript:void(0);">{LANG.album_hotest}</a>
			<!-- END: type_tab2 -->
		</div>
		<div id="music-main-load">
			<!-- BEGIN: data -->
				<!-- BEGIN: first -->
				<div class="maintopal">
					<a href="{ALBUM.url_album}" title="{ALBUM.tname} - {ALBUM.casi}">
						<img class="main-imgtop musicsmalllalbum" src="{ALBUM.thumb}" width="100" height="100" alt="{ALBUM.tname}" />
					</a>
					<h2 class="large"><a href="{ALBUM.url_album}" title="{ALBUM.tname}">{ALBUM.tname2}</a></h2>
					<p>{LANG.show}: <a href="{ALBUM.url_search_singer}" title="{ALBUM.casi}">{ALBUM.casi2}</a></p>						
					<ul class="mmainsong">
						<!-- BEGIN: song -->
						<li>
							<div class="alcontent">
								{SONG.stt}. <a class="song" href="{SONG.url}" title="{SONG.tenthat}">{SONG.tenthat1}</a>
							</div>
						</li>
						<!-- END: song -->
					</ul>
					<div class="clear"></div>
				</div>
				<!-- END: first -->
				<div class="clear"></div>
				<!-- BEGIN: old -->
				<div class="topalbum_item">
					<div class="alcontent">
						<a href="{ALBUM.url_album}" title="{ALBUM.tname} - {ALBUM.casi}">			 
							<img class="musicsmalllalbum mmimgalbum" src="{ALBUM.thumb}" width="90" height="90" alt="{ALBUM.tname}"/>
						</a>
						<div class="alcontent">
							<a href="{ALBUM.url_album}" title="{ALBUM.tname}">{ALBUM.tname1}</a><br />
							<a class="singer" href="{ALBUM.url_search_singer}" title="{ALBUM.casi}">{ALBUM.casi1}</a>
						</div>
					</div>
				</div>
				<!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
				<!-- END: old -->
			<div class="clear"></div>
			<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{DATA.url_more}" >&nbsp;{LANG.view_all}</a></p>
			<!-- END: data -->
		</div>
	</div>
</div>
<!-- END: main -->