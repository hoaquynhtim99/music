<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td width="20px">{LANG.select}</td>
			<td>{LANG.song}</td>
			<td>{LANG.state}</td>
			<td width="150px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{class}>
		<tr>
			<td align="center"><input type='checkbox' class='filelist' value="{id}"></td>
			<td><a href='{URL}' target="_blank">{songname}</a></td>
			<td>{result}</td>
			<td align="center">
				<!-- BEGIN: check -->
					<a onclick="checksong('{SONG}');" class='checkfile'>{LANG.check}</a>
				<!-- END: check -->
				<span class="edit_icon">
					<a class='editfile' href="{URL_EDIT}">{LANG.edit}</a>
				</span>
				<span class="delete_icon">
					<a class='delfile' href="{URL_DEL_ONE}">{LANG.delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<table class="tab1">
	<tfoot>
		<tr>
			<td>
				<span>
					<a href='javascript:void(0);' id='checkall'>{LANG.checkall}</a>
					&nbsp;&nbsp;
					<a href='javascript:void(0);' id='uncheckall'>{LANG.uncheckall}</a>
					&nbsp;&nbsp;
				</span>
				<span class="delete_icon"><a id='delfilelist' href="javascript:void(0);">{LANG.delete}</a>
				</span>
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
		
		$('#delfilelist').click(function()
		{
			if (confirm("{LANG.song_del_confirm}"))
			{
				var listall = [];
				$('input.filelist:checked').each(function()
				{
					listall.push($(this).val());
				});
				if (listall.length < 1)
				{
					alert("{LANG.error_check_song}");
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
			if (confirm("{LANG.song_del_confirm}"))
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
	});
</script>
<!-- END: main -->
