<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="header-block1"> 
		<h3><span>{LANG.playlist_edit}</span></h3> 
	</div> 
	<!-- BEGIN: sucess -->
	<div class="sucess">
		<p>{LANG.playlist_edit_sucess}<a href="{url_play}"> <strong>{LANG.playlist_edit_sucess1} </strong></a>{LANG.playlist_edit_sucess2} <a href="{url_back}"><strong> {LANG.playlist_edit_sucess3}</strong></a> {LANG.playlist_edit_sucess4}</p>
	</div>
	<!-- END: sucess -->
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.playlist_song}</strong>
		</div>
	</div>
	<div class="clear"></div>
	<div style="padding: 10px;">
	<!-- BEGIN: loop -->
	<div id="song{songid}">
		<div style="padding-right:15px;" class="tools">
			<a class="del" onclick="delsongfrplaylist('{songid}', '{INFO.id}');" title="{LANG.del}"></a>
		</div>
		<div class="listsong">&nbsp;&nbsp;<a href="{url_view}" style="color: #000;">{songname}</a> - <a href="{url_search_singer}">{songsinger}</a>
		</div>
	</div>
	<!-- END: loop -->
	</div>
	<div id="album_info">
		<img border="0" src="{img}" width="90px" height="90px" />
		<form id="editplaylist" style="float:right;width:50%" method="post" action="{ACTION}">
			<p style="float:right;width:50%;height:6px;">&nbsp;</p>
			<input style="width: 220px" type="text" name="name" id="name" value="{INFO.name}" />
			<input style="width: 220px" type="text" name="singer" id="singer" value="{INFO.singer}" />
			<textarea style="width: 218px" rows="5" name="message" id="message" >{INFO.message}</textarea>
			<input type="hidden" name="ok" value="1" />
			<input class="submitbutton" style="width: 50px" type="button" value="{LANG.save}"/>
		</form>
		<p><br />
		{LANG.album_name}:<br /><br />
		{LANG.album_singer}:</span><br /><br />
		{LANG.message}:</p>
	</div>	
	<div class="clear"></div>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".submitbutton").click(function() {
			if( document.getElementById('name').value == '')
			{
				alert('{LANG.err_ful_aname}');
				document.getElementById('name').focus();
			}else if( document.getElementById('singer').value == ''){
				alert('{LANG.err_ful_asinger}');
				document.getElementById('singer').focus();
			}else if( document.getElementById('message').value == ''){
				alert('{LANG.err_ful_amessage}');
				document.getElementById('message').focus();
			}else $("#editplaylist").submit();
		});
	});
	</script>
</div>
<!-- END: main -->