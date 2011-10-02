<!-- BEGIN: main -->
<div class="clear"></div>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav">  
			<strong>{LANG.newest_song}</strong> 
		</div>
	</div>
	<!-- BEGIN: catdata -->
	<ul id="new-song-block-tab" class="list-tab top-option clearfix">
		<!-- BEGIN: loop -->
		<li><a id="tabssong-block-{CAT.id}" href="javascript:void(0);" onclick="nv_tool_music_tab({CAT.id});" title="{CAT.title}">{CAT.title}</a></li> 
		<!-- END: loop -->
	</ul> 
	<script type="text/javascript">
	$(document).ready(function(){
		$('#new-song-block-tab li:first').addClass('ui-tabs-selected');
	});
	function nv_tool_music_tab(cid){
		if( $('#tabssong-block-'+cid).parent('li').attr('class') == 'ui-tabs-selected' ) return;
		$('#new-song-block-tab li').removeClass('ui-tabs-selected');
		nv_showloader_tabmusic(1);
		$('#tabssongarea').load(
			'{LOAD_URL}'+cid,
			function(){
				$('#tabssong-block-'+cid).parent('li').addClass('ui-tabs-selected');
				nv_showloader_tabmusic(0);
			}
		);
	}
	function nv_showloader_tabmusic(show){
		if( show == 1 ){
			$('#new-song-block-tab').append('<li class="loader">&nbsp;</li>');
		}
		else{
			$('#new-song-block-tab li.loader').remove();
		}
	}
	</script>
	<!-- END: catdata -->
	<div class="topsong_container">
		<div id="tabssongarea" class="topsong_content">
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
			<div class="songitem">
				<a class="songname" title="{name}" href="{url_view}">{name}</a>
				<div class="tool">
					<a name="{ID}" class="adds add"></a>
					<a href="{URL_DOWN}{ID}" class="down"></a>
					<a href="{url_view}" class="play"></a>
				</div>
				<p>
					{LANG.show}: <a href="{url_search_singer}">{singer}</a><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{LANG.upload}: 
					<a href="{url_search_upload}">{who_upload}</a> | 
					<a href="{url_search_category}">{category}</a> | {LANG.view}:	{view}	
				</p>
			</div>
			<!-- END: loop -->
			<!-- END: songdata -->
			<p>&nbsp;</p>
		</div>
	</div>
	<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{ALL_NEW_SONG}" title="{LANG.view_all}">» {LANG.view_all}</a>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<!-- END: main -->