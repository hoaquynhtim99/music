<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" method="post" action="" autocomplete="off">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.ftp_host}</th>
					<th class="col-number">{LANG.ftp_user}</th>
					<th class="col-number">{LANG.ftp_pass}</th>
					<th>{LANG.ftp_full_address}</th>
					<th>{LANG.ftp_floder}</th>
					<th>{LANG.ftp_sub_address}</th>
					<th class="col-status center">{LANG.active}</th>
					<th class="col-status center">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
			<!-- BEGIN: loop -->
				<tr>
					<td><input class="form-control music-input txt-fullsmini" name="host{ROW.key}" type="text" value="{ROW.host}" /></td>
					<td><input class="form-control music-input txt-fullsmallest" name="user{ROW.key}" type="text" value="{ROW.user}" /></td>
					<td><input class="form-control music-input txt-fullsmallest" name="pass{ROW.key}" type="password" value="{ROW.pass}" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="fulladdress{ROW.key}" type="text" value="{ROW.fulladdress}" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="subpart{ROW.key}" type="text" value="{ROW.subpart}" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="ftppart{ROW.key}" type="text" value="{ROW.ftppart}" /></td>
					<td class="text-center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_ftp_status({ROW.id})" /></a>
					<td class="text-center"><span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_ftp({ROW.id});">{LANG.delete}</a></span></td>
				</tr>
			<!-- END: loop -->
				<tr>
					<td><input class="form-control music-input txt-fullsmini" name="host{NEWID}" type="text" /></td>
					<td><input class="form-control music-input txt-fullsmallest" name="user{NEWID}" type="text" /></td>
					<td><input class="form-control music-input txt-fullsmallest" name="pass{NEWID}" type="password" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="fulladdress{NEWID}" type="text" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="subpart{NEWID}" type="text" /></td>
					<td><input class="form-control music-input txt-fullsmini" name="ftppart{NEWID}" type="text" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="8" class="text-center"><input type="submit" value="{LANG.save}" class="music-button"></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" value="1" name="save">
	<input type="hidden" value="{NEWID}" name="newid">
	<input type="hidden" value="{LASTID}" name="lastid">
</form>
<!-- END: main -->