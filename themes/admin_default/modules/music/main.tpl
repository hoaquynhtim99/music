<!-- BEGIN: main -->
<!-- BEGIN: info --><div style="width:98%" class="quote">
    <blockquote class="error"><span>{INFO}.</span></blockquote>
</div><!-- END: info -->
<form action="{FORM_ACTION}" method="get">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<table class="tab1 fixbottomtable">
		<tbody>
			<tr>
				<td>
					<strong>{LANG.search_music}:</strong>&nbsp;&nbsp;&nbsp;
					<select name="where_search">
						<option value="0" >{LANG.select_category}</option>
						<!-- BEGIN: cat -->
						<option value="{CAT.id}"{CAT.selected}>{CAT.title}</option>
						<!-- END: cat -->
					</select>
					<select name="type_search">
						<option value="ten"{TYPE_TEN}>{LANG.search_with_name}</option>
						<option value="casi"{TYPE_CASI}>{LANG.search_with_singer}</option>
						<option value="nhacsi"{TYPE_NHACSI}>{LANG.search_with_author}</option>
						<option value="album"{TYPE_ALBUM}>{LANG.search_with_album}</option>
					</select>
					{LANG.search_per_page}
					<select name="numshow">
						<!-- BEGIN: numshow -->
						<option value="{NUM}"{SELECTED}>{TITLE}</option>
						<!-- END: numshow -->
					</select>
					{LANG.search_key}: <input type="text" value="{Q}" maxlength="64" name="q" style="width: 265px">
					<input type="submit" value="{LANG.search}">
					<input type="hidden" name ="do" value="1" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<table class="tab1">
	<thead>
		<tr>
			<td width="20px">{LANG.select}</td>
			<td><a href="{ORDER_NAME}">{LANG.song_name}</a></td>
			<td><a href="{ORDER_SINGER}">{LANG.singer}</a></td>
			<td><a href="{ORDER_ALBUM}">Album</a></td>
			<td>{LANG.category}</td>
			<td>{LANG.active}</td>
			<td width="100px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{class}>
		<tr>
			<td align="center"><input type='checkbox' class='filelist' value="{id}"></td>
			<td><a href="{URL}" target="_blank">{name}</a></td>
			<td>{singer}</td>
			<td>{album}</td>
			<td>{category}</td>
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
	<!-- BEGIN: genpage -->
	<tbody><tr><td class="strong center" colspan="7">{genpage}</td></tr></tbody>
	<!-- END: genpage -->
</table>
<table class="tab1">
	<tfoot>
		<tr>
			<td>
				<span class="select_icon"><a href="javascript:void(0);" id="checkall">{LANG.checkall}</a>&nbsp;&nbsp;</span>
				<span class="unselect_icon"><a href="javascript:void(0);" id="uncheckall">{LANG.uncheckall}</a>&nbsp;&nbsp;</span>
				<span class="add_icon"><a class="addfile" href="{LINK_ADD}">{LANG.song_add}</a>&nbsp;&nbsp;</span>
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
					alert("{LANG.error_check_song}");
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
