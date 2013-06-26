<!-- BEGIN: main -->
<table class="tab1 fixmargin">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input style="width:300px" type="text" name="q" value="{DATA_SEARCH.q}" onfocus="if(this.value == '{LANG.filter_enterkey}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.filter_enterkey}';}"/>
					{LANG.filter_from}
					<input class="text" value="{DATA_SEARCH.from}" type="text" id="from" name="from" readonly="readonly" style="width:70px" />
					{LANG.filter_to}
					<input class="text" value="{DATA_SEARCH.to}" type="text" id="to" name="to" readonly="readonly" style="width:70px" />
					<input type="button" name="do" value="{LANG.filter_action}"/>
					<input type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input type="button" name="clear" value="{LANG.filter_clear}"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$("#from,#to").datepicker({
		showOn: "button",
		dateFormat: "dd.mm.yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		buttonText: '{LANG.select}',
		showButtonPanel: true,
		showOn: 'focus'
	});
	//
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
		$('input[name=q]').val('{LANG.filter_enterkey}');
	});
	//
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_from = $('input[name=from]').val();
		var f_to = $('input[name=to]').val();

		if ( ( f_q != '{LANG.filter_enterkey}' && f_q != '' ) || f_from != '' || f_to != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&from=' + f_from + '&to=' + f_to;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>
<table class="tab1">
	<thead>
		<tr>
			<td width="20px">{LANG.select}</td>
			<td>{LANG.user_comment}</td>
			<td>{LANG.sort_content}</td>
			<td>{LANG.song_name}</td>
			<td>{LANG.active}</td>
			<td width="100px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{class}>
		<tr>
			<td align="center"><input type='checkbox' class='filelist' value="{id}"></td>
			<td>{name}</td>
			<td>{body}</td>
			<td>{song}</td>
			<td width="50px" align="center"><a href="{URL_ACTIVE}" class="active">{active}</a>
			</td>
			<td align="center">
				<span class="edit_icon">
					<a class='editfile' href="{URL_EDIT}">{LANG.edit}</a>
				</span>
				&nbsp;-&nbsp; 
				<span class="delete_icon">
					<a class='delfile' href="{URL_DEL_ONE}">{LANG.delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
</table>
<table class="tab1">
	<tfoot>
		<tr>
			<td>
				<span class="select_icon"><a href="javascript:void(0);" id="checkall">{LANG.checkall}</a>&nbsp;&nbsp;</span>
				<span class="unselect_icon"><a href="javascript:void(0);" id="uncheckall">{LANG.uncheckall}</a>&nbsp;&nbsp;</span>
				<span class="delete_icon"><a id="delfilelist" href="javascript:void(0);">{LANG.delete}</a>&nbsp;&nbsp;</span>
				<span class="status_icon"><a id="activelist" href="javascript:void(0);">{LANG.active1}</a>&nbsp;&nbsp;</span>
			</td>
		</tr>
	</tfoot>
</table>
<script type='text/javascript'>
	$(function()
	{
		$('#checkall').click(function()
		{
			$('input:checkbox').each(function()
			{
				$(this).attr('checked', 'checked');
			});
		});
		
		$('#uncheckall').click(function()
		{
			$('input:checkbox').each(function()
			{
				$(this).removeAttr('checked');
			});
		});
		
		$('#activelist').click(function()
		{
			if (confirm("{LANG.active_confirm}"))
			{
				var listall = [];
				$('input.filelist:checked').each(function()
				{
					listall.push($(this).val());
				});
				if (listall.length < 1)
				{
					alert("{LANG.error_check_comment}");
					return false;
				}
				$.ajax(
				{
					type: 'POST',
					url: '{URL_ACTIVE_LIST}',
					data: 'listall=' + listall,
					success: function(data)
					{
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('#delfilelist').click(function()
		{
			if (confirm("{LANG.comment_del_cofirm}"))
			{
				var listall = [];
				$('input.filelist:checked').each(function()
				{
					listall.push($(this).val());
				});
				if (listall.length < 1)
				{
					alert("{LANG.error_check_comment}");
					return false;
				}
				$.ajax(
				{
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
		$('a[class="delfile"]').click(function(event)
		{
			event.preventDefault();
			if (confirm("{LANG.comment_del_cofirm}"))
			{
				var href = $(this).attr('href');
				$.ajax(
				{
					type: 'POST',
					url: href,
					data: '',
					success: function(data)
					{
						alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a[class="active"]').click(function(event)
		{
			event.preventDefault();
			if (confirm("{LANG.active_confirm}"))
			{
				var href = $(this).attr('href');
				$.ajax(
				{
					type: 'POST',
					url: href,
					data: '',
					success: function(data)
					{
						//alert(data);
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
</script>
<!-- END: main -->
