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
			<!-- BEGIN: loop -->
			<a class="musicicon mplay" title="{name}" href="{url_view}"><strong>{name}</strong></a>
			<div class="tool">
				<a name="{ID}" class="adds add"></a>
				<a href="{URL_DOWN}{ID}" class="down"></a>
				<a href="{url_view}" class="play"></a>
			</div>
			{LANG.show}: <a href="{url_search_singer}" title="{singer}" class="singer">{singer}</a><br />
			{LANG.upload}: <a class="singer" href="{url_search_upload}" title="{who_upload}">{who_upload}</a> | {LANG.category_2}: <a class="singer" href="{url_search_category}" title="{category}">{category}</a> | {LANG.view}:	{view}
			<div class="hr"></div>
			<!-- END: loop -->
			<!-- END: songdata -->
			<div class="clear"></div>
			<p class="alright alcontent"><a class="musicicon mforward" title="{LANG.view_all}" href="{ALL_NEW_SONG}" >&nbsp;{LANG.view_all}</a></p>
		</div>
	</div>
</div>
<div class="clear"></div>
<!-- END: main -->