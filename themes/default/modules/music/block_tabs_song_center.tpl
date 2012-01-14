<!-- BEGIN: main -->
<div class="clear"></div>
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader" id="load-area"><span>{LANG.newest_song}</span></div>
		<!-- BEGIN: catdata -->
		<ul id="new-song-block-tab" class="mreset mbltabsong">
			<!-- BEGIN: loop -->
			<li style="width:{ITEM_WIDTH}%"><a id="tabssong-block-{CAT.id}" href="javascript:void(0);" onclick="nv_tool_music_tab({CAT.id});" title="{CAT.title}">{CAT.title}</a></li> 
			<!-- END: loop -->
		</ul>
		<div class="clear"></div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('#new-song-block-tab li:first a').addClass('active');
		});
		function nv_tool_music_tab(cid){
			if( $('#tabssong-block-'+cid).attr('class') == 'active' ) return;
			$('#new-song-block-tab li a').removeClass('active');
			nv_showloader_tabmusic(1);
			$('#tabssongarea').load(
				'{LOAD_URL}'+cid,
				function(){
					$('#tabssong-block-'+cid).addClass('active');
					nv_showloader_tabmusic(0);
				}
			);
		}
		function nv_showloader_tabmusic(show){
			if( show == 1 ){
				$('#load-area').append('<span class="nv-loader">&nbsp;</span>');
			}
			else{
				$('#load-area .nv-loader').remove();
			}
		}
		</script>
		<!-- END: catdata -->
		<div id="tabssongarea" class="alcontent">
			<!-- BEGIN: songdata -->
			<!-- BEGIN: loop -->
			<ul class="mtool">
				<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{ID}" class="madd">&nbsp;</a></li>
				<li><a title="{LANG.down_song}" href="{URL_DOWN}{ID}" class="mdown">&nbsp;</a></li>
			</ul>
			<a class="musicicon mplay" title="{name}" href="{url_view}"><strong>{name}</strong></a><br />
			{LANG.show}: <a href="{url_search_singer}" title="{singer}" class="singer">{singer}</a><br />
			{LANG.upload}: <a class="singer" href="{url_search_upload}" title="{who_upload}">{who_upload}</a> | {LANG.category_2}: <a class="singer" href="{url_search_category}" title="{category}">{category}</a> | {LANG.view}:	{view}
			<div class="hr"></div>
			<!-- END: loop -->
			<script type="text/javascript">$(document).ready(function(){$("ul.mtool a.madd").click(function(){$(this).removeClass("madd").addClass("madded");addplaylist($(this).attr("name"));});});</script>
			<!-- END: songdata -->
			<div class="clear"></div>
		</div>
		<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{ALL_NEW_SONG}" >&nbsp;{LANG.view_all}</a></p>
	</div>
</div>
<div class="clear"></div>
<!-- END: main -->