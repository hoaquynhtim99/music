<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.pagetitle1}</title>
		
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">
			//<![CDATA[
			var nv_siteroot = "{NV_BASE_SITEURL}";
			//]]>
		</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
		
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/popcalendar/popcalendar.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/shadowbox/shadowbox.js"></script>
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}js/shadowbox/shadowbox.css" />
		<script type="text/javascript">
		Shadowbox.init();
		</script>
	</head>
	<body>
		<div id="getuidcontent">
			<form id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
			<input type="hidden" name="area" value="{AREA}" />
			<table class="tab1">
				<tbody>
					<tr>
						<td>
							{LANG.album_name}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="title" value="{SEARCH.title}" maxlength="100" />
						</td>
						<td>
							{LANG.describle}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="describe" value="{SEARCH.describe}" maxlength="100" />
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
							{LANG.singer}
						</td>
						<td>
							<input class="fixwidthinput" type="text" name="casi" value="{SEARCH.casi}" maxlength="100" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td colspan="4" class="center">
							<input type="submit" name="submit" value="{LANG.search}"/>
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="resultdata">
			<!-- BEGIN: resultdata -->
            <!-- BEGIN: data -->
			<table class="tab1">
				<thead>
					<tr>
						<td>ID</td>
						<td>{LANG.album_name}</td>
						<td>{LANG.singer}</td>
						<td>{LANG.who_up}</td>
						<td class="center">
							{LANG.select}
						</td>
					</tr>
				</thead>
				<!-- BEGIN: row -->
				<tbody{CLASS}>
					<tr>
						<td style="width:30px;">
							<strong>{ROW.id}</strong>
						</td>
						<td>
							{ROW.tname}
						</td>
						<td style="width:100px;">
							{ROW.tenthat}
						</td>
						<td style="width:100px;white-space:nowrap;">
							{ROW.upboi}
						</td>
						<td style="width:50px;text-align:center">
							<a title="" onclick="nv_close_pop('{ROW.id}');" href="javascript:void(0);">{LANG.select}</a>
						</td>
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
			function nv_close_pop ( name ) {
			  $( "#{AREA}", opener.document ).val( name );
			  window.close();
			}
			</script>
			<!-- END: data -->
			<!-- BEGIN: nodata -->
			<table class="tab1">
				<tbody>
					<tr>
						<td class="center">
							{LANG.noresult}
						</td>
					</tr>
				</tbody>
			</table>
			<!-- END: nodata -->
			<!-- END: resultdata -->		
		</div>
	</body>
</html>
<!--  END: main  -->
