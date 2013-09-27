<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" name="add_pic">
	<table class="tab1">
		<col width="200px"/>
		<tbody>
			<tr>
				<td class="strong">{LANG.album_name}</td>
				<td>
					<input id="idtitle" name="tenthat" class="music-input txt-half" value="{DATA.tname}" type="text"/>
					<img alt="Select" onclick="get_alias('idtitle','res_get_alias');" class="middle" width="16" src="{NV_BASE_SITEURL}images/refresh.png"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="strong">{LANG.song_name_short}</td>
				<td><input id="idalias" name="ten" class="music-input txt-half" value="{DATA.name}" type="text" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="strong">{LANG.singer}</td>
				<td></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="strong">{LANG.singer_new}	</td>
				<td><input name="casimoi" class="music-input txt-half" type="text" /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="strong">{LANG.thumb}</td>
				<td>
					<input id="thumb" name="thumb" class="music-input txt-half" value="{DATA.thumb}" type="text" />
					<input name="select" type="button" value="{LANG.select}" class="music-button-2"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="3">
					<p><strong>{LANG.describle}</strong></p>
					{DATA.describe}
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="strong">
					{LANG.content_list}<br />
					<input type="hidden" name="listsong" value="{LISTSONG}"/>
					<a href="javascript:void(0);" id="selectsongtoadd">{LANG.album_add_list_song}</a><br />
					<a href="javascript:void(0);" id="addasong">{LANG.album_add_a_song}</a>
				</td>
				<td>
					<ul id="listsong-area" class="fixbg list_song">
						<!-- BEGIN: song -->
						<li class="{SONG.id}">{SONG.title}<span onclick="nv_del_song_fromalbum({SONG.id})" class="delete-icon">&nbsp;</span></li>
						<!-- END: song -->
					</ul>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input class="music-button" name="submit" value="{LANG.save}" type="submit"></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("input[name=select]").click(function(){
		nv_open_browse_file('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=upload&popup=1&area=thumb&path={IMG_DIR}&type=image', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
		return false;
	});
	
	<!-- BEGIN: auto_get_alias -->
	$("#idtitle").change(function(){
		get_alias('idtitle', 'res_get_alias');
	});
	<!-- END: auto_get_alias -->
	
	$( "#listsong-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_soft_song(); }
	});
	$( "#listsong-area" ).disableSelection();
	
	// Them nhieu bai hat
	$("a#selectsongtoadd").click(function(){
		var songlist = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=findsongtoalbum&songlist=" + songlist, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	
	// Them mot bai hat
	$("a#addasong").click(function(){
		var songlist = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=findasongtoalbum&songlist=" + songlist, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
</script>
<!-- END: main -->