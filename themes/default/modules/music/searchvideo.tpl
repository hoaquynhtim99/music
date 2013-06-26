<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span>VIDEO</span>
		</div>
		<div class="alcontent">
			<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.search_find} {GDATA.num} video<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
			<!-- BEGIN: loop -->
			<a href="{ROW.url_listen}" title="{ROW.name}"><img class="musicsmalllalbum fl" width="128" height="72" src="{ROW.thumb}" alt="{ROW.name}" /></a>
			<strong><a href="{ROW.url_listen}" title="{ROW.name}">{ROW.name}</a></strong> <!-- BEGIN: hit -->&nbsp;&nbsp;<span class="musicicon mhit miconiblock">&nbsp;&nbsp;&nbsp;</span><!-- END: hit --><br />
			{LANG.show}: <a class="singer" href="{ROW.url_search_singer}" title="{ROW.singer}">{ROW.singer}</a><br />
			{LANG.playlist_creat}: <span class="greencolor">{ROW.creat}</span> | {LANG.view1}: <span class="greencolor">{ROW.view}</span>
			<div class="clear"></div>
			<div class="hr"></div>
			<!-- END: loop -->
		</div>
	</div>
</div>
<!-- END: main -->