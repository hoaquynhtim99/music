<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.hot_singer}</strong>
		</div>
	</div>
	<div id="hotsinger">
		<!-- BEGIN: top -->
		<div id="hotsg{TOPSTT}" class="toph">
			<a href="{url_album}" title="{topname}"><img  style="border-width:0px" width="446" height="132" alt="{topname}" src="{large_thumb}" /></a>
			<p><strong>{LANG.songof} <a href="{url_search_singer}" title="{topname}">{topname}</a> {LANG.show_2}</strong></p>
			<!-- BEGIN: song -->
			<a title="{songname}" href="{url_song}" class="hssong {left} {end}">{songname}</a>		
			<!-- END: song -->
			<p align="right" style="margin:4px 4px 4px 0px;float:right;"><a title="{LANG.listen_all_song_of}" href="{url_album}">{LANG.listen_all_song_of}</a></p>	
		</div>
		<!-- END: top -->
		<div class="bottom">
			<!-- BEGIN: bottom -->
			<div class="itemhs">
				<a class="hotsg" href="#hotsg{STT}"><img width="114x" height="72px" src="{thumb}" border="0px" />
				{name}</a>
			</div>
			<!-- END: bottom -->
		</div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(".toph").hide();
	$(".toph:first").show();
	$(".hotsg").click(function() {
		$(".toph").hide();
		var activeTab = $(this).attr("href");
		$(activeTab).fadeIn();
		return false;
	});
});
</script>
<!-- END: main -->
