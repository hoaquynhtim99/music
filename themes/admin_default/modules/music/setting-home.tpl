<!-- BEGIN: main -->
<div class="infoalert">{LANG.home_setting_note}</div>
<form class="form-inline" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col style="width:50%"/>
			<tbody>
				<tr>
					<td class="atop">
						<p>
							<strong>
								{LANG.album}
								<a href="javascript:void(0);" id="addonealbum" class="nounderline add-icon">{LANG.home_setting_select_one}</a>
								<a href="javascript:void(0);" id="addlistalbum" class="nounderline list-icon">{LANG.home_setting_select_more}</a>
							</strong>
						<p>
						<input type="hidden" name="albums" value="{LISTALBUMS}"/>
						<ul id="listalbums-area" class="fixbg list_song">
							<!-- BEGIN: album -->
							<li class="{ALBUM.id}">
								{ALBUM.tname}<span onclick="nv_del_item_on_list({ALBUM.id}, 'listalbums-area', '{LANG.author_del_confirm}', 'albums')" class="delete-icon">&nbsp;</span>
							</li>
							<!-- END: album -->
						</ul>
					</td>
					<td class="atop">
						<p>
							<strong>
								{LANG.video} 
								<a href="javascript:void(0);" id="addonevideo" class="nounderline add-icon">{LANG.home_setting_select_one}</a>
								<a href="javascript:void(0);" id="addlistvideo" class="nounderline list-icon">{LANG.home_setting_select_more}</a>
							</strong>
						</p>
						<input type="hidden" name="videos" value="{LISTVIDEOS}"/>
						<ul id="listvideos-area" class="fixbg list_song">
							<!-- BEGIN: video -->
							<li class="{VIDEO.id}">
								{VIDEO.tname}<span onclick="nv_del_item_on_list({VIDEO.id}, 'listvideos-area', '{LANG.author_del_confirm}', 'videos')" class="delete-icon">&nbsp;</span>
							</li>
							<!-- END: video -->
						</ul>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="text-center" colspan="2"><input type="submit" class="music-button" name="submit" value="{LANG.save}"/></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<div class="infoalert">{LANG.home_setting_order_note}</div>
<script type="text/javascript">
$(document).ready(function(){
	$( "#listalbums-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listalbums-area', 'albums'); }
	});
	$( "#listalbums-area" ).disableSelection();
	
	$( "#listvideos-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listvideos-area', 'videos'); }
	});
	$( "#listvideos-area" ).disableSelection();

	$("a#addonevideo").click(function(){
		var listvideo = $("input[name=videos]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=videoclip&findOneAndReturn=1&area=listvideos-area&input=videos&listvideo=" + listvideo, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistvideo").click(function(){
		var listvideo = $("input[name=videos]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=videoclip&findListAndReturn=1&area=listvideos-area&input=videos&listvideo=" + listvideo, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});

	$("a#addonealbum").click(function(){
		var listalbum = $("input[name=albums]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=album&findOneAndReturn=1&area=listalbums-area&input=albums&listalbum=" + listalbum, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistalbum").click(function(){
		var listalbum = $("input[name=albums]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=album&findListAndReturn=1&area=listalbums-area&input=albums&listalbum=" + listalbum, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
</script>
<!-- END: main -->