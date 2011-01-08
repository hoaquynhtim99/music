<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
<div class="cat-box-header"> 
<div class="cat-nav"> 
<a class="albumtop" href="#tab1">{LANG.album_hotest}</a></li>
<a class="albumtop" href="#tab2">{LANG.album_newest}</a></li>
</div>
</div>
<div class="tab_container">
	<!-- BEGIN: hotalbum -->
	<div id="tab1" class="tab_content">
		<!-- BEGIN: first -->
	   <div class="picleft">&nbsp;</div>
	   <a href="{url_album}" title="{faname} - {fasinger}">
		   <img border="0" class="first" src="{fapic}" width="90" height="90" />
	   </a>
	   <div class="picright">
		   	<a href="{url_album}" title="{faname}"><h2>{faname}</h2></a>
		   	<p>{LANG.show}: <a href="{url_search_singer}" title="{fasinger}">{fasinger}</a></p>
	   </div>
	   <div class="first_a_song">
			<!-- BEGIN: song -->
		   	<a href="{url}" title="{name}">{STT}. {name}</a>
			<!-- END: song -->
		   	<a class="listenall" href="{url_album}" title="{faname} - {fasinger}">{LANG.listen_all_album}</a>
	   </div>
		<!-- END: first -->
	   <!-- BEGIN: old -->
	   <div class="topalbum_item">
		   <a href="{url_album}" title="{albumtitle}">			 
			   <img border="0" class="item" src="{albumpic}" width="90" height="90" />
		   </a>
		   <a style="color:#000;" href="{url_album}" title="{albumtitle}">{albumtitle}</a>
		   <a href="{url_search_singer}" title="{singer}">{singer}</a>
	   </div>
	   <!-- END: old -->
	</div> 
	<!-- END: hotalbum -->
	<!-- BEGIN: topalbum -->
	<div id="tab2" class="tab_content">
		<!-- BEGIN: firstn -->
	   <div class="picleft">&nbsp;</div>
	   <a href="{url_album}">
		   <img border="0" class="first" src="{pic}" width="90" height="90" />
	   </a>
	   <div class="picright">
		   	<a href="{url_album}" title="{name}"><h2>{name}</h2></a>
		   	<p>{LANG.show}: <a href="{url_search_singer}" title="#">{singer}</a></p>
	   </div>
	   <div class="first_a_song">
			<!-- BEGIN: song -->
		   	<a href="{url}" title="{sname}">{STT}. {sname}</a>
			<!-- END: song -->
		   	<a class="listenall" href="{url_album}" title="{LANG.listen_all_album}">{LANG.listen_all_album}</a>
	   </div>
		<!-- END: firstn -->
	   <!-- BEGIN: old -->
	   <div class="topalbum_item">
		   <a href="{url_album}" title="{albumtitle}">			 
			   <img border="0" class="item" src="{albumpic}" width="90" height="90" />
		   </a>
		   <a style="color:#000;" href="{url_album}" title="{albumtitle}">{albumtitle}</a>
		   <a href="{url_search_singer}" title="#">{singer}</a>
	   </div>
	   <!-- END: old -->
	</div> 
	<!-- END: topalbum -->
</div>
<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{allalbum}" >» {LANG.view_all}</a>
<div class="clear"> </div> 
</div>

<!-- BEGIN: oldsong -->
<div class="box-border-shadow m-bottom">
<div class="cat-box-header"> 
<div class="cat-nav">  
<strong>{LANG.newest_song}</strong> 
</div>
</div>
<ul id="casong" class="list-tab top-option clearfix"> 
<li><a href="#topsong1">{f1}</a></li> 
<li><a href="#topsong2">{f2}</a></li> 
<li><a href="#topsong3">{f3}</a></li> 
<li><a href="#topsong4">{f4}</a></li> 
<li></li> 
</ul> 
<div class="topsong_container">
	<!-- BEGIN: topsong -->
	<div id="topsong{DIV}" class="topsong_content">
		<!-- BEGIN: loop -->
		<div class="songitem">
			<a class="songname" title="{name}" href="{url_view}">{name}</a>
			<div class="tool">
				<a name="{ID}" id="add" class="add"></a>
				<a href="{URL_DOWN}{ID}" target="_blank" class="down"></a>
				<a href="{url_view}" id="play" class="play"></a>
			</div>
			<p>
				{LANG.show}: <a href="{url_search_singer}">{singer}</a><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{LANG.upload}: 
				<a href="{url_search_upload}">{who_upload}</a> | 
				<a href="{url_search_category}">{category}</a> | {LANG.view}:	{view}	
			</p>
		</div>
		<!-- END: loop -->
		<p>&nbsp;</p>
	</div>
	<!-- END: topsong -->
</div>
<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{allsong}">» {LANG.view_all}</a>
<div class="clear"></div>
</div>
<!-- END: oldsong -->
<script type="text/javascript">
$(document).ready(function() {
	$(".topsong_content").hide(); 
	$("ul#casong li:first").addClass("ui-tabs-selected").show(); 
	$(".topsong_content:first").show(); 
	$("ul#casong li").click(function() {
		$("ul#casong li").removeClass("ui-tabs-selected"); 
		$(this).addClass("ui-tabs-selected"); 
		$(".topsong_content").hide(); 
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).fadeIn(); 
		return false;
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide();
	$("a.albumtop:first").addClass("current-cat").show();
	$(".tab_content:first").show();
	$("a.albumtop").click(function() {
		$("a.albumtop").removeClass("current-cat"); 
		$(this).addClass("current-cat"); 
		$(".tab_content").hide(); 
		var activeTab = $(this).attr("href"); 
		$(activeTab).fadeIn(); 
		return false;
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$("a#add").click(function() {
		$(this).removeClass("add"); 
		$(this).addClass("addedtolist"); 
		var songid = $(this).attr("name");
		addplaylist(songid);
	});
});
</script>
<!-- END: main -->