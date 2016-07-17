<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col width="150"/>
		<tbody>
			<tr>
	            <td>{LANG.addFromOtherSite_site}</td>
	            <td>
					<select class="form-control" name="site">
						<option value="nhaccuatui">http://nhaccuatui.com/</option>
						<option value="zing">http://mp3.zing.vn/</option>
						<option value="nhacso">http://nhacso.net/</option>
						<option value="nhacvui">http://hcm.nhac.vui.vn/</option>
					</select>
				</td>
			</tr>
			<tr>
	            <td>{LANG.hot_album_add}</td>
	            <td>
					<input class="form-control" style="width:350px;" type="text" name="album" id="album" value="" readonly="readonly" />
					<input type="button" name="selectalbum" value="{LANG.select}"/>
				</td>
			</tr>
			<tr>
				<td>{LANG.select_category}</td>
	            <td>
					<select class="form-control" name="theloai">
						<!-- BEGIN: catid -->
						<option value="{catid}"{selected}>{cat_title}</option>
						<!-- END: catid -->
					</select>
	            </td>
			</tr>
			<tr>
				<td>{LANG.category_sub}</td>
	            <td>
					<div style="max-height:250px;overflow:auto">
						<!-- BEGIN: listcat -->
						<input name="listcat[]" type="checkbox" value="{catid}" />{cat_title}<br />
						<!-- END: listcat -->
					</div>
	            </td>
			</tr>
			<tr>
				<td class="atop">
					{LANG.addFromOtherSite_listSong}<br />
					<strong>[<a href="javascript:void(0);" id="aaddlink">{LANG.song_add}</a>]</strong>
				</td>
	            <td>
					<div id="listsong">
	                <input class="form-control" style="width:600px;" type="text" name="song[]" value="" />
					</div>
	            </td>
			</tr>
			<tr>
	            <td style="text-align:center" colspan="2">
					<input name="addsong" type="button" value="{LANG.song_add}" />
					<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	            </td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<script type="text/javascript">
$('input[name=addsong], #aaddlink').click( function(){
	$('#listsong').append('<input class="form-control" style="width:600px;" type="text" name="song[]" value="" />');
});
</script>
<script type="text/javascript">
$("input[name=selectalbum]").click( function(){
	nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getalbumid&area=album", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	return false;
});
</script>
<!-- END: main -->