<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width:98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" name="add_pic">
	<table class="tab1">
		<col width="150"/>
		<col width="10"/>
		<thead>
			<tr>
				<td colspan="3">{LANG.singer_info}</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{LANG.singer_name}</td>
				<td class="center"><span class="requie">*</span></td>
				<td>
					<input class="music-input txt-half" id="idtitle" name="tenthat" value="{DATA.tenthat}" type="text"/>
					<img height="16" alt="Refreshing" onclick="get_alias('idtitle','res_get_alias');" style="cursor:pointer;vertical-align:middle;" width="16" src="{NV_BASE_SITEURL}images/refresh.png"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.song_name_short}</td>
				<td class="center"><span class="requie">*</span></td>
				<td><input class="music-input txt-half" id="idalias" name="ten" value="{DATA.ten}" type="text"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.thumb}</td>
				<td class="center"></td>
				<td>
					<input class="music-input txt-half" id="thumb" name="thumb" value="{DATA.thumb}" type="text" />
					<input class="music-button-2" name="select" type="button" value="{LANG.select}" onclick="nv_open_browse_file('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=upload&popup=1&area=thumb&path={IMAGE_DIR}&type=image', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="3">
					<p><strong>{LANG.describle}</strong></p>
					{DATA.introduction}
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="3" align="center">
					<input name="confirm" value="{LANG.save}" type="submit" class="music-button"/>
					<!-- BEGIN: add -->
					<input type="hidden" name="add" id="add" value="1">
					<!-- END: add -->
					<!-- BEGIN: edit -->
					<input type="hidden" name="edit" id="edit" value="1">
					<!-- END: edit -->
					<span name="notice" style="float: right; padding-right: 50px; color: red; font-weight: bold;"></span>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
$("#idtitle").change(function(){
	get_alias('idtitle', 'res_get_alias');
});
</script>
<!-- END: get_alias -->
<!-- END: main -->