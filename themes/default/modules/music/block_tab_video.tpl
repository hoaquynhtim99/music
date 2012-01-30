<!-- BEGIN: main -->
<script type="text/javascript">
function nv_tool_music_main_tabvideo(cid){
	if( $('#music-bltabvideo-tabs-'+cid).attr('class') == 'boldcolor' ) return;
	$('#music-bltabvideo-tabs a').removeClass('boldcolor');
	nv_music_block_tabvideo_show_loader(1);
	$('#music-bltabvideo-load').load(
		'{URL_LOAD}'+cid,
		function(){
			$('#music-bltabvideo-tabs-'+cid).addClass('boldcolor');
			nv_music_block_tabvideo_show_loader(0);
		}
	);
}
function nv_music_block_tabvideo_show_loader(show){
	if( show == 1 ){
		$('#music-bltabvideo-tabs').append('<span class="nv-loader">&nbsp;</span>');
	}else{
		$('#music-bltabvideo-tabs .nv-loader').remove();
	}
}
</script>
<div class="alboxw">
	<div class="alwrap">
		<div id="music-bltabvideo-tabs" class="alheader"> 
			<a id="music-bltabvideo-tabs-1" onclick="nv_tool_music_main_tabvideo(1);" class="boldcolor" href="javascript:void(0);">{LANG.new_video}</a>
			&nbsp;|&nbsp;
			<a id="music-bltabvideo-tabs-2" onclick="nv_tool_music_main_tabvideo(2);" href="javascript:void(0);">{LANG.hot_video}</a>
		</div>
		<div id="music-bltabvideo-load">
			<!-- BEGIN: data -->
				<div class="clear"></div><br />
				<!-- BEGIN: loop -->
				<div class="gv-wrap" style="width:{WIDTH_ITEM}%">
					<div class="vcontent">
						<a href="{ROW.url_view}" title="{ROW.tname}"><img alt="{ROW.stname}" class="musicsmalllalbum" src="{ROW.thumb}" width="{CONFIG.width}" height="{CONFIG.height}"/></a>
						<a href="{ROW.url_view}" title="{ROW.tname}">{ROW.stname}</a><br />
						<a class="singer" href="{ROW.url_search_singer}" title="{ROW.tenthat}">{ROW.stenthat}</a>
					</div>
				</div>
				<!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
				<!-- END: loop -->
			<div class="clear"></div>
			<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{URL_ALL}" >&nbsp;{LANG.view_all}</a></p>
			<!-- END: data -->
		</div>
	</div>
</div>
<!-- END: main -->