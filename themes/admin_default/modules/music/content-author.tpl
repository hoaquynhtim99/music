<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width:98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" method="post" name="add_pic">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="150"/>
			<col width="10"/>
			<thead>
				<tr>
					<th colspan="3">{LANG.author_info}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{LANG.author_name}</td>
					<td class="text-center"><span class="requie">*</span></td>
					<td>
						<input class="form-control music-input txt-half" id="idtitle" name="tenthat" value="{DATA.tenthat}" type="text">
						<img height="16" alt="Refreshing" onclick="get_alias('idtitle','res_get_alias');" style="cursor:pointer;vertical-align:middle;" width="16" src="{NV_BASE_SITEURL}images/refresh.png"/>
					</td>
				</tr>
			<tbody>
				<tr>
					<td>{LANG.author_sort_name}</td>
					<td class="text-center"><span class="requie">*</span></td>
					<td><input class="form-control music-input txt-half" id="idalias" name="ten" value="{DATA.ten}" type="text"/></td>
				</tr>
			<tbody>
				<tr>
					<td>{LANG.thumb}</td>
					<td class="text-center"></td>
					<td>
						<input id="thumb" name="thumb" class="form-control music-input txt-half" value="{DATA.thumb}" type="text"/>
						<input class="music-button-2" name="select" type="button" value="{LANG.select}" onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=upload&popup=1&area=thumb&path={IMAGE_DIR}&type=image', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');"/>
					</td>
				</tr>
			<tbody>
				<tr>
					<td colspan="3">
						<p><strong>{LANG.describle}</strong></p>
						{DATA.introduction}
					</td>
				</tr>
				<tr>
					<td colspan="3" class="text-center">
						<input class="music-button" name="confirm" value="{LANG.save}" type="submit">
						<!-- BEGIN: add -->
						<input type="hidden" name="add" id="add" value="1">
						<!-- END: add -->
						<!-- BEGIN: edit -->
						<input type="hidden" name="edit" id="edit" value="1">
						<!-- END: edit -->
						<span name="notice" style="float: right; padding-right: 50px; color: red; font-weight: bold;"></span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
$("#idtitle").change(function(){
	get_alias('idtitle', 'res_get_alias');
});
</script>
<!-- END: get_alias -->
<!-- END: main -->