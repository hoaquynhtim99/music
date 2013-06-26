<!-- BEGIN: main -->
<!-- BEGIN: noaccess -->
<div class="alboxw">
	<div class="alwrap alcontent infoerror">
		<div>
			{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}
		</div>
	</div>
</div>
<!-- END: noaccess -->
<!-- BEGIN: access -->
<!-- BEGIN: creatlist -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"><span>{LANG.album_creat}</span></div>
		<div class="alcontent">
			<blockquote>{LANG.playlist_song}</blockquote>
		<!-- BEGIN: loop -->
		<div id="song{SONG.stt}" class="listsong">
			<ul class="mtool">
				<li><a title="{LANG.del}" href="javascript:void(0);" class="mdel" onclick="delsongfrlist('{SONG.stt}', '{LANG.cofirm_del}');">&nbsp;</a></li>
			</ul>
			<a class="musicicon mplay" href="{SONG.url_view}" title="{SONG.songname}">{SONG.songname}</a> - <a class="singer" title="{SONG.singer}" href="{SONG.url_search_singer}">{SONG.singer}</a>
			<div class="clear"></div>
			<div class="hr"></div>
		</div>
		<!-- END: loop -->
		</div>
	</div>
</div>
<div class="alboxw">
	<div class="alwrap alcontent">
		<table cellpadding="0" cellspacing="0" class="musictable">
			<tr>
				<td style="width:100px" class="veraltop">
					<img class="musicsmalllalbum" src="{img}" width="90" height="90" alt="Random image" />
				</td>
				<td>
					<table cellpadding="0" cellspacing="0" class="musictable">
						<tr>
							<td style="width:100px">{LANG.album_name}</td>
							<td><input class="txt-full" type="text" name="name" id="name"/></td>
						</tr>
						<tr>
							<td>{LANG.album_singer}</td>
							<td><input class="txt-full" type="text" name="singer" id="singer"/></td>
						</tr>
						<tr>
							<td>{LANG.message}</td>
							<td><textarea class="txt-full" style="height:100px" name="message" id="message" ></textarea></td>
						</tr>
						<tr>
							<td colspan="2" class="mcenter">
								<input type="hidden" name="img" value="{img}" />
								<input id="submitpl" class="mbutton" type="button" value="{LANG.save}"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#submitpl").click(function(){
			var name = document.getElementById('name').value;
			var singer = document.getElementById('singer').value;
			var message  = strip_tags(document.getElementById('message').value);
			var num = {GDATA.num};
			if ( num > {GDATA.playlist_max}){
				alert('{LANG.err_ful_playlist} ' + {GDATA.playlist_max} + ' playlist');
			}else{
				if( name == ''){
					alert('{LANG.err_ful_aname}');
					document.getElementById('name').focus();
				}else if( singer == ''){
					alert('{LANG.err_ful_asinger}');
					document.getElementById('singer').focus();
				}else if( message == ''){
					alert('{LANG.err_ful_amessage}');
					document.getElementById('message').focus();
				}else saveplaylist(name,singer,message);
			}
		});
	});
</script>
<!-- END: creatlist -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"><span>{LANG.your_playlist}</span></div>
		<div class="alcontent creatpl">
			<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.there_are} {GDATA.num} playlist<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
			<!-- BEGIN: list -->
			<div id="item{PLIST.id}">
				<ul class="mtool">
					<li><a title="{LANG.playlist_listen1}" href="{PLIST.url_view}" class="mplay">&nbsp;</a></li>
					<li><a title="{LANG.playlist_edit}" href="{PLIST.url_edit}" class="medit">&nbsp;</a></li>
					<li><a title="{LANG.playlist_delete}" href="javascript:void(0);" class="mdel" onclick="dellist('{PLIST.id}', '{LANG.cofirm_del}');">&nbsp;</a></li>
				</ul>
				<a title="{PLIST.name}" href="{PLIST.url_view}"><img class="musicsmalllalbum fl" width="90" height="90" src="{PLIST.playlist_img}"/></a>
				<a title="{PLIST.name}" href="{PLIST.url_view}">
				<p><strong>{PLIST.name}</strong></a></p>
				<p>{LANG.show_1}: <span class="greencolor">{PLIST.singer}</span></p>
				<p>{LANG.playlist_creat}: <span class="greencolor">{PLIST.date}</span> | {LANG.view}: <span class="greencolor">{PLIST.view}</span></p>
				<div class="clear"></div>
				<div class="hr"></div>
			</div>
			<!-- END: list -->
		</div>
	</div>
</div>
<!-- END: access -->
<!-- END: main -->