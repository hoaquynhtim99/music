<!-- BEGIN: main -->
<form method="post">
<table class="tab1">
	<thead>
		<tr>
			<td width="63px">{LANG.order}</td>
			<td>{LANG.category}</td>
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
			<td>{td}</td>
			</td>
			<td align="center">
				<span class="delete_icon"><a class='delfile' href="{URL_DEL_ONE}">{LANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
	<!-- BEGIN: add -->
		<tr>
		 <td style="text-align:center;" colspan="3">
			{tdadd}
		 </td>
		</tr>
	<!-- END: add -->
		<tr><td style="text-align:center;" colspan="3">
		<input type="hidden" name="save" value="1" />
		<input type="hidden" name="num" value="{num}" />
		<input type="submit" value="{LANG.save}" />
		</td></tr>
	</form>
</table>
<script type='text/javascript'>
	$(function(){
		$('a[class="delfile"]').click(function(event){
			event.preventDefault();
			if (confirm("{LANG.category_main_del_confim}")){
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
				url: "{NV_BASE_ADMINURL}index.php?"+nv_name_variable+"="+nv_module_name+"&"+nv_fc_variable+"=sort",
				data: "old=" + $(this).attr('id') + "&new=" + $(this).val(),
				success: function(data){
					window.location.href = window.location.href;
				}
			});
		});
	});
</script>
<!-- END: main -->
