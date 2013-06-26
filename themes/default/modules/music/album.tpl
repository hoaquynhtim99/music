<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span><a class="boldcolor{active_1}" href="{GDATA.hot}" title="{LANG.album_hotest}">{LANG.album_hotest}</a></span> - 
			<span><a class="boldcolor{active_2}" href="{GDATA.new}" title="{LANG.album_newest}">{LANG.album_newest}</a></span>
		</div>
		<div class="alcontent">
			<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.there_are} {GDATA.num} album <a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
			<!-- BEGIN: loop -->
				<a href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}">
					<img alt="{ROW.name}" class="musicsmalllalbum fl" width="90" height="90" src="{ROW.thumb}" />
				</a>
				<a class="singer" href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}"><strong>{ROW.name}</strong></a> - <a class="singer" title="{ROW.name}" href="{ROW.url_search_singer}"><strong>{ROW.singer}</strong></a>
				<p>{LANG.who_create_1}: <a class="singer" title="{ROW.upload}" href="{ROW.url_search_upload}">{ROW.upload}</a> | {LANG.view}: {ROW.view}
				<!-- BEGIN: hit -->&nbsp;&nbsp;<span class="musicicon mhit miconiblock">&nbsp;&nbsp;&nbsp;</span><!-- END: hit --></p>
				{ROW.describe}
				<div class="clear"></div>
				<div class="hr"></div>
			<!-- END: loop -->
		</div>
	</div>
</div>
<!-- END: main -->