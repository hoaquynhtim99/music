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
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:150px">
					<strong>{LANG.actor_name}</strong>
				</td>
				<td>
					<input class="form-control" type="text" style="width:350px" value="{DATA.title}" name="title" />
				</td>
			</tr>
			<tr>
				<td style="width:150px">
					<strong>{LANG.actor_national}</strong>
				</td>
				<td>
					<select class="form-control" name="national" style="width:350px">
						<!-- BEGIN: national -->
						<option value="{national.id}"{national.selected}>{national.title}</option>
						<!-- END: national -->
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:150px">
					<strong>{LANG.images}</strong>
				</td>
				<td>
					<input class="form-control" type="text" style="width:300px" value="{DATA.images}" name="images" id="images" />
					<input type="button" name="selectimages" id="selectimages" value="{LANG.select}" />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="width:150px">
					<strong>{LANG.description}</strong>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{DATA.description}
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="text-center">
					<input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#selectimages").click( function() {
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=images&path={IMG_DIR}&type=image", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
});
</script>
<!-- END: main -->