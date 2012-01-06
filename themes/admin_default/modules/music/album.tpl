<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td width="20px">{LANG.select}</td>
			<td><a href="{ORDER_NAME}">{LANG.album_name}</a></td>
			<td><a href="{ORDER_SINGER}">{LANG.singer}</a></td>
			<td>{LANG.active}</td>
			<td width="250px" align="center">{LANG.feature}</td>
			<td width="100px" align="center">{LANG.add_song}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{class}>
		<tr>
			<td align="center"><input type='checkbox' class='filelist' value="{id}"></td>
			<td><a href="{URL}" target="_blank">{name}</a> <a href="{URL_SONG}">({numsong}) {LANG.song}</a></td>
			<td>{singer}</td>
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
				<span class="add_icon">
					<a href="{URL_ADD_TO_MAINALBUM}">{LANG.add_to_main_album}</a>
				</span>
			</td>
			<td>
				<span class="add_icon">
					<a href="{url_add_song}">{LANG.song_add}</a>
					&nbsp;&nbsp;
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
				<span class="add_icon"><a class="addfile" href="{LINK_ADD}">{LANG.add_album}</a>&nbsp;&nbsp;</span>
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
					alert("{LANG.error_check_album}");
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
			if (confirm("{LANG.album_del_cofirm}"))
			{
				var listall = [];
				$('input.filelist:checked').each(function()
				{
					listall.push($(this).val());
				});
				if (listall.length < 1)
				{
					alert("{LANG.error_check_album}");
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
			if (confirm("{LANG.album_del_cofirm}"))
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
