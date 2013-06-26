<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
<table class="tab1">
	<tbody>
		<tr>
            <td style="width:150px">
				{LANG.hot_album_add}
            </td>
            <td>
				<input style="width:350px;" type="text" name="album" id="album" value="" readonly="readonly" />
				<input type="button" name="selectalbum" value="{LANG.select}"/>
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
				{LANG.category_sub}
			</td>
            <td>
				<div style="max-height:250px;overflow:auto">
					<!-- BEGIN: listcat -->
					<input name="listcat[]" type="checkbox" value="{catid}" />{cat_title}<br />
					<!-- END: listcat -->
				</div>
            </td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td class="atop">
				{LANG.nct_list_song}<br />
				<strong>[<a href="javascript:void(0);" id="aaddlink">{LANG.song_add}</a>]</strong>
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
$('input[name=addsong], #aaddlink').click( function()
{
	$('#listsong').append('<input style="width:600px;" type="text" name="song[]" value="" />');
});
</script>
<script type="text/javascript">
	$("input[name=selectalbum]").click( function(){
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getalbumid&area=album", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
</script>
<!-- END: main -->