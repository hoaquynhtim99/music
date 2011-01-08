<!-- BEGIN: main -->
<div id="searchblock">

<div>
	<form action="{search_action}" method="post">
		<input class="txt" type="text" name="key" style="width: 306px" />
		<select name="type" style="width: 102px">	
			<option selected="selected" value="name">{LANG.search_with_name}</option>
			<option value="singer">{LANG.search_with_singer}</option>
			<option value="album">{LANG.search_with_album}</option>
			<option value="playlist">{LANG.search_playlist}</option>
		</select>
		<input type="hidden" name="block_sed" value="1" />
		<input class="sed" type="submit" value="{LANG.search}" />
	</form>
	</div>
</div>
<!-- END: main -->