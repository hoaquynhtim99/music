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
				<td class="strong aright">{LANG.album_name}<span class="requie"> (*)</span></td>
				<td>
					<input id="idtitle" name="tenthat" class="music-input txt-half" value="{DATA.tname}" type="text" original-title="{LANG.tip_album_title}"/>
					<img alt="Select" onclick="get_alias('idtitle','res_get_alias');" class="middle" width="16" src="{NV_BASE_SITEURL}images/refresh.png"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="strong aright">{LANG.song_name_short}<span class="requie"> (*)</span></td>
				<td><input id="idalias" name="ten" class="music-input txt-half" value="{DATA.name}" type="text" original-title="{LANG.tip_alias}"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright atop"><strong>{LANG.singer}</strong></td>
				<td>
					<input type="hidden" name="casi" value="{LISTSINGERS}"/>
					<p>
						<strong>
							<a href="javascript:void(0);" id="addonesinger" class="nounderline add-icon">{LANG.singer_add2}</a>
							<a href="javascript:void(0);" id="addlistsinger" class="nounderline list-icon">{LANG.singer_add3}</a>
							<a href="javascript:void(0);" class="nounderline note-icon tooltip" original-title="{LANG.tip_album_singer}">{LANG.info}</a>
						</strong>
					</p>
					<ul id="listsingers-area" class="fixbg list_song">
						<!-- BEGIN: singer -->
						<li class="{SINGER.id}">
							{SINGER.title}<span onclick="nv_del_item_on_list({SINGER.id}, 'listsingers-area', '{LANG.author_del_confirm}', 'casi')" class="delete-icon">&nbsp;</span>
						</li>
						<!-- END: singer -->
					</ul>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="strong aright">{LANG.singer_new}	</td>
				<td><input name="casimoi" class="music-input txt-half" type="text" original-title="{LANG.tip_new_singer}"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="strong aright">{LANG.thumb}<span class="requie"> (*)</span></td>
				<td>
					<input id="thumb" name="thumb" class="music-input txt-half" value="{DATA.thumb}" type="text" readonly="readonly"/>
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
		<tbody>
			<tr>
				<td class="aright atop"><strong>{LANG.content_list}</strong></td>
				<td>
					<input type="hidden" name="listsong" value="{LISTSONG}"/>
					<p>
						<strong>
							<a href="javascript:void(0);" id="addonesong" class="nounderline add-icon">{LANG.album_add_a_song}</a>
							<a href="javascript:void(0);" id="addlistsong" class="nounderline list-icon">{LANG.album_add_list_song}</a>
							<a href="javascript:void(0);" class="nounderline note-icon tooltip" original-title="{LANG.tip_album_song}">{LANG.info}</a>
						</strong>
					</p>
					<ul id="listsong-area" class="fixbg list_song">
						<!-- BEGIN: song -->
						<li class="{SONG.id}">
							{SONG.title}<span onclick="nv_del_item_on_list({SONG.id}, 'listsong-area', '{LANG.author_del_confirm}', 'listsong')" class="delete-icon">&nbsp;</span>
						</li>
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
	
	// Tooltip
	$('.music-input').tipsy({
		trigger: 'focus',
		gravity: 'e'
	});
	$('.tooltip').tipsy({
		trigger: 'hover',
		gravity: 's'
	});

	// Ca si
	$( "#listsingers-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listsingers-area', 'casi'); }
	});
	$( "#listsingers-area" ).disableSelection();
	$("a#addonesinger").click(function(){
		var singers = $("input[name=casi]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=singer&findOneAndReturn=1&area=listsingers-area&input=casi&singers=" + singers, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistsinger").click(function(){
		var singers = $("input[name=casi]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=singer&findListAndReturn=1&area=listsingers-area&input=casi&singers=" + singers, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	
	// Bai hat
	$( "#listsong-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listsong-area', 'listsong'); }
	});
	$( "#listsong-area" ).disableSelection();
	$("a#addonesong").click(function(){
		var listsong = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findOneAndReturn=1&area=listsong-area&input=listsong&listsong=" + listsong, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistsong").click(function(){
		var listsong = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findListAndReturn=1&area=listsong-area&input=listsong&listsong=" + listsong, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
</script>
<!-- END: main -->