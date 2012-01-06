<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td width="20px">{LANG.select}</td>
			<td>{LANG.error_user}</td>
			<td>{LANG.error_what}</td>
			<td>{LANG.content}</td>
			<td style="width:100px">{LANG.addtime}</td>
			<td style="width:60px">{LANG.ip}</td>
			<td style="width:80px">{LANG.status1}</td>
			<td width="150px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{class}>
		<tr>
			<td align="center"><input type="checkbox" class="filelist" value="{id}"></td>
			<td>{name}</td>
			<td><a href="{url_edit}" title="{what}">{what}</a></td>
			<td>{body}</td>
			<td>{addtime}</td>
			<td>{ip}</td>
			<td><span class="text">{status}</span>&nbsp;<a class="nounderline" title="{atitle}" href="javascript:void(0);" onclick="nv_checkviewed($(this),'{id}');"><span class="{icon}_icon">&nbsp;</span></a></td>
			<td align="center">
				<!-- BEGIN: check -->
				<span class="check_icon"><a href="javascript:void(0);" onclick="checksong('{SONG}');" class="checkfile">{LANG.check}</a></span>
				<!-- END: check -->
				<span class="delete_icon"><a class="delfile" href="{URL_DEL_ONE}">{LANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
		<td colspan="7">
			{GENERATE_PAGE}
		</td>
	</tr>
	</tfoot>
<!-- END: generate_page -->
</table>
<table class="tab1">
	<tfoot>
		<tr>
			<td>
				<span class="select_icon"><a href="javascript:void(0);" id="checkall">{LANG.checkall}</a>&nbsp;&nbsp;</span>
				<span class="unselect_icon"><a href="javascript:void(0);" id="uncheckall">{LANG.uncheckall}</a>&nbsp;&nbsp;</span>
				<span class="delete_icon"><a id="delfilelist" href="javascript:void(0);">{LANG.delete}</a>&nbsp;&nbsp;</span>
				<span class="check_icon"><a href="javascript:void(0);" id="checklink">{LANG.check}</a>&nbsp;&nbsp;</span>
			</td>
		</tr>
	</tfoot>
</table>
<script type='text/javascript'>
	$(function(){
		$('#checkall').click(function(){
			$('input:checkbox').each(function(){
				$(this).attr('checked', 'checked');
			});
		});
		
		$('#uncheckall').click(function(){
			$('input:checkbox').each(function(){
				$(this).removeAttr('checked');
			});
		});
		
		$('#checklink').click(function(){
			var listall = [];
			$('input.filelist:checked').each(function(){
				listall.push($(this).val());
			});
			if (listall.length < 1){
				alert("{LANG.error_check_error}");
				return false;
			}
			window.location = '{URL_CHECK}' + '&listall=' + listall;
		});
		$('#delfilelist').click(function(){
			if (confirm("{LANG.error_del_confirm}")){
				var listall = [];
				$('input.filelist:checked').each(function(){
					listall.push($(this).val());
				});
				if (listall.length < 1){
					alert("{LANG.error_check_error}");
					return false;
				}
				$.ajax({
					type: 'POST',
					url: '{URL_DEL}',
					data: 'listall=' + listall,
					success: function(data)
					{
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a[class="delfile"]').click(function(event){
			event.preventDefault();
			if (confirm("{LANG.error_del_confirm}")){
				var href = $(this).attr('href');
				$.ajax({
					type: 'POST',
					url: href,
					data: '',
					success: function(data){
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
// Danh da xem cac bao loi
function nv_checkviewed(element,sid){
	var parent_class = element.children('span').attr('class');
	if(parent_class=='select_icon') return false;
	$.ajax({
		type: 'POST',
		url: script_name,
		data: nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable+'=error&setviewed='+sid+'&num='+nv_randomPassword(8),
		success: function(data){
			if(data=='OK'){
				element.children('span').attr('class','select_icon');
				element.parent('td').find('.text').text('{LANG.view_ed}');
			}else alert(data);
		}
	});
	return false;
}
</script>
<!-- END: main -->
