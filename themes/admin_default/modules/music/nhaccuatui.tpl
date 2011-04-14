<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
<table class="tab1">
	<caption>{TABLE_CAPTION}</caption>
	<tbody>
		<tr>
            <td>
				{LANG.hot_album_add}
            </td>
            <td>
				<select name="album">
					<!-- BEGIN: album -->
					<option value="{album_name}"{album_selected}>{album_title}</option>
					<!-- END: album -->
				</select>
            </td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				{LANG.select_category}
			</td>
            <td>
				<select name="theloai">
					<!-- BEGIN: catid -->
					<option value="{catid}"{selected}>{cat_title}</option>
					<!-- END: catid -->
				</select>
            </td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>
				{LANG.nct_list_song}
			</td>
            <td>
				<div id="listsong">
                <input style="width:600px;" type="text" name="song[]" value="" />
				</div>
            </td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
            <td style="text-align:center" colspan="2">
				<input name="addsong" type="button" value="{LANG.song_add}" />
				<input name="submit" type="submit" value="{LANG.save}" />
            </td>
		</tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
$('input[name=addsong]').click( function()
{
	$('#listsong').append('<input style="width:600px;" type="text" name="song[]" value="" />');
});
</script>
<!-- END: main -->