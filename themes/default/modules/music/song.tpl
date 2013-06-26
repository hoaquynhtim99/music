<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span><a class="boldcolor{active_1}" href="{hot}" title="{LANG.hotest_song}">{LANG.hotest_song}</a></span> - 
			<span><a class="boldcolor{active_2}" href="{new}" title="{LANG.newset_song}">{LANG.newset_song}</a></span>
		</div>
		<div class="alcontent">
			<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.there_are} {GDATA.num} {LANG.song}<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
			<!-- BEGIN: loop -->
			<ul class="mtool">
				<!-- BEGIN: hit --><li><a title="{LANG.hit_song}" href="javascript:void(0);" class="mstar">&nbsp;</a></li><!-- END: hit -->
				<li><a title="{LANG.add_box}" href="javascript:void(0);" name="{ROW.id}" class="madd">&nbsp;</a></li>
				<li><a title="{LANG.down_song}" href="{URL_DOWN}{ROW.id}" class="mdown">&nbsp;</a></li>
			</ul>
			<a class="musicicon mplay" title="{LANG.song_edit_listen1} {ROW.name}" href="{ROW.url_view}"><strong>{ROW.name}</strong></a><br />
			{LANG.show}: <a href="{ROW.url_search_singer}" title="{LANG.search_song_by_singer} {ROW.singer}" class="singer">{ROW.singer}</a><br />
			{LANG.upload}: <a class="singer" href="{ROW.url_search_upload}" title="{LANG.search_song_by_uploader} {ROW.upload}">{ROW.upload}</a> | {LANG.category_2}: <a class="singer" href="{ROW.url_search_category}" title="{LANG.search_song_by_cat} {ROW.category}">{ROW.category}</a> | {LANG.view}:	{view} | {ROW.bitrate}kb/s | {ROW.duration} | {ROW.size} MB
			<div class="hr"></div>
			<div class="clear"></div>
			<!-- END: loop -->
		</div>
	</div>
</div>
<!-- END: main -->