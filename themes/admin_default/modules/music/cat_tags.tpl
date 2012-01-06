<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td width="20px">STT</td>
				<td width="20px">ID</td>
				<td width="20px">ID {LANG.category}</td>
				<td>{LANG.category}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{class}>
			<tr>
				<td align="center">{ROW.stt}</td>
				<td>{ROW.id}</td>
				<td>{ROW.cid}</td>
				<td>
					<input type="hidden" name="hide_theloai[]" value="{ROW.id}" />
					<select name="theloai[]">
						<option value="0">-- {GLANG.delete} --</option>
						<!-- BEGIN: cat -->
						<option value="{CAT.id}"{CAT.selected}>{CAT.title}</option>
						<!-- END: cat -->
					</select>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<tfoot>
			<tr>
				<td colspan="4">
					<input type="submit" name="submit_save" value="{LANG.submit}"/>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<form action="" method="post">
<table class="tab1">
	<tbody>
		<tr>
			<td>
				{LANG.select_category}
				<select name="add_theloai">
					<!-- BEGIN: cat -->
					<option value="{CAT.id}">{CAT.title}</option>
					<!-- END: cat -->
				</select>
				<input type="submit" name="submit_add" value="{LANG.add_hot_album}"/>
			</td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->
