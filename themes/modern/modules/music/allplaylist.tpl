<!-- BEGIN: main -->
<div id="searchcontent">
	<div class="box-border m-bottom"> 
		<div class="header-block1"> 
			<h3>
				{LANG.playlist_all}
				<span {active_1}><a href="{GDATA.hot}">{LANG.playlist_hotest}</a></span>
				<span {active_2}><a href="{GDATA.new}">{LANG.playlist_newest}</a></span>
			</h3> 
		</div> 
		<h2 class="album">{LANG.there_are} {GDATA.num} playlist</h2>
		<!-- BEGIN: loop -->
		<div class="resuirt">
			<a href="{ROW.url_listen}" class="namealbum" title="{ROW.name}">
				<img class="album" width="90" height="90" style="border-width:0px" src="{ROW.thumb}" />
			</a>
			<div class="album">
				<p><a href="{ROW.url_listen}" title="{ROW.name}"><strong>{ROW.name}</strong></a></p>
				<p>{LANG.show_1}: <a>{ROW.singer}</a></p>
				<p>{LANG.who_create_1}: <a href="{ROW.url_search_upload}">{ROW.upload}</a> | {LANG.view}: {ROW.view}</p>
			</div>
			<div class="boder"></div>
		</div>
		<!-- END: loop -->
		<div class="clear"></div>
	</div>
</div>
<!-- END: main -->