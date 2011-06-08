<!-- BEGIN: main -->
<div id="searchcontent">
	<div class="box-border m-bottom"> 
		<div class="header-block1"> 
			<h3><span>{LANG.search_song}</span></h3> 
		</div> 
		<div class="resuirt">
			<p><strong>{LANG.search_find} {num} {LANG.results}.</strong></p>
			<!-- BEGIN: loop -->
			<div class="itemsearch {gray}">
				<div class="tool">
					<a name="{ID}" class="adds add"></a>
					<a href="{URL_DOWN}{ID}" class="down"></a>
					<a href="{url_listen}" class="play"></a>
				</div>
				<!-- BEGIN: hit -->
				<div  style="margin:8px 5px -20px -30px;float:right" class="hitsong"></div>
				<!-- END: hit -->
				<a href="{url_listen}" class="title">{name}</a>
				<p>{LANG.show}: <a href="{url_search_singer}">{singer}</a> | {LANG.upload}: <a href="{url_search_upload}">{upload}</a></p>
				<p>{LANG.category_2}: <a href="{url_search_category}">{category}</a> | {LANG.view}: {view} | {bitrate}kb/s | {duration} | {size} MB</p>
			</div>
			<!-- BEGIN: sub -->
				<div id="subsearch">
				<!-- BEGIN: video -->
					<h4><strong>{LANG.resuitvideo}</strong></h4>
					<!-- BEGIN: loop -->
						<div style="margin-left:18px" id="videoitem">
							<a href="{videoview}"><img width="128px" height="72px" src="{thumb}" /><div style="height:6px;">&nbsp;</div>
							{videoname}</a><br />
							<a href="{s_video}" class="frontmin" >{videosinger}</a>
						</div>
					<!-- END: loop -->
					<div class="clear"></div>
					<p style="width: 480px;" class="aright">
						<a href="{allvideo}" class="more">{LANG.view_all}...</a>
					</p>
				<!-- END: video -->
				<!-- BEGIN: album -->
				<h4><strong>{LANG.resuitalbum}</strong></h4>
				<!-- BEGIN: loop -->
				   <div style="margin-left:8px" id="salbum">
					   <a href="{albumview}" title="{albumname}">			 
						   <img class="item" src="{thumb}" width="90" height="90" alt="" />
					   </a>
					   <a style="color:#000;" href="{albumview}" title="{albumname}">{albumname}</a><br />
					   <a href="{url_search_singer}" title="{albumsinger}">{albumsinger}</a>
				   </div>
				<!-- END: loop -->
				<div class="clear"></div>
				<p style="width: 480px;" class="aright">
					<a href="{allalbum}" class="more">{LANG.view_all}...</a>
				</p>
				<!-- END: album -->
				</div>
			<!-- END: sub -->
			<!-- END: loop -->
		</div>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("a.adds").click(function() {
		$(this).removeClass("add"); 
		$(this).addClass("addedtolist"); 
		var songid = $(this).attr("name");
		addplaylist(songid);
	});
});
</script>
<!-- END: main -->