<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td>&nbsp;</td>
			<td>{LANG.state}</td>
			<td width="150px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{class}>
		<tr>
			<td><a href="{URL}" target="_blank">{songname}</a></td>
			<td>{result}</td>
			<td align="center">
				<!-- BEGIN: check -->
					<a onclick="checksong('{SONG}');" class='checkfile'>{LANG.check}</a>
				<!-- END: check -->
				<span class="edit_icon">
					<a class="editfile" href="{URL_EDIT}">{LANG.edit}</a>
				</span>
				<span class="delete_icon">
					<a class="delfile" href="{URL_DEL_ONE}">{LANG.delete}</a>
				</span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<script type="text/javascript">
	$(function(){		
		$('a[class="delfile"]').click(function(event){
			event.preventDefault();
			if (confirm("{LANG.song_del_confirm}")){
				var href = $(this).attr('href');
				$.ajax({
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
