<!-- BEGIN: main -->
<!-- BEGIN: noaccess -->
<div class="box-border-shadow m-bottom">
	<p style="width:100%;float:left;" align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
</div>
<!-- END: noaccess -->
<!-- BEGIN: access -->
<!-- BEGIN: creatlist -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.album_creat}</strong>
		</div>
	</div>
	<div style="padding: 10px;">
		<p><strong>{LANG.playlist_song}</strong></p>
		<!-- BEGIN: loop -->
		<div id="song{stt}" class="listsong">&nbsp;&nbsp;{songname} - {singer}<div onclick="delsongfrlist('{stt}');" class="tools"></div></div>
		<!-- END: loop -->
	</div>
	<div id="album_info">
		<img border="0" src="{img}" width="90px" height="90px" />
		<form style="float:right;width:50%">
			<p style="float:right;width:50%;height:6px;">&nbsp;</p>
			<input style="width: 220px" type="text" name="name" id="name" />
			<input style="width: 220px" type="text" name="singer" id="singer" />
			<textarea style="width: 218px" rows="5" name="message" id="message" ></textarea>
			<input type="hidden" name="img" value="{img}" />
			<input class="submitbutton" style="width: 50px" type="button" value="{LANG.save}"/>
		</form>
		<p><br />
		{LANG.album_name}:<br /><br />
		{LANG.album_singer}:</span><br /><br />
		{LANG.message}:</p>
	</div>	
	<div class="clear"></div>
</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".submitbutton").click(function() {
			var name = document.getElementById('name').value;
			var singer = document.getElementById('singer').value;
			var message  = strip_tags(document.getElementById('message').value);
			var num = {num};
			if ( num > 7)
			{
				alert('{LANG.err_ful_playlist} ' + 7 + ' playlist');
			}else
			{
				if( name == '')
				{
					alert('{LANG.err_ful_aname}');
					document.getElementById('name').focus();
				}else if( singer == ''){
					alert('{LANG.err_ful_asinger}');
					document.getElementById('singer').focus();
				}else if( message == ''){
					alert('{LANG.err_ful_amessage}');
					document.getElementById('message').focus();
				}else saveplaylist( name, singer, message);
			}
		});
	});
	</script>
<!-- END: creatlist -->
<div class="box-border-shadow m-bottom">
	<div id="searchcontent">
		<div class="box-border m-bottom"> 
			<div class="header-block1"> 
				<h3><span>{LANG.your_playlist}</span></h3> 
			</div> 
			<h2 class="album">{LANG.there_are} {num} playlist</h2>
			<!-- BEGIN: list -->
			<div id="item{id}" class="resuirt">
				<div onclick="dellist('{id}');" class="tools"></div>
				<a href="{url_view}" class="namealbum">
				<img class="album" width="90px" height="90px" border="0" src="{playlist_img}" />
				<div id="album">
					<p><strong>{name}</strong></a></p>
					<p>{LANG.show_1}: {singer}</p>
					<p>{LANG.playlist_creat}: {date} | {LANG.view}: {view}</p>
				</div>
				<div id="boder"></div>
			</div>
			<!-- END: list -->
			<div class="clear"></div>
		</div>
	</div>
</div>
<!-- END: access -->
<!-- END: main -->