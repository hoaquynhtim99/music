<!-- BEGIN: main -->
<div id="searchblock">
	<div class="search_info">
		{LANG.search_info}.
	<form action="{search_action}" method="post">
		<select name="type" style="width: 102px">
		
		<option selected="selected" value="name">{LANG.search_with_name}</option>
		<option value="singer">{LANG.search_with_singer}</option>
		<option value="album">{LANG.search_with_album}</option>
		
		</select>
		<input class="txt" type="text" name="key" style="width: 136px" />
		<input type="hidden" name="block_sed" value="1" />
		<input class="sed" type="submit" value="GO" />
	</form>
	</div>
</div>
<!-- END: main -->