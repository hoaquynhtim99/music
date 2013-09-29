<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="music-input text size2" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_singer}"/>
					<input class="music-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="music-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="music-button" type="button" name="clear" value="{LANG.filter_clear}"/>
					<input class="music-button" type="button" value="{LANG.singer_add1}" onclick="window.location='{URL_ADD}';"/>
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

		if( f_q != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q;	
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
				<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.filter_singer}</a></td>
				<td class="col-number"><a href="{DATA_ORDER.numsong.data.url}" title="{DATA_ORDER.numsong.data.title}" class="{DATA_ORDER.numsong.data.class}">{LANG.siteinfo_numsong}</a></td>
				<td class="col-number"><a href="{DATA_ORDER.numalbum.data.url}" title="{DATA_ORDER.numalbum.data.title}" class="{DATA_ORDER.numalbum.data.class}">{LANG.siteinfo_numalbum}</a></td>
				<td class="col-number"><a href="{DATA_ORDER.numvideo.data.url}" title="{DATA_ORDER.numvideo.data.title}" class="{DATA_ORDER.numvideo.data.class}">{LANG.siteinfo_numvideo}</a></td>
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
				<td class="center"><strong>{ROW.numsong}</strong></td>
				<td class="center"><strong>{ROW.numalbum}</strong></td>
				<td class="center"><strong>{ROW.numvideo}</strong></td>
				<td class="center">
					<span class="edit-icon"><a class="nounderline" href="{ROW.url_edit}">{GLANG.edit}</a></span>
					<span class="delete-icon"><a class="nounderline" href="javascript:void(0);" onclick="nv_delete_singer({ROW.id});">{GLANG.delete}</a></span>
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
					<span class="{ACTION.class}-icon"><a onclick="nv_singer_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
					<!-- END: action -->
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->