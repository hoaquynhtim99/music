<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.getalbumid_title}</title>
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">var nv_siteroot = "{NV_BASE_SITEURL}";</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
	</head>
	<body>
	<div style="padding:5px">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td class="col-id center">ID</td>
				<td>{LANG.album}</td>
				<td class="col-sathor">{LANG.singer}</td>
				<td class="center col-feature">{LANG.select}</td>
			</tr>
		</thead>
	</table>
	<div id="data">
		<!-- BEGIN: data -->
		<table class="table table-striped table-bordered table-hover">
			<tbody>
			<!-- BEGIN: row -->
				<tr>
					<td class="center col-id"><strong>{ROW.id}</strong></td>
					<td>{ROW.title}</td>
					<td class="col-sathor">{ROW.singers}</td>
					<td class="center col-feature"><input type="checkbox" name="albumid" value="{ROW.id}"{ROW.checked} /></td>
				</tr>
			<!-- END: row -->
			</tbody>
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="5" class="text-center">
						<div id="loadpage">{GENERATE_PAGE}</div>
					</td>
				</tr>
			<!-- END: generate_page -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5" style="text-align:right">
						<input class="music-button-2" type="button" value="{LANG.checkall}" id="checkall" /> 
						<input class="music-button-2" type="button" value="{LANG.uncheckall}" id="uncheckall" />
						<script type="text/javascript">
						$(document).ready(function(){
							$('#checkall').click(function(){
								$('input:checkbox').each(function(){
									albums = albums.split( "," );
									if ( albums[0] == "" ) albums = new Array();

									var inlist = 0;
									var i = 0;
									for ( i = 0; i < albums.length; i ++ ){
										if ( $(this).attr('value') == albums[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										albums.push($(this).attr('value'));				
									}
									albums = albums.toString();
									$(this).attr('checked', 'checked');
								});
							});
							
							$('#uncheckall').click(function(){
								$('input:checkbox').each(function(){
									albums = albums.split( "," );
									if ( albums[0] == "" ) albums = new Array();

									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < albums.length; i ++ ){
										if ( $(this).attr('value') != albums[i] ){
											listtemp.push(albums[i]);
										}
									}
									albums = listtemp;				
									albums = albums.toString();
									$(this).removeAttr('checked');
								});
							});
							$("input[name=albumid]").click(function() {
								albums = albums.split( "," );
								if ( albums[0] == "" ) albums = new Array();
								if ( $(this).attr('checked') ){
									var inlist = 0;
									var i = 0;
									for ( i = 0; i < albums.length; i ++ ){
										if ( $(this).attr('value') == albums[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										albums.push($(this).attr('value'));				
									}
								}else{								
									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < albums.length; i ++ ){
										if ( $(this).attr('value') != albums[i] ){
											listtemp.push(albums[i]);
										}
									}
									albums = listtemp;				
								}
								albums = albums.toString();
							});
						});
						</script>
					</td>
				</tr>
			</tfoot>
		</table>
		<!-- END: data -->
	</div>
	<div style="text-align:center"><input type="button" value="{LANG.complete}" name="complete" id="complete" class="music-button"/></div>
	<script type="text/javascript">
		var albums = "{LISTALBUM}"; 
		function nv_load_page( url, tagsid ){
			url = rawurldecode ( url ) + "&getdata=1&area={RETURNAREA}&input={RETURNINPUT}&listalbum=" + albums;
			$('div#data').html('<div style="padding:5px;text-align:center"><img alt="Loading" src="{NV_BASE_SITEURL}images/load_bar.gif" /></div>');
			$('div#data').load(url);
			return;
		}
		$("input[name=complete]").click(function(){
			$('#tmp-data').html('');
			$('#tmp-data').load( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&findListAndReturn=1&loadname=1&area={RETURNAREA}&input={RETURNINPUT}&listalbum=' + albums, function(){
				nv_return();
			});
		});		
		function nv_return(){
			$("#{RETURNAREA}", opener.document).html($('#tmp-data').html());
			$("input[name={RETURNINPUT}]", opener.document).val(albums);
			window.close()
		}
	</script>
	<div style="display:none;visibility:hidden" id="tmp-data"></div>
	</div>
	</body>
</html>
<!-- END: main -->