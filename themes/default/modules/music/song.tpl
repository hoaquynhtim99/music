<!-- BEGIN: main -->
 <div id="searchcontent">
 	<h1>{LANG.all_song}
  		<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>	
		<span {active_2}><a href="{hot}">{LANG.hotest_song}</a></span>
 		<span {active_1}><a href="{new}">{LANG.newset_song}</a></span>
 	</h1>
 	<h2 class="album">{LANG.there_are} {num} {LANG.song}</h2>
	<div class="topsong_container">
	<div class="topsong_content">
	<!-- BEGIN: loop -->
		<div class="songitem">
			<a class="songname" title="{name}" href="{url_view}">{name}</a>
			<div class="tool">
				<a onclick="addplaylist('{ID}');" id="add" class="add"></a>
				<a target="_blank" href="{url}" id="down" class="down"></a>
				<a href="{url_view}" id="play" class="play"></a>
			</div>
			<p>
				{LANG.show}: <a class="singer" href="{url_search_singer}">{singer}</a><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{LANG.upload}: 
				<a class="singer" href="{url_search_upload}">{upload}</a> | 
				<a class="singer" href="{url_search_category}">{category}</a> | {LANG.view}:	{view}	
			</p>
		</div>
	<!-- END: loop -->
	</div>
	</div>
 </div>
<!-- END: main -->