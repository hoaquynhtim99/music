<!-- BEGIN: main -->
<div class="infoalert">{LANG.setting_alias_note}</div>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<col width="150"/>
		<tbody>
			<tr>
				<td class="strong">{LANG.setting_alias_viewsong}</td>
				<td><input type="text" class="music-input txt-half" name="alias_listen_song" value="{DATA.alias_listen_song}"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="strong">{LANG.setting_alias_viewalbum}</td>
				<td><input type="text" class="music-input txt-half" name="alias_view_album" value="{DATA.alias_view_album}"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td class="strong">{LANG.setting_alias_viewvideoclip}</td>
				<td><input type="text" class="music-input txt-half" name="alias_view_videoclip" value="{DATA.alias_view_videoclip}"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input class="music-button" type="submit" name="submit" value="{LANG.submit}" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->