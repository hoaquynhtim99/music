<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<col width="200px"/>
		<tbody>
			<tr>
				<td colspan="2"><strong class="toupper">{LANG.song_info}</strong></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.song_name}</strong></td>
				<td>
					<input type="text" class="music-input txt-half" id="idtitle" name="tenthat" value="{DATA.tenthat}"/>
					<img class="middle" width="16" height="16" alt="get" onclick="get_alias('idtitle','res_get_alias');" src="{NV_BASE_SITEURL}images/refresh.png"/>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.song_name_short}</strong></td>
				<td><input type="text" class="music-input txt-half" id="idalias" name="ten" value="{DATA.ten}"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.singer}</strong></td>
				<td></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.singer_new}</strong></td>
				<td><input type="text" class="music-input txt-half" name="casimoi" value="{DATA.casimoi}"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.author}</strong></td>
				<td></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.author_new}</strong></td>
				<td><input type="text" class="music-input txt-half" name="nhacsimoi" value="{DATA.nhacsimoi}"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.album}</strong></td>
				<td>
					<input class="music-input txt-half" name="album" id="album" type="text" readonly="readonly" value="{DATA.album}"/>
					<input class="music-button" type="button" name="selectalbum" value="{LANG.select}"/>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.category_base}</strong></td>
				<td>
					<select class="music-input" name="theloai">
						<!-- BEGIN: theloai -->
						<option value="{THELOAI.id}"{THELOAI.selected}>{THELOAI.title}</option>
						<!-- END: theloai -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.category_sub}</strong></td>
				<td>
					<div class="autoscroll">
						<!-- BEGIN: listcat -->
						<label><input type="checkbox" name="listcat[]" value="{THELOAI.id}"{THELOAI.checked}/> {THELOAI.title}</label>
						<!-- END: listcat -->
						<div class="clear"></div>
					</div>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.link}</strong></td>
				<td>
					<input type="text" class="music-input txt-half" id="duongdan" name="duongdan" value="{DATA.duongdan}"/>
					<input type="button" class="music-button-2" name="select" value="{LANG.select}" /> &raquo;
					<input id="get_info" name="get_info" type="button" class="music-button-2" value="{LANG.get_info}" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td class="aright"><strong>{LANG.bitrate} - {LANG.duration} - {LANG.size}</strong></td>
				<td>
					<input type="text" class="music-input txt-quater" name="bitrate" id="bitrate" value="{DATA.bitrate}"/> bps
					<input type="text" class="music-input txt-quater" name="duration" id="duration" value="{DATA.duration}"/> {GLANG.sec}
					<input type="text" class="music-input txt-quater" name="size" id="size" value="{DATA.size}"/> byte
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="aright"><strong>{LANG.is_official}</strong></td>
				<td><input type="checkbox" name="is_official" value="1"{DATA.is_official}/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="2"><strong class="toupper">{LANG.add_lyric}</strong></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2"><textarea class="music-input txt-full autoresize textarea-animated" name="lyric" rows="5"/>{DATA.lyric}</textarea>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="submit" name="submit" value="{LANG.save}" class="music-button"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$(function(){
	$("input[name=select]").click(function(){
		var area = "duongdan";
		var path = "{NV_UPLOADS_DIR}/" + nv_module_name + "/{SETTING.root_contain}";
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path, "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
	$("input[name=selectalbum]").click( function(){
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=getalbumid&area=album", "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
	$("#get_info").click(function(){
		getsonginfo();
	});
	<!-- BEGIN: auto_get_alias -->
	$("#idtitle").change(function(){
		get_alias('idtitle', 'res_get_alias');
	});
	<!-- END: auto_get_alias -->
});
</script>
<!-- END: main -->