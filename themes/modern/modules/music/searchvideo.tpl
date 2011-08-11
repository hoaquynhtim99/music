<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>VIDEO</strong>
		</div>
	</div>
	<p>&nbsp;&nbsp;{LANG.search_find} {GDATA.num} video</p>
	<!-- BEGIN: loop -->
	<div class="videos">
		<img class="videoitem" width="128px" height="72px" style="border-width:0px" src="{ROW.thumb}" alt="" />
		<p>&nbsp;&nbsp;<strong><a href="{ROW.url_listen}">{ROW.name}</a></strong><br />
		&nbsp;&nbsp;{LANG.show}: <a href="{ROW.url_search_singer}">{ROW.singer}</a><br />
		&nbsp;&nbsp;{LANG.playlist_creat}: {ROW.creat} | {LANG.view1}: {ROW.view}</p>
	<div class="clear"></div>
	</div>
	<!-- END: loop -->
	<div class="clear"></div>
</div>
<!-- END: main -->