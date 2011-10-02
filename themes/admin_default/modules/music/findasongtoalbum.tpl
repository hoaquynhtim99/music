<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.album_add_a_song}</title>
		
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript"> var nv_siteroot = "{NV_BASE_SITEURL}";</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
	</head>
	<body>
		<div id="getuidcontent">
			<form id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
			<input type="hidden" name="songlist" value="{SONGLIST}" />
			<table class="tab1 fixbottomtable">
				<tbody class="second">
					<tr><td colspan="4" class="center green"><strong>{LANG.album_add_a_song}</strong></td></tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.song_name}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="ten" value="{SEARCH.ten}" maxlength="100" />
						</td>
						<td>
							{LANG.singer}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="casi" value="{SEARCH.casi}" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.who_up}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="upboi" value="{SEARCH.upboi}" maxlength="100" />
						</td>
						<td>
							{LANG.author}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="nhacsi" value="{SEARCH.nhacsi}" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>
							{LANG.category}
						</td>
						<td>
							<select class="fixwidthinput" name="theloai">
								<!-- BEGIN: theloai -->
								<option value="{theloai.id}"{theloai.selected}>{theloai.title}</option>
								<!-- END: theloai -->
							</select>
						</td>
						<td>
							{LANG.album}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="album" value="{SEARCH.album}" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td>{LANG.filter_from}</td>
						<td><input class="text" value="{SEARCH.from}" type="text" id="from" name="from" readonly="readonly" style="width:120px" /></td>
						<td>{LANG.filter_to}</td>
						<td><input class="text" value="{SEARCH.to}" type="text" id="to" name="to" readonly="readonly" style="width:120px" /></td>
					</tr>
				</tbody>
				<tbody class="second">
					<tr>
						<td colspan="4" class="center">
							<input type="submit" name="submit" value="{LANG.search}"/>
							<strong><a href="{URLCANCEL}" title="{LANG.filter_cancel}">{LANG.filter_cancel}</a></strong>
						</td>
					</tr>
				</tbody>
			</table>
			<script type="text/javascript">
			$(document).ready(function(){
				$("#from,#to").datepicker({
					showOn: "button",
					dateFormat: "dd.mm.yy",
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					buttonText: '{LANG.select}',
					showButtonPanel: true,
					showOn: 'focus'
				});
			});
			</script>
			</form>
		</div>
		<div id="resultdata">
			<table class="tab1 fixbottomtable">
				<thead>
					<tr>
						<td>ID</td>
						<td><a href="{DATA_ORDER.ten.data.url}" title="{DATA_ORDER.ten.data.title}" class="{DATA_ORDER.ten.data.class}">{LANG.song_name}</a></td>
						<td><a href="{DATA_ORDER.theloai.data.url}" title="{DATA_ORDER.theloai.data.title}" class="{DATA_ORDER.theloai.data.class}">{LANG.category}</a></td>
						<td><a href="{DATA_ORDER.casi.data.url}" title="{DATA_ORDER.casi.data.title}" class="{DATA_ORDER.casi.data.class}">{LANG.singer}</a></td>
						<td><a href="{DATA_ORDER.nhacsi.data.url}" title="{DATA_ORDER.nhacsi.data.title}" class="{DATA_ORDER.nhacsi.data.class}">{LANG.author}</a></td>
						<td><a href="{DATA_ORDER.dt.data.url}" title="{DATA_ORDER.dt.data.title}" class="{DATA_ORDER.dt.data.class}">{LANG.dt}</a></td>
						<td class="center" style="width:30px">{LANG.select}</td>
					</tr>
				</thead>
				<!-- BEGIN: row -->
				<tbody{CLASS}>
					<tr>
						<td><strong>{ROW.id}</strong></td>
						<td>{ROW.ten}</td>
						<td>{ROW.theloai}</td>
						<td>{ROW.singer}</td>
						<td>{ROW.author}</td>
						<td>{ROW.dt}</td>
						<td class="center"><a title="" onclick="nv_close_pop('{ROW.id}','{ROW.ten}');" href="javascript:void(0);">{LANG.select}</a></td>
					</tr>
				</tbody>
				<!-- END: row -->
				<!-- BEGIN: generate_page -->
				<tbody>
					<tr>
						<td colspan="5" style="text-align:center">
							<div class="fr generatePage">
							{GENERATE_PAGE}
							</div>
						</td>
					</tr>
				</tbody>
				<!-- END: generate_page -->
			</table>
			<script type="text/javascript">
			function nv_close_pop (id,name){
				var songlist = "{SONGLIST}";
				
				if( songlist == "" ) songlist = id;
				else songlist = songlist + "," + id;
				
				$("#listsong-area", opener.document).append('<li class="'+id+'">'+name+'<span onclick="nv_del_song_fromalbum('+id+')" class="delete_icon">&nbsp;</span></li>');
				$("input[name=listsong]", opener.document).val(songlist);
				window.close()
			}
			</script>
		</div>
	</body>
</html>
<!--  END: main  -->
