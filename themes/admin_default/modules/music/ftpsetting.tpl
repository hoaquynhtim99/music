<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" action="" autocomplete="off">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.ftp_host}</td>
				<td class="col-number">{LANG.ftp_user}</td>
				<td class="col-number">{LANG.ftp_pass}</td>
				<td>{LANG.ftp_full_address}</td>
				<td>{LANG.ftp_floder}</td>
				<td>{LANG.ftp_sub_address}</td>
				<td class="col-status center">{LANG.active}</td>
				<td class="col-status center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<td><input class="music-input txt-fullsmini" name="host{ROW.key}" type="text" value="{ROW.host}" /></td>
				<td><input class="music-input txt-fullsmallest" name="user{ROW.key}" type="text" value="{ROW.user}" /></td>
				<td><input class="music-input txt-fullsmallest" name="pass{ROW.key}" type="password" value="{ROW.pass}" /></td>
				<td><input class="music-input txt-fullsmini" name="fulladdress{ROW.key}" type="text" value="{ROW.fulladdress}" /></td>
				<td><input class="music-input txt-fullsmini" name="subpart{ROW.key}" type="text" value="{ROW.subpart}" /></td>
				<td><input class="music-input txt-fullsmini" name="ftppart{ROW.key}" type="text" value="{ROW.ftppart}" /></td>
				<td class="center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_ftp_status({ROW.id})" /></a>
				<td class="center"><span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_ftp({ROW.id});">{LANG.delete}</a></span></td>
			</tr>
		</tbody>
		<!-- END: loop -->
		<tbody class="second">
			<tr>
				<td><input class="music-input txt-fullsmini" name="host{NEWID}" type="text" /></td>
				<td><input class="music-input txt-fullsmallest" name="user{NEWID}" type="text" /></td>
				<td><input class="music-input txt-fullsmallest" name="pass{NEWID}" type="password" /></td>
				<td><input class="music-input txt-fullsmini" name="fulladdress{NEWID}" type="text" /></td>
				<td><input class="music-input txt-fullsmini" name="subpart{NEWID}" type="text" /></td>
				<td><input class="music-input txt-fullsmini" name="ftppart{NEWID}" type="text" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8" class="center"><input type="submit" value="{LANG.save}" class="music-button"></td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" value="1" name="save">
	<input type="hidden" value="{NEWID}" name="newid">
	<input type="hidden" value="{LASTID}" name="lastid">
</form>
<!-- END: main -->