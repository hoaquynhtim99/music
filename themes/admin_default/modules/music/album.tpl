<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="music-input text size2" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_album}"/>
					<input class="music-input text size2" type="text" name="singer" value="{DATA_SEARCH.singer}" placeholder="{LANG.filter_singer}"/>
					<input class="music-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="music-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="music-button" type="button" name="clear" value="{LANG.filter_clear}"/>
					<input class="music-button" type="button" value="{LANG.sub_add_album}" onclick="window.location='{URL_ADD}';"/>
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
		var f_singer = $('input[name=singer]').val();

		if( f_q != '' || f_singer != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&singer=' + f_singer;	
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
				<td class="center col-image">{LANG.thumb}</td>
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.album_name}</a></td>
				<td>{LANG.singer}</td>
				<td class="col-number">{LANG.album_numsong}</td>
				<td class="col-number"><a href="{DATA_ORDER.numview.data.url}" title="{DATA_ORDER.numview.data.title}" class="{DATA_ORDER.numview.data.class}">{LANG.song_numvew}</a></td>
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
				<td class="center"><a href="{ROW.thumb}" title="{ROW.title}" rel="shadowbox"><img src="{ROW.thumb}" alt="{ROW.title}" width="50" height="50"/></a></td>
				<td>{ROW.title}</td>
				<td>{ROW.singers}</td>
				<td class="center"><strong>{ROW.numsong}</strong></td>
				<td class="center"><strong>{ROW.numview}</strong></td>
				<td class="aright">{ROW.addtime}</td>
				<td class="center">
					<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_album_status({ROW.id})" />
				</td>
				<td class="center">
					<span class="edit-icon"><a class="nounderline" href="{ROW.url_edit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_album({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="9">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
		<tfoot>
			<tr>
				<td colspan="9">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_album_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->