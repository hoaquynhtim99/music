<!-- BEGIN: main -->
<script type="text/javascript" src="{GDATA.data_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_song}</strong>
		</div>
	</div>
<div id="listen_main">
	<!-- BEGIN: hit -->
	<div style="margin:10px 10px 0px 0px;" class="hitsong"></div>
	<!-- END: hit -->
	<strong>{name}</strong>
	<p>{LANG.show}: <a href="{SDATA.url_search_singer}">{SDATA.song_singer}</a></p>
	<p>{LANG.category_2}:
		<a href="{SDATA.url_search_category}"> {SDATA.song_cat}&nbsp;</a>
		<!-- BEGIN: cat -->
		<!-- BEGIN: loop -->
		 / <a href="{CAT.url}" title="{CAT.title}">{CAT.title}&nbsp;</a>
		<!-- END: loop -->
		<!-- END: cat -->
		| {LANG.view}: {SDATA.song_numview}
	</p>
	<p>{LANG.author}: {SDATA.song_author}. Album: <a href="{SDATA.url_search_album}">{SDATA.album_name}
	</a></p>
	<div class="playercontainer">
		<div class="music-ads">
			<a onclick="this.target='_blank';" class="fixads" href="{GDATA.ads_data.url}" title="">&nbsp;</a>
			<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,1,1,0" width="470" height="120">
				<param name="src" value="{GDATA.ads_data.link}" />
				<param name="loop" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="wmode" value="transparent" />
				<param name="menu" value="false" />
				<embed src="{GDATA.ads_data.link}" pluginspage="http://www.adobe.com/shockwave/download/" width="470" height="120" wmode="transparent" loop="true" menu="false" allowscriptaccess="always"></embed>
			</object>
		</div>
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{GDATA.data_url}player.swf",
			file: "{SDATA.song_link}",
			controlbar: "bottom",
			volume: 100,
			height: 24,
			width: 470,
			repeat: "list",
			autostart: "true"
		});
		</script>
		<div class="clear"></div>
	</div>
</div>
</div>
<ul class="tool">
	<li class="active"><a class="give" href="#tab1">{LANG.send_to}</a></li>
	<li class="active"><a class="lyric" href="#tab2">Lyric</a></li>
	<li class="active"><a class="error" href="#tab3">{LANG.give_error}</a></li>
	<li class="active"><a name="{SDATA.song_id}" id="add" class="add" href="#tab4">{LANG.add}</a></li>
	<li class="active"><a class="down" href="#tab5" >Download</a></li>
