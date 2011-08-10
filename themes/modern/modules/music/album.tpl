<!-- BEGIN: main -->
<div id="searchcontent">
	<div class="box-border m-bottom"> 
		<div class="header-block1"> 
			<h3>
				{LANG.all_album}
				<span {active_1}><a href="{GDATA.hot}">{LANG.album_hotest}</a></span>
				<span {active_2}><a href="{GDATA.new}">{LANG.album_newest}</a></span>
			</h3> 
		</div> 
		<h2 class="album">{LANG.there_are} {GDATA.num} album</h2>
		<!-- BEGIN: loop -->
		<div class="resuirt">
			<a href="{ROW.url_listen}" class="namealbum" title="{ROW.name}">
				<img alt="{ROW.name}" class="album" width="90" height="90" style="border-width:0px" src="{ROW.thumb}" />
			</a>
			<div class="album">
				<p style="width: 350px;"><a href="{ROW.url_listen}" title="{ROW.name}"><strong>{ROW.name}</strong></a></p>
				<p>{LANG.show_1}: <a title="{ROW.name}" href="{ROW.url_search_singer}">{ROW.singer}</a></p>
				<p>{LANG.who_create_1}: <a title="{ROW.upload}" href="{ROW.url_search_upload}">{ROW.upload}</a> | {LANG.view}: {ROW.view}</p>
			</div>
			<div class="boder"></div>
		</div>
		<!-- END: loop -->
	<div class="clear"></div>
	</div>
</div>
<!-- END: main -->