<!-- BEGIN: main -->
<div id="searchcontent">
	<h1>{LANG.search_song}</h1>
	<div class="resuirt">
		<h2>{LANG.search_find} {num} {LANG.results}.</h2><br />
		<!-- BEGIN: loop -->
		<div class="itemsearch {gray}">
			<div class="tool">
				<a onclick="addplaylist('{ID}');" id="add" class="add"></a>
				<a target="_blank" href="{link}" id="down" class="down"></a>
				<a href="{url_listen}" id="play" class="play"></a>
			</div>
			<a href="{url_listen}" class="title">{name}</a>
			<p>{LANG.show}: <a href="{url_search_singer}">{singer}</a> | {LANG.upload}: <a href="{url_search_upload}">{upload}</a></p>
			<p>{LANG.category_2}: <a href="{url_search_category}">{category}</a> | {LANG.view}: {view}</p>
		</div>
		<!-- END: loop -->
	</div>
</div>
<!-- END: main -->