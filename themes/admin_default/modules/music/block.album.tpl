<!-- BEGIN: main -->
<tr style=display:none>
	<td><link rel=stylesheet href="{CSS_FILE}" type=text/css /></td>
	<td>{JQUERY_PLUGIN}</td>
</tr>
<tr>
	<td>{LANG.block_album_display_type}</td>
	<td>
		<select name="config_display_type" class="music-input">	
			<!-- BEGIN: display_type --><option value="{DISPLAY_TYPE.key}"{DISPLAY_TYPE.selected}>{DISPLAY_TYPE.title}</option><!-- END: display_type -->
		</select>
	</td>
</tr>
<tr id="albums-manual" class="hide">
	<td class="atop">
		<p>{LANG.album}</p>
		<p><a href="javascript:void(0);" id="addonealbum" class="nounderline add-icon">{LANG.home_setting_select_one}</a></p>
		<p><a href="javascript:void(0);" id="addlistalbum" class="nounderline list-icon">{LANG.home_setting_select_more}</a></p>
	</td>
	<td class="atop">
		<input type="hidden" name="config_albums" value="{LISTALBUMS}"/>
		<ul id="listalbums-area" class="fixbg list_song">
			<!-- BEGIN: album -->
			<li class="{ALBUM.id}">
				{ALBUM.tname}<span onclick="nv_del_item_on_list({ALBUM.id}, 'listalbums-area', '{LANG.author_del_confirm}', 'config_albums')" class="delete-icon">&nbsp;</span>
			</li>
			<!-- END: album -->
		</ul>
	</td>
</tr>
<tr style=display:none>
	<td></td>
	<td>
		<script type="text/javascript">
		function controlDisplay(){
			var type = $('select[name="config_display_type"]').val();
			if( type == '0' ){
				$('#albums-manual').show();
			}
		}
		$(document).ready(function(){
			controlDisplay();
			$('select[name="config_display_type"]').change(function(){
				controlDisplay();
			});
			
			$( "#listalbums-area" ).sortable({
				cursor: "crosshair",
				update: function(event, ui) { nv_sort_item('listalbums-area', 'config_albums'); }
			});
			$( "#listalbums-area" ).disableSelection();
			
			$("a#addonealbum").click(function(){
				var listalbum = $("input[name=config_albums]").attr("value");
				nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=album&findOneAndReturn=1&area=listalbums-area&input=config_albums&listalbum=" + listalbum, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
			});
			$("a#addlistalbum").click(function(){
				var listalbum = $("input[name=config_albums]").attr("value");
				nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=album&findListAndReturn=1&area=listalbums-area&input=config_albums&listalbum=" + listalbum, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
			});
		});
		</script>
	</td>
</tr>
<!-- END: main -->