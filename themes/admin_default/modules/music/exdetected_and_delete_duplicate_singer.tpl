<!-- BEGIN: main -->
<!-- BEGIN: find_duplicate -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:40px">{LANG.images_mini}</td>
			<td>{LANG.singer_name}</td>
			<td style="width:100px">{LANG.song}</td>
			<td style="width:100px">{LANG.album}</td>
			<td style="width:100px">{LANG.video}</td>
			<td style="width:100px">{LANG.ex_detected_and_delete_duplicate_singer_num}</td>
			<td style="width:50px">{LANG.detail}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>
				<!-- BEGIN: thumb --><a href="{ROW.thumb}" title="{ROW.tenthat}" rel="shadowbox[]"><img src="{ROW.thumb}" alt="{ROW.tenthat}" width="40" height="40"/></a><!-- END: thumb -->
			</td>
			<td>{ROW.tenthat}</td>
			<td>{ROW.numsong}</td>
			<td>{ROW.numalbum}</td>
			<td>{ROW.numvideo}</td>
			<td>{ROW.duplicate}</td>
			<td><a href="{ROW.link}" class="default_icon">{LANG.view}</a></td>
		</tr>
	</tbody>
	<!-- END: loop -->
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
<!-- END: find_duplicate -->
<!-- BEGIN: detail_duplicate -->
<form action="" method="post" name="levelnone" id="levelnone">
<table class="tab1">
	<thead>
		<tr>
			<td class="center" style="width:50px">ID</td>
			<td style="width:40px">{LANG.images_mini}</td>
			<td>{LANG.singer_name}</td>
			<td style="width:100px">{LANG.song}</td>
			<td style="width:100px">{LANG.album}</td>
			<td style="width:100px">{LANG.video}</td>
			<td class="center" style="width:50px">{GLANG.delete}<br /><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'deleteid[]', 'check_all[]',this.checked);" /></td>
			<td class="center" style="width:50px">{LANG.ex_detected_and_delete_duplicate_singer_to}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td class="center">{ROW.id}</td>
			<td>
				<!-- BEGIN: thumb --><a href="{ROW.thumb}" title="{ROW.tenthat}" rel="shadowbox[]"><img src="{ROW.thumb}" alt="{ROW.tenthat}" width="40" height="40"/></a><!-- END: thumb -->
			</td>
			<td>{ROW.tenthat}</td>
			<td>{ROW.numsong}</td>
			<td>{ROW.numalbum}</td>
			<td>{ROW.numvideo}</td>
			<td class="center">
				<input id="singer-{ROW.id}" type="checkbox" onclick="nv_UncheckAll(this.form, 'deleteid[]', 'check_all[]', this.checked);" value="{ROW.id}" name="deleteid[]" />
			</td>
			<td class="center">
				<input type="radio" name="toid" value="{ROW.id}"/>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
	<tfoot>
		<tr>
			<td colspan="8" class="center">
				<input type="submit" name="submit" value="{LANG.ex_do}"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
<!-- END: detail_duplicate -->
<!-- END: main -->
<!-- BEGIN: complete_duplicate -->
<div class="infook">
	{LANG.ex_detected_and_delete_duplicate_singer_complete}<br />
	<a href="{URL}">{LANG.back_page}</a>
</div>
<!-- END: complete_duplicate -->
