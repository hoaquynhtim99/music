<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.getvideoid_title}</title>
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
	<table class="tab1">
		<thead>
			<tr>
				<td class="col-id center">ID</td>
				<td>{LANG.video}</td>
				<td class="col-sathor">{LANG.singer}</td>
				<td class="col-sathor">{LANG.author}</td>
				<td class="center col-feature">{LANG.select}</td>
			</tr>
		</thead>
	</table>
	<div id="data">
		<!-- BEGIN: data -->
		<table class="tab1">
			<!-- BEGIN: row -->
			<tbody{CLASS}>
				<tr>
					<td class="center col-id"><strong>{ROW.id}</strong></td>
					<td>{ROW.title}</td>
					<td class="col-sathor">{ROW.singers}</td>
					<td class="col-sathor">{ROW.authors}</td>
					<td class="center col-feature"><input type="checkbox" name="videoid" value="{ROW.id}"{ROW.checked} /></td>
				</tr>
			</tbody>
			<!-- END: row -->
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="5" class="center">
						<div id="loadpage">{GENERATE_PAGE}</div>
					</td>
				</tr>
			</tbody>
			<!-- END: generate_page -->
			<tfoot>
				<tr>
					<td colspan="5" style="text-align:right">
						<input class="music-button-2" type="button" value="{LANG.checkall}" id="checkall" /> 
						<input class="music-button-2" type="button" value="{LANG.uncheckall}" id="uncheckall" />
						<script type="text/javascript">
						$(document).ready(function(){
							$('#checkall').click(function(){
								$('input:checkbox').each(function(){
									videos = videos.split( "," );
									if ( videos[0] == "" ) videos = new Array();

									var inlist = 0;
									var i = 0;
									for ( i = 0; i < videos.length; i ++ ){
										if ( $(this).attr('value') == videos[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										videos.push($(this).attr('value'));				
									}
									videos = videos.toString();
									$(this).attr('checked', 'checked');
								});
							});
							
							$('#uncheckall').click(function(){
								$('input:checkbox').each(function(){
									videos = videos.split( "," );
									if ( videos[0] == "" ) videos = new Array();

									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < videos.length; i ++ ){
										if ( $(this).attr('value') != videos[i] ){
											listtemp.push(videos[i]);
										}
									}
									videos = listtemp;				
									videos = videos.toString();
									$(this).removeAttr('checked');
								});
							});
							$("input[name=videoid]").click(function() {
								videos = videos.split( "," );
								if ( videos[0] == "" ) videos = new Array();
								if ( $(this).attr('checked') ){
									var inlist = 0;
									var i = 0;
									for ( i = 0; i < videos.length; i ++ ){
										if ( $(this).attr('value') == videos[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										videos.push($(this).attr('value'));				
									}
								}else{								
									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < videos.length; i ++ ){
										if ( $(this).attr('value') != videos[i] ){
											listtemp.push(videos[i]);
										}
									}
									videos = listtemp;				
								}
								videos = videos.toString();
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
		var videos = "{LISTVIDEO}"; 
		function nv_load_page( url, tagsid ){
			url = rawurldecode ( url ) + "&getdata=1&area={RETURNAREA}&input={RETURNINPUT}&listvideo=" + videos;
			$('div#data').html('<div style="padding:5px;text-align:center"><img alt="Loading" src="{NV_BASE_SITEURL}images/load_bar.gif" /></div>');
			$('div#data').load(url);
			return;
		}
		$("input[name=complete]").click(function(){
			$('#tmp-data').html('');
			$('#tmp-data').load( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&findListAndReturn=1&loadname=1&area={RETURNAREA}&input={RETURNINPUT}&listvideo=' + videos, function(){
				nv_return();
			});
		});		
		function nv_return(){
			$("#{RETURNAREA}", opener.document).html($('#tmp-data').html());
			$("input[name={RETURNINPUT}]", opener.document).val(videos);
			window.close()
		}
	</script>
	<div style="display:none;visibility:hidden" id="tmp-data"></div>
	</div>
	</body>
</html>
<!-- END: main -->