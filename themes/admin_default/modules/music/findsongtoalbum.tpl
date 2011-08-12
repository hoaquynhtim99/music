<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Insert title here</title>
		
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
		<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
		<style type="text/css">
			.exp_time {
			    line-height: 20px;
			}
			
			.exp_time input {
			    float: left;
			}
			
			.exp_time img {
			    float: left;
			    margin: 2px;
			}
		</style>
		<script type="text/javascript">
			//<![CDATA[
			var nv_siteroot = "{NV_BASE_SITEURL}";
			var htmlload = '<tr><td align="center" colspan="2"><img alt="" src="{NV_BASE_SITEURL}/images/load_bar.gif"/></td></tr>';
			//]]>
		</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
	</head>
	<body>
	<div style="padding:5px">
	<table class="tab1" style="margin-bottom:0px">
		<thead>
			<tr>
				<td style="width:30px;">ID</td>
				<td style="width:100px;">{LANG.song_name}</td>
				<td>{LANG.singer}</td>
				<td style="width:100px;">{LANG.author}</td>
				<td style="width:50px;white-space:nowrap;text-align:center">{LANG.select}</td>
			</tr>
		</thead>
	</table>
	<div id="data">
	<!-- BEGIN: data -->
		<table class="tab1">
			<!-- BEGIN: row -->
			<tbody{CLASS}>
				<tr>
					<td style="width:30px;">
						<strong>{ROW.id}</strong>
					</td>
					<td style="width:200px;">
						{ROW.tenthat}
					</td>
					<td>
						{ROW.casi}
					</td>
					<td style="width:100px;white-space:nowrap;">
						{ROW.nhacsi}
					</td>
					<td style="width:50px;text-align:center">
						<input type="checkbox" name="user" value="{ROW.id}"{ROW.checked} />
					</td>
				</tr>
			</tbody>
			<!-- END: row -->
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="5" style="text-align:center">
						<div id="loadpage">
						{GENERATE_PAGE}
						</div>
					</td>
				</tr>
			</tbody>
			<!-- END: generate_page -->
			<tfoot>
				<tr>
					<td colspan="5" style="text-align:right">
						<input type="button" value="{LANG.checkall}" id="checkall" /> 
						<input type="button" value="{LANG.uncheckall}" id="uncheckall" />
						<script type="text/javascript">
						$(document).ready(function() 
						{
							$('#checkall').click(function()
							{
								$('input:checkbox').each(function()
								{
									songlist = songlist.split( "," );
									if ( songlist[0] == "" ) songlist = new Array();

									var inlist = 0;
									var i = 0;
									for ( i = 0; i < songlist.length; i ++ )
									{
										if ( $(this).attr('value') == songlist[i] )
										{
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 )
									{
										songlist.push($(this).attr('value'));				
									}
									songlist = songlist.toString();
									$(this).attr('checked', 'checked');
								});
							});
							
							$('#uncheckall').click(function()
							{
								$('input:checkbox').each(function()
								{
									songlist = songlist.split( "," );
									if ( songlist[0] == "" ) songlist = new Array();

									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < songlist.length; i ++ )
									{
										if ( $(this).attr('value') != songlist[i] )
										{
											listtemp.push(songlist[i]);
										}
									}
									songlist = listtemp;				
									songlist = songlist.toString();
									$(this).removeAttr('checked');
								});
							});
							$("input[name=user]").click(function() {
								songlist = songlist.split( "," );
								if ( songlist[0] == "" ) songlist = new Array();
								if ( $(this).attr('checked') )
								{
									var inlist = 0;
									var i = 0;
									for ( i = 0; i < songlist.length; i ++ )
									{
										if ( $(this).attr('value') == songlist[i] )
										{
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 )
									{
										songlist.push($(this).attr('value'));				
									}
								}
								else
								{								
									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < songlist.length; i ++ )
									{
										if ( $(this).attr('value') != songlist[i] )
										{
											listtemp.push(songlist[i]);
										}
									}
									songlist = listtemp;				
								}
								songlist = songlist.toString();
							});
						});
						</script>
					</td>
				</tr>
			</tfoot>
		</table>
		<!-- END: data -->
	</div>
	<div style="text-align:center"><input type="button" value="{LANG.complete}" name="complete" id="complete" /></div>
	<script type="text/javascript">
		var songlist = "{songlist}"; 
		function nv_load_user( url, tagsid )
		{
			url = rawurldecode ( url ) + "&getdata=1&songlist=" + songlist;
			$('div#data').html('<div style="padding:5px;text-align:center"><img alt="" src="{NV_BASE_SITEURL}images/load_bar.gif" /></div>');
			$('div#data').load(url);
			return;
		}
		$("input[name=complete]").click(function() {
			$('#tmp-song-name').html('');
			$('#tmp-song-name').load('{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&loadname=1&songlist='+songlist,
				function(){
					nv_return();
				}
			);
		});		
		function nv_return()
		{
			$("#listsong-area", opener.document).html($('#tmp-song-name').html());
			$("input[name=listsong]", opener.document).val(songlist);
			window.close()
		}
	</script>
	<div style="display:none;visibility:hidden" id="tmp-song-name"></div>
	</div>
	</body>
</html>
<!-- END: main -->