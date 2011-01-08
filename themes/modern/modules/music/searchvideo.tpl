<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>VIDEO</strong>
		</div>
	</div>
	<p>&nbsp;&nbsp;{LANG.search_find} {num} video</p>
	<!-- BEGIN: loop -->
	<div id="videos">
		<img id="videoitem" width="128px" height="72px" border="0" src="{thumb}" />
		<p>&nbsp;&nbsp;<strong><a href="{url_listen}">{name}</a></strong><br />
		&nbsp;&nbsp;{LANG.show}: <a href="{url_search_singer}">{singer}</a><br />
		&nbsp;&nbsp;{LANG.playlist_creat}: {creat} | {LANG.view1}: {view}</p>
	<div class="clear"></div>
	</div>
	<!-- END: loop -->
	<div class="clear"></div>
</div>
<!-- END: main -->