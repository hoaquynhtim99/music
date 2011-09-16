<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:150px">
					<strong>{LANG.actor_name}</strong>
				</td>
				<td>
					<input type="text" style="width:350px" value="{DATA.title}" name="title" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td style="width:150px">
					<strong>{LANG.actor_national}</strong>
				</td>
				<td>
					<select name="national" style="width:350px">
						<!-- BEGIN: national -->
						<option value="{national.id}"{national.selected}>{national.title}</option>
						<!-- END: national -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td style="width:150px">
					<strong>{LANG.images}</strong>
				</td>
				<td>
					<input type="text" style="width:300px" value="{DATA.images}" name="images" id="images" />
					<input type="button" name="selectimages" id="selectimages" value="{LANG.select}" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="2" style="width:150px">
					<strong>{LANG.description}</strong>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
					{DATA.description}
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" name="submit" value="{LANG.submit}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#selectimages").click( function() {
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=images&path={IMG_DIR}&type=image", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
});
</script>
<!-- END: main -->
