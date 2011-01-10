<!-- BEGIN: main -->
<ul class="tabs">
	<li><a href="#tab1">{LANG.album_hotest}</a></li>
	<li><a href="#tab2">{LANG.album_newest}</a></li>
</ul>
<div class="tab_container">
	<!-- BEGIN: hotalbum -->
	<div id="tab1" class="tab_content">
		<!-- BEGIN: first -->
	   <div class="picleft">&nbsp;</div>
	   <a href="{url_album}" title="{faname} - {fasinger}">
		   <img class="first" src="{fapic}" width="100" height="100" />
	   </a>
	   <div class="picright">
		   	<a href="{url_album}" title="{faname}"><span>{faname}</span></a>
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
			   <img class="item" src="{albumpic}" width="100" height="100" />
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
		   <img class="first" src="{pic}" width="100" height="100" />
	   </a>
	   <div class="picright">
		   	<a href="{url_album}" title="{name}"><span>{name}</span></a>
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
			   <img  class="item" src="{albumpic}" width="100" height="100" />
		   </a>
		   <a style="color:#000;" href="{url_album}" title="{albumtitle}">{albumtitle}</a>
		   <a href="{url_search_singer}" title="#">{singer}</a>
	   </div>
	   <!-- END: old -->
	</div> 
	<!-- END: topalbum -->
</div>
<div class="topalbum_foot">
	<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{allalbum}" >» {LANG.view_all}</a>
</div>
<p>&nbsp;</p>
<!-- BEGIN: oldsong -->
<div id="topsong_head">
	<h2>{LANG.newest_song}</h2>
</div>
<ul class="topsong">
	<li><a href="#topsong1">{f1}</a></li>
	<li><a href="#topsong2">{f2}</a></li>
	<li><a href="#topsong3">{f3}</a></li>
	<li><a href="#topsong4">{f4}</a></li>
</ul>
<div class="topsong_container">
	<!-- BEGIN: topsong -->
	<div id="topsong{DIV}" class="topsong_content">
		<!-- BEGIN: loop -->
		<div class="songitem">
			<a class="songname" title="{name}" href="{url_view}">{name}</a>
			<div class="tool">
				<a onclick="addplaylist('{ID}');"  class="add"></a>
				<a target="_blank" href="{url}" class="down"></a>
				<a href="{url_view}" class="play"></a>
			</div>
			<p>
				{LANG.show}: <a class="singer" href="{url_search_singer}">{singer}</a><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{LANG.upload}: 
				<a class="singer" href="{url_search_upload}">{who_upload}</a> | 
				<a class="singer" href="{url_search_category}">{category}</a> | {LANG.view}:	{view}	
			</p>
		</div>
		<!-- END: loop -->
		<p>&nbsp;</p>
	</div>
	<!-- END: topsong -->
</div>
<div class="topalbum_foot">
	<a style="float:right;margin-bottom:5px;margin-right:10px;color:#000;" href="{allsong}">» {LANG.view_all}</a>
</div>
<!-- END: oldsong -->
<script type="text/javascript">
$(document).ready(function() {
	//When page loads...
	$(".topsong_content").hide(); //Hide all content
	$("ul.topsong li:first").addClass("active").show(); //Activate first tab
	$(".topsong_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.topsong li").click(function() {

		$("ul.topsong li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".topsong_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
</script>
<!-- END: main -->