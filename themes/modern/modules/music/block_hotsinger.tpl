<!-- BEGIN: main -->
<div id="topsong_head">
	<h2>{LANG.hot_singer}</h2>
</div>
<div id="hotsinger">
	<!-- BEGIN: top -->
	<div id="hotsg{TOPSTT}" class="toph">
		<img width="450px" height="135px" alt="{topname}" src="{large_thumb}" />
		<p><strong>{LANG.songof} <a href="{url_search_singer}">{topname}</a> {LANG.show_2}</strong></p>
		<!-- BEGIN: song -->
		<a href="{url_song}" class="hssong {left} {end}">{songname}</a>		
		<!-- END: song -->
		<p align="right" style="margin:4px 4px 4px 0px;float:right;"><a href="{url_album}">{LANG.listen_all_song_of}</a></p>	
	</div>
	<!-- END: top -->
	<div class="bottom">
		<!-- BEGIN: bottom -->
		<div class="itemhs">
			<img width="119px" height="84px" src="{thumb}" border="0px" />
			<a class="hotsg" href="#hotsg{STT}">{name}</a>
		</div>
		<!-- END: bottom -->
	</div>
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
