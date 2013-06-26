<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td width="63px">{LANG.order}</td>
			<td>Album</td>
			<td width="100px" align="center">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{class}>
		<tr>
			<td>
				<!-- BEGIN: sel --> 
				<select class="sel_w" style="width: 60px;" id="{SEL_W}">
					<!-- BEGIN: sel_op -->
					<option {SELECT} value="{VAL}">{VAL}</option>
					<!-- END: sel_op -->
				</select> 
				<!-- END: sel -->
			</td>
			<td>{name}</td>
			</td>
			<td align="center">
				<span class="delete_icon"><a class='delfile' href="{URL_DEL_ONE}">{LANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
</table>
<script type='text/javascript'>
	$(function(){
		$('a[class="delfile"]').click(function(event){
			event.preventDefault();
			if (confirm("{LANG.album_del_cofirm}")){
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
		$('.sel_w').change(function(event){
			$.ajax({
				type: "POST",
				url: "{NV_BASE_ADMINURL}index.php?"+nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=sortmainalbum",
				data: "old=" + $(this).attr('id') + "&new=" + $(this).val(),
				success: function(data){
					window.location.href = window.location.href;
				}
			});
		});
	});
</script>
<!-- END: main -->
