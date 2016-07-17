<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.author_add2}</title>
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
				<td class="col-image center">{LANG.thumb}</td>
				<td>{LANG.author}</td>
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
					<td class="center col-image"><img src="{ROW.thumb}" alt="{ROW.title}" width="50" height="50"/></td>
					<td>{ROW.title}</td>
					<td class="center col-feature"><input type="checkbox" name="authorid" value="{ROW.id}"{ROW.checked} /></td>
				</tr>
			<!-- END: row -->
			</tbody>
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="4" class="text-center">
						<div id="loadpage">{GENERATE_PAGE}</div>
					</td>
				</tr>
			<!-- END: generate_page -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" style="text-align:right">
						<input class="music-button-2" type="button" value="{LANG.checkall}" id="checkall" /> 
						<input class="music-button-2" type="button" value="{LANG.uncheckall}" id="uncheckall" />
						<script type="text/javascript">
						$(document).ready(function(){
							$('#checkall').click(function(){
								$('input:checkbox').each(function(){
									authors = authors.split( "," );
									if ( authors[0] == "" ) authors = new Array();

									var inlist = 0;
									var i = 0;
									for ( i = 0; i < authors.length; i ++ ){
										if ( $(this).attr('value') == authors[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										authors.push($(this).attr('value'));				
									}
									authors = authors.toString();
									$(this).attr('checked', 'checked');
								});
							});
							
							$('#uncheckall').click(function(){
								$('input:checkbox').each(function(){
									authors = authors.split( "," );
									if ( authors[0] == "" ) authors = new Array();

									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < authors.length; i ++ ){
										if ( $(this).attr('value') != authors[i] ){
											listtemp.push(authors[i]);
										}
									}
									authors = listtemp;				
									authors = authors.toString();
									$(this).removeAttr('checked');
								});
							});
							$("input[name=authorid]").click(function() {
								authors = authors.split( "," );
								if ( authors[0] == "" ) authors = new Array();
								if ( $(this).attr('checked') ){
									var inlist = 0;
									var i = 0;
									for ( i = 0; i < authors.length; i ++ ){
										if ( $(this).attr('value') == authors[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										authors.push($(this).attr('value'));				
									}
								}else{								
									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < authors.length; i ++ ){
										if ( $(this).attr('value') != authors[i] ){
											listtemp.push(authors[i]);
										}
									}
									authors = listtemp;				
								}
								authors = authors.toString();
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
		var authors = "{AUTHORS}"; 
		function nv_load_page( url, tagsid ){
			url = rawurldecode ( url ) + "&getdata=1&area={RETURNAREA}&input={RETURNINPUT}&authors=" + authors;
			$('div#data').html('<div style="padding:5px;text-align:center"><img alt="Loading" src="{NV_BASE_SITEURL}images/load_bar.gif" /></div>');
			$('div#data').load(url);
			return;
		}
		$("input[name=complete]").click(function(){
			$('#tmp-data').html('');
			$('#tmp-data').load( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&findListAndReturn=1&loadname=1&area={RETURNAREA}&input={RETURNINPUT}&authors=' + authors, function(){
				nv_return();
			});
		});		
		function nv_return(){
			$("#{RETURNAREA}", opener.document).html($('#tmp-data').html());
			$("input[name={RETURNINPUT}]", opener.document).val(authors);
			window.close()
		}
	</script>
	<div style="display:none;visibility:hidden" id="tmp-data"></div>
	</div>
	</body>
</html>
<!-- END: main -->