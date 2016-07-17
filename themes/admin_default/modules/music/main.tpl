<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control music-input text size1" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_song}"/>
					<input class="form-control music-input text size1" type="text" name="singer" value="{DATA_SEARCH.singer}" placeholder="{LANG.filter_singer}"/>
					<input class="form-control music-input text size1" type="text" name="author" value="{DATA_SEARCH.author}" placeholder="{LANG.filter_author}"/>
					<select class="form-control text music-input size1" name="theloai">
						<option value="-1">{LANG.filter_category}</option>
						<!-- BEGIN: cat --><option value="{CAT.id}"{CAT.selected}>{CAT.title}</option><!-- END: cat -->
					</select>
					<input class="music-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="music-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="music-button" type="button" name="clear" value="{LANG.filter_clear}"/>
					<input class="music-button" type="button" value="{LANG.add_song}" onclick="window.location='{URL_ADD}';"/>
					<input class="music-button" type="button" value="{LANG.addFromOtherSite_title}" onclick="window.location='{URL_ADD_OTHER}';"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('[name="theloai"]').val(-1);
		$('#filter-form .text').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_singer = $('input[name=singer]').val();
		var f_author = $('input[name=author]').val();
		var f_theloai = $('select[name=theloai]').val();

		if( f_q != '' || f_singer != '' || f_author != '' || f_theloai != '-1' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&singer=' + f_singer + '&author=' + f_author + '&theloai=' + f_theloai;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td class="center col-check">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.song_name}</a></td>
				<td>{LANG.singer}</td>
				<td>{LANG.author}</td>
				<td>{LANG.album}</td>
				<td>{LANG.filter_category}</td>
				<td>{LANG.upboi}</td>
				<td>{LANG.info}</td>
				<td><a href="{DATA_ORDER.numview.data.url}" title="{DATA_ORDER.numview.data.title}" class="{DATA_ORDER.numview.data.class}">{LANG.song_numvew}</a></td>
				<td class="aright col-date"><a href="{DATA_ORDER.dt.data.url}" title="{DATA_ORDER.dt.data.title}" class="{DATA_ORDER.dt.data.class}">{LANG.playlist_time}</a></td>
				<td class="center col-status">{LANG.status}</td>
				<td class="center col-feature">{LANG.feature}</td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td class="text-center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td><a href="{ROW.link}" onclick="this.target='_blank'">{ROW.title}</a></td>
				<td>{ROW.singers}</td>
				<td>{ROW.authors}</td>
				<td>{ROW.album}</td>
				<td>{ROW.theloai}</td>
				<td>{ROW.upload_name}</td>
				<td>{ROW.bitrate} - {ROW.duration} - {ROW.size}</td>
				<td><strong>{ROW.numview}</strong></td>
				<td class="aright">{ROW.addtime}</td>
				<td class="text-center">
					<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_song_status({ROW.id})" />
				</td>
				<td class="text-center">
					<span class="edit-icon"><a class="nounderline" href="{ROW.url_edit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_song({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="12">
					{GENERATE_PAGE}
				</td>
			</tr>
		<!-- END: generate_page -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="12">
					<!-- BEGIN: action -->
					<span class="{ACTION.class}-icon"><a onclick="nv_song_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->