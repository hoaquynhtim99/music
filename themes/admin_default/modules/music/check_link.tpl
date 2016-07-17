<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>{LANG.state}</th>
				<th width="150px" class="text-center">{LANG.feature}</th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td><a href="{URL}" target="_blank">{songname}</a></td>
				<td>{result}</td>
				<td class="text-center">
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
		<!-- END: loop -->
		</tbody>
	</table>
</div>
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