</ul>
<div style="width:498px;" id="boderfull" class="tab_container">
	<div id="tab1" class="tab_content">
		<div class="sendtool">
			<a onclick="window.open(this.href); return false;"  href="http://www.facebook.com/sharer.php?u={GDATA.selfurl_encode}&amp;t={SDATA.song_name}-{SDATA.song_singer}" class="facebook">Facebook   </a>
			<a class="sendtomail" href="javascript:void(0);" onclick="NewWindow('{SDATA.send_mail_url}','{SDATA.send_mail_title}','500','500','no');return false">{LANG.sendtomail}</a>
			<a class="votesong" href="javascript:void(0);" onclick="votethissong('{SDATA.song_id}');"><strong>{LANG.song_vote}</strong></a><div id="vote">({SDATA.song_vote})</div>
		</div>
		<form action="#" method="post">
			<fieldset>
			<p>{LANG.link_song}:
			<input id="linksong" onclick="Select_all('linksong');" type="text" value="{GDATA.selfurl_base}" readonly="readonly" /> </p>
			<p>{LANG.blog_song}:
			<input id="blogsong" onclick="Select_all('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;60&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{GDATA.full_data_url}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;playlistfile={GDATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{GDATA.full_data_url}player.swf&quot; width=&quot;500&quot; height=&quot;60&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;playlistfile={GDATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly" /> </p>
			<p>{LANG.forum_song}:
			<input id="songforum" onclick="Select_all('songforum');" type="text" value="[FLASH]{GDATA.full_data_url}player.swf?playlistfile={GDATA.creat_link_url}[/FLASH]" readonly="readonly" /> </p>
			<script type="text/javascript">
				function Select_all(id){
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
			<p><strong>{LANG.send_to}</strong></p>
		<!-- BEGIN: gift -->
			<div class="giftcontent">
				<p>{LANG.enter_name}:
				<input id="who-send-gift" type="text" value="{GDATA.username}" {CDATA.no_change} /> </p>
				<p>{LANG.who_recive}:
				<input class="txt" id="who-receive-gift" type="text" value="" /> </p>
				<p>{LANG.email_receive}:
				<input class="txt" id="email-receive-gift" type="text" value="" /> </p>
				<p>{LANG.message}:</p>
				<textarea class="txt" id="body-gift" name="message" rows="2" cols="auto"></textarea>
				<p style="text-align:center">
				<input id="send-gift-button" style="width: 50px" onclick="nvms_sendgift('{SDATA.song_id}');" class="submitbutton" type="button" value="{LANG.send}" />
				</p>
			</div>
			<script type="text/javascript">
			function nvms_sendgift(sid){
				var who_send = $('#who-send-gift').val();
				var who_receive = $('#who-receive-gift').val();
				var email_receive = $('#email-receive-gift').val();
				var body = $('#body-gift').val();
				
				if( who_send == '' ){ alert('{LANG.error_gift_send}'); $('#who-send-gift').focus(); return; }
				if( who_receive == '' ){ alert('{LANG.error_gift_recieve}'); $('#who-receive-gift').focus(); return; }
				if( email_receive == '' ){ alert('{LANG.error_empty_email}'); $('#email-receive-gift').focus(); return; }
				if( body == '' ){ alert('{LANG.error_gift_body}'); $('#body-gift').focus(); return; }
				
				$('#send-gift-button').attr('disabled','disabled');
				$.ajax({
					type: 'POST',
					url: nv_siteroot + 'index.php',
					data: nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&send_gift=1&checksess={GDATA.checksess_gift}&id=' + sid + '&who_send=' + who_send + '&who_receive=' + who_receive + '&email_receive=' + email_receive + '&body=' + encodeURIComponent(body),
					success: function(data){
						if(data=='OK'||data=='WAIT'){
							$('.giftcontent .txt').val('');
							if(data=='OK'){
								alert('{LANG.send_gift_suc}');
							}else if(data=='WAIT'){
								alret('{LANG.send_gift_wating}');
							}
							$('#send-gift-button').attr('disabled','');
						}else alert(data);
					}
				});
			}
			</script>
		<!-- END: gift -->
		</fieldset>
		</form>
		<!-- BEGIN: nogift -->
		<p style="width:100%;float:left;text-align:center"><strong>{LANG.you_must} <a href="{GDATA.url_login}">{LANG.loginsubmit}</a> / <a href="{GDATA.url_register}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nogift -->
		<!-- BEGIN: stopgift -->
		<p style="width:100%;float:left;text-align:center"><em>{LANG.setting_stop}</em></p>
		<!-- END: stopgift -->
	</div> 
	<div id="tab2" class="tab_content">
		<div id="playlistcotainer">
			<!-- BEGIN: nolyric -->
				<p style="text-align:center">{LANG.no_lyric}</p>
			<!-- END: nolyric -->
			<!-- BEGIN: lyric -->
			<div id="list{thisdiv}" class="playlistitem">
				<p style="float:left;width:490px;margin-bottom:-20px;">
				<!-- BEGIN: next -->
				<a class="next clicknext" href="#list{nextdiv}"></a>
				<!-- END: next -->
				<!-- BEGIN: prev -->
				<a class="frev clicknext" href="#list{prevdiv}"></a>
				</p>
				<!-- END: prev -->
				<p><strong><a>{SDATA.song_name}</a> - <a>{SDATA.song_singer}</a></strong></p>
				<p><span>{LANG.user_lyric}: {LYRIC_DATA.user}</span></p>
				<p>{LYRIC_DATA.content}</p>
			</div>
			<!-- END: lyric -->
			<script type="text/javascript">
			$(document).ready(function() {
				$(".playlistitem").hide(); 
				$(".playlistitem:first").show(); 
				$("a.clicknext").click(function() {
					$(".playlistitem").hide(); 
					var activeTab = $(this).attr("href"); 
					$(activeTab).fadeIn(); 
					return false;
				});
			});
			</script>
		</div>
		<!-- BEGIN: accesslyric -->
		<p style="width:498px;float:left;"><a onclick="ShowHide('sendlyric'); return false;" class="show_lyric">{LANG.send} {LANG.lyric}</a></p>
		<div id="sendlyric" class="send_lyric">
			<form method="post">
				<input style="width:250px" type="text" id="user_lyric" value="{GDATA.username}" {CDATA.no_change}/>
				<input id="send-lyric-button" style="width: 50px;float:right;margin-right:20px;" onclick="sendlyric('{SDATA.song_id}');" class="submitbutton" type="button" value="{LANG.send}" />
				<textarea rows="10" cols="auto" id="body_lyric" ></textarea>
			</form>
			<script type="text/javascript">
			function sendlyric(id){
				var user_lyric = document.getElementById('user_lyric');
				var body_lyric = document.getElementById('body_lyric').value;
				if (user_lyric.value == "") {
					alert(nv_fullname);
					user_lyric.focus();
				} else if (body_lyric == "") {
					alert(nv_content);
					document.getElementById('body_lyric').focus();
				}else{
					$('#send-lyric-button').attr('disabled','disabled');
					$.ajax({
						type: 'POST',
						url: nv_siteroot + 'index.php',
						data: nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&sendlyric=1&id=' + id + '&user_lyric=' + user_lyric.value +  '&body_lyric=' + encodeURIComponent(body_lyric),
						success: function(data){
							if(data=='OK'||data=='WAIT'){
								$('#body_lyric').val('');
								if(data=='OK'){
									alert('{LANG.send_lyric_suc}');
								}else if(data=='WAIT'){
									alret('{LANG.send_lyric_wait}');
								}
								$('#send-lyric-button').attr('disabled','');
							}else alert(data);
						}
					});
				}
				return;
			}
			</script>
		</div>
		<!-- END: accesslyric -->
		<!-- BEGIN: noaccesslyric -->
		<p style="width:100%;float:left;text-align:center"><strong>{LANG.you_must} <a href="{GDATA.url_login}">{LANG.loginsubmit}</a> / <a href="{GDATA.url_register}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: noaccesslyric -->
		<!-- BEGIN: stoplyric -->
		<p style="width:100%;float:left;text-align:center"><em>{LANG.setting_stop}</em></p>
		<!-- END: stoplyric -->
	</div> 
	<div id="tab3" class="tab_content">
		<form action="#" method="post">
		<fieldset>
		<p><strong>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
			<p>&nbsp;
			<select id="root_error" name="root_error" style=";float:right;width: 346px;margin-right:34px;">
				<option value="">{LANG.error_choose}</option>
				<option value="check">{LANG.error_s_1}</option>
				<option>{LANG.error_s_2}</option>
				<option>{LANG.error_s_3}</option>
				<option>{LANG.error_s_4}</option>
				<option>{LANG.error_s_5}</option>
				<option>{LANG.error_s_6}</option>
				<option>{LANG.error_s_7}</option>
				<option>{LANG.error_s_8}</option>
				<option>{LANG.error_s_9}</option>
			</select></p>	
			<p>{LANG.enter_name}:
			<input id="user" type="text" value="{GDATA.username}" {CDATA.no_change}/> </p>
			<p style="width:460px">{LANG.give_error_diff}</p>
			<textarea id="bodyerror" name="message" rows="3" cols="3"></textarea>
			<p style="text-align:center">
			<input style="width: 50px" class="submitbutton" onclick="senderror('{SDATA.song_id}', 'song');" type="button" value="{LANG.send}" />
			</p>
		</fieldset>
		</form>
	</div> 
	<div id="tab4" class="tab_content">
	</div> 
	<div id="tab5" class="tab_content">
		<p style="text-align:center">
		<a href="{GDATA.download_url}{SDATA.song_id}">
			<img src="{GDATA.img_url}/Down.png" alt=""/></a><br />
		</p>
	</div> 
</div>

<script type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide(); 
	$("ul.tool li:first").removeClass("active").show();
	$(".tab_content:first").show(); 
	$("ul.tool li").click(function() {
		$("ul.tool li").addClass("active");
		$(this).removeClass("active"); 
		$(".tab_content").hide(); 
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).fadeIn(); 
		return false;
	});
});
</script>
<div style="height:10px;">&nbsp;</div>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
		<strong>{LANG.comment}</strong>
		</div>
	</div>
	<div id="main">
		<div id="size" class="commentcontainer"></div>
		<!-- BEGIN: comment -->
		<div class="commentcontainerbo">
			<div id="addCommentContainer">
				<form id="addCommentForm" method="post" action="#">
					<div>
						<label for="name">{LANG.your_name}:</label>
						<input type="text" name="name" id="name" value="{GDATA.username}" {CDATA.no_change} />
						<textarea name="body" id="commentbody" cols="20" rows="5"></textarea>
						<label for="body">{LANG.content}:</label>
						<div>
						<input style="width: 50px;float:right;margin-right:20px;" class="submitbutton" type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{SDATA.song_id}' , 'song' );" />
						<input id="showemotion" style="width: 130px;float:right;margin-right:20px;" class="submitbutton" type="button"  value="{LANG.emotion}" />
						<div class="clear"></div>
						<div style="position:relative;">
						<div id="emotion">{EMOTIONS}</div>
						<script type="text/javascript" src="{GDATA.data_url}showemotion.js"></script>
						</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- END: comment -->
		<!-- BEGIN: nocomment -->
		<p style="text-align:center"><strong>{LANG.you_must} <a href="{GDATA.url_login}">{LANG.loginsubmit}</a> / <a href="{GDATA.url_register}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nocomment -->
		<!-- BEGIN: stopcomment -->
		<p style="text-align:center"><em>{LANG.setting_stop}</em></p>
		<!-- END: stopcomment -->
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{SDATA.song_id}' , 'song', 0 );
	$("a#add").click(function() {
		$(this).removeClass("add"); 
		$(this).addClass("addedtolist1"); 
		var songid = $(this).attr("name");
		addplaylist(songid);
	});
});
</script>

<!-- END: main -->