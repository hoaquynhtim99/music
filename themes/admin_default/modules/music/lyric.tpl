<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="music-input text size2" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.add_lyric}"/>
					<input class="music-input text size2" type="text" name="song" value="{DATA_SEARCH.song}" placeholder="{LANG.song_name}"/>
					<input class="music-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="music-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="music-button" type="button" name="clear" value="{LANG.filter_clear}"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_song = $('input[name=song]').val();

		if( f_q != '' || f_song != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&song=' + f_song;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>
<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td class="center col-check">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td class="col-sathor">{LANG.song_name}</td>
				<td>{LANG.add_lyric}</td>
				<td class="col-number">{LANG.error_user}</td>
				<td class="aright col-date"><a href="{DATA_ORDER.addtime.data.url}" title="{DATA_ORDER.addtime.data.title}" class="{DATA_ORDER.addtime.data.class}">{LANG.playlist_time}</a></td>
				<td class="center col-status">{LANG.status}</td>
				<td class="center col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr class="topalign">
				<td class="center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td>{ROW.song}</td>
				<td>{ROW.body}</td>
				<td>{ROW.user}</td>
				<td class="aright">{ROW.addtime}</td>
				<td class="center">
					<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_lyric_status({ROW.id})" />
				</td>
				<td class="center">
					<span class="edit-icon"><a class="nounderline" href="{ROW.url_edit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_lyric({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="7">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
		<tfoot>
			<tr>
				<td colspan="7">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_lyric_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->