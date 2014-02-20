<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.getalbumid_title}</title>
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript"> var nv_siteroot = "{NV_BASE_SITEURL}";</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
	</head>
	<body>
		<div id="getuidcontent">
			<form id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
			<input type="hidden" name="findOneAndReturn" value="1" />
			<input type="hidden" name="authors" value="{AUTHORS}" />
			<input type="hidden" name="area" value="{RETURNAREA}" />
			<input type="hidden" name="input" value="{RETURNINPUT}" />
			<table class="tab1">
				<tbody class="second">
					<tr><td colspan="8" class="center green"><strong>{LANG.getalbumid_title}</strong></td></tr>
				</tbody>
				<tbody>
					<tr>
						<td>{LANG.album_name}</td>
						<td><input class="music-input col-date" type="text" name="q" value="{SEARCH.q}"/></td>
						<td>{LANG.singer}</td>
						<td><input class="music-input col-date" type="text" name="singer" value="{SEARCH.singer}"/></td>
						<td class="col-status"><input type="submit" name="submit" value="{LANG.search}" class="music-button"/></td>
						<td class="col-status"><input type="button" onclick="window.location='{URLCANCEL}';" value="{LANG.filter_cancel}" class="music-button"/></td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="resultdata">
			<table class="tab1">
				<thead>
					<tr>
						<td class="col-id center">ID</td>
						<td class="col-image center">{LANG.thumb}</td>
						<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.album}</a></td>
						<td>{LANG.singer}</td>
						<td class="center col-feature">{LANG.select}</td>
					</tr>
				</thead>
				<!-- BEGIN: row -->
				<tbody{CLASS}>
					<tr>
						<td class="center"><strong>{ROW.id}</strong></td>
						<td class="center"><img src="{ROW.thumb}" alt="{ROW.title}" height="50"/></td>
						<td>{ROW.title}</td>
						<td>{ROW.singers}</td>
						<td class="center"><a class="select-icon nounderline" title="{LANG.select}" onclick="nv_close_pop('{ROW.id}','{ROW.title}');" href="javascript:void(0);">{LANG.select}</a></td>
					</tr>
				</tbody>
				<!-- END: row -->
				<!-- BEGIN: generate_page -->
				<tbody>
					<tr>
						<td colspan="4" class="center">{GENERATE_PAGE}</td>
					</tr>
				</tbody>
				<!-- END: generate_page -->
			</table>
			<script type="text/javascript">
			function nv_close_pop( id, name ){
				var listalbum = "{LISTALBUM}";
				
				if( listalbum == "" ) listalbum = id;
				else listalbum = listalbum + "," + id;
				
				$("#{RETURNAREA}", opener.document).append('<li class="' + id + '">' + name + '<span onclick="nv_del_item_on_list(' + id + ', \'{RETURNAREA}\', \'{LANG.author_del_confirm}\', \'{RETURNINPUT}\');" class="delete-icon">&nbsp;</span></li>');
				$("input[name={RETURNINPUT}]", opener.document).val(listalbum);
				window.close()
			}
			</script>
		</div>
	</body>
</html>
<!--  END: main  -->