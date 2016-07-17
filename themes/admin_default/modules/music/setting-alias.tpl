<!-- BEGIN: main -->
<div class="infoalert">{LANG.setting_alias_note}</div>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="150"/>
			<tbody>
				<tr>
					<td class="strong">{LANG.setting_alias_viewsong}</td>
					<td><input type="text" class="form-control music-input txt-half" name="alias_listen_song" value="{DATA.alias_listen_song}"/></td>
				</tr>
				<tr>
					<td class="strong">{LANG.setting_alias_viewalbum}</td>
					<td><input type="text" class="form-control music-input txt-half" name="alias_view_album" value="{DATA.alias_view_album}"/></td>
				</tr>
				<tr>
					<td class="strong">{LANG.setting_alias_viewvideoclip}</td>
					<td><input type="text" class="form-control music-input txt-half" name="alias_view_videoclip" value="{DATA.alias_view_videoclip}"/></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input class="music-button" type="submit" name="submit" value="{LANG.submit}" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- END: main -->