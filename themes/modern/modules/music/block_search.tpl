<!-- BEGIN: main -->
<div id="searchblock">
	<form action="{search_action}" method="post">
		<input class="txt" type="text" name="key" style="width: 306px" value="{KEY}" />
		<select name="type" style="width: 102px">	
			<!-- BEGIN: type -->
			<option value="{ID}"{SELECTED}>{TITLE}</option>
			<!-- END: type -->
		</select>
		<input type="hidden" name="block_sed" value="1" />
		<input class="sed" type="submit" value="{LANG.search}" />
	</form>
</div>
<!-- END: main -->