<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"><span>{LANG.playlist_edit}</span></div>
		<div class="alcontent">
			<blockquote>{LANG.playlist_song}</blockquote>
		<!-- BEGIN: loop -->
		<div id="song{ROW.songid}" class="listsong">
			<ul class="mtool">
				<li><a title="{LANG.del}" href="javascript:void(0);" class="mdel" onclick="delsongfrplaylist('{ROW.songid}', '{INFO.id}', '{LANG.cofirm_del}');">&nbsp;</a></li>
			</ul>
			<a class="musicicon mplay" href="{ROW.url_view}" title="{ROW.songname}">{ROW.songname}</a> - <a class="singer" title="{ROW.songsinger}" href="{ROW.url_search_singer}">{ROW.songsinger}</a>
			<div class="clear"></div>
			<div class="hr"></div>
		</div>
		<!-- END: loop -->
		</div>
	</div>
</div>
<!-- BEGIN: sucess -->
<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.playlist_edit_sucess}<a href="{url_play}"> <strong>{LANG.playlist_edit_sucess1} </strong></a>{LANG.playlist_edit_sucess2} <a href="{url_back}"><strong> {LANG.playlist_edit_sucess3}</strong></a> {LANG.playlist_edit_sucess4}<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
<!-- END: sucess -->
<div class="alboxw">
	<div class="alwrap alcontent">
		<form id="editplaylist" method="post" action="{ACTION}">
			<table cellpadding="0" cellspacing="0" class="musictable">
				<tr>
					<td style="width:100px" class="veraltop">
						<img class="musicsmalllalbum" src="{img}" width="90" height="90" alt="Random image" />
					</td>
					<td>
						<table cellpadding="0" cellspacing="0" class="musictable">
							<tr>
								<td style="width:100px">{LANG.album_name}</td>
								<td><input class="txt-full" type="text" name="name" id="name" value="{INFO.name}"/></td>
							</tr>
							<tr>
								<td>{LANG.album_singer}</td>
								<td><input class="txt-full" type="text" name="singer" id="singer" value="{INFO.singer}"/></td>
							</tr>
							<tr>
								<td>{LANG.message}</td>
								<td><textarea class="txt-full" style="height:100px" name="message" id="message" >{INFO.message}</textarea></td>
							</tr>
							<tr>
								<td colspan="2" class="mcenter">
									<input type="hidden" name="img" value="{img}" />
									<input type="hidden" name="ok" value="1" />
									<input id="submitpl" class="mbutton" type="button" value="{LANG.save}"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#submitpl").click(function(){
		if( document.getElementById('name').value == ''){
			alert('{LANG.err_ful_aname}');
			document.getElementById('name').focus();
		}else if( document.getElementById('singer').value == ''){
			alert('{LANG.err_ful_asinger}');
			document.getElementById('singer').focus();
		}else if( document.getElementById('message').value == ''){
			alert('{LANG.err_ful_amessage}');
			document.getElementById('message').focus();
		}else{
			$("#submitpl").attr('disabled','disabled');
			$("#editplaylist").submit();
		}
	});
});
</script>
<!-- END: main -->