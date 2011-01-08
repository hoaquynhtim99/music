<!-- BEGIN: main -->
 <div id="searchcontent">
 	<h1>{LANG.all_album}
  		<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>	
		<span {active_2}><a href="{hot}">Album hot nhất</a></span>
 		<span {active_1}><a href="{new}">Album mới nhất</a></span>
 	</h1>
 	<h2 class="album">{LANG.there_are} {num} album</h2>
	<!-- BEGIN: loop -->
	<div class="resuirt">
 		<a href="{url_listen}" class="namealbum">
 		<img width="100px" height="100px" border="0" src="{thumb}" />
		<div id="album">
 			<strong>{name}</strong></a>
 			<p>{LANG.show_1}: <a href="{url_search_singer}">{singer}</a></p>
 			<p>{LANG.who_create_1}: <a href="{url_search_upload}">{upload}</a> | {LANG.view}: {view}</p>
 		</div>
	 	<div id="boder"></div>
 	</div>
	<!-- END: loop -->
 </div>
<!-- END: main -->