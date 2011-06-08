<!-- BEGIN: main -->
 <div id="searchcontent">
 <div class="box-border m-bottom"> 
 <div class="header-block1"> 
 <h3>{LANG.all_song}
 <span {active_1}><a href="{hot}">{LANG.hotest_song}</a></span>
 <span {active_2}><a href="{new}">{LANG.newset_song}</a></span></h3> 
 </div> 
 	<h2 class="album">{LANG.there_are} {num} {LANG.song}</h2>
	<div class="topsong_container">
	<div class="topsong_content">
	<!-- BEGIN: loop -->
		<div class="songitem">
			<div class="tool">
				<a name="{ID}" class="adds add"></a>
				<a href="{URL_DOWN}{ID}" class="down"></a>
				<a href="{url_view}" id="play" class="play"></a>
			</div>
			<!-- BEGIN: hit -->
			<div  style="margin:8px -10px -20px -30px;float:right" class="hitsong"></div>
			<!-- END: hit -->
			<a class="songname" title="{name}" href="{url_view}">{name}</a>
			<p>
				{LANG.show}: <a class="singer" href="{url_search_singer}">{singer}</a> | {LANG.upload}: <a class="singer" href="{url_search_upload}">{upload}</a><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<a class="singer" href="{url_search_category}">{category}</a> | {LANG.view}: {view}	| {bitrate}kb/s | {duration} | {size} MB
			</p>
		</div>
	<!-- END: loop -->
	</div>
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