<!-- BEGIN: main -->
<!--<meta name="verify-v1" content="nPO+sJiorgxine7U88iTPkF/yVTxi3t9QOi9Wi8z60w=" />-->
<link rel="image_src" href="{base_url}ember.jpg" />
<link rel="video_src" href="{playerurl}player.swf?playlistfile={creat_link_url}" />

<meta name="video_height" content="60" /> 
<meta name="video_width" content="400" /> 
<meta name="video_type" content="application/x-shockwave-flash" />

<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_song}</strong>
		</div>
	</div>
<div id="listen_main">
	<strong>{name}</strong>
	<p>{LANG.show}: <a href="{url_search_singer}">{singer}</a></p>
	<p>{LANG.category_2}:<a href="{url_search_category}"> {category}&nbsp;</a> | {LANG.view}: {numview}</p>
	<p>Album: <a href="{url_search_album}">{album}
	</a></p>
	<div class="playercontainer">
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			file: "{link}", image: "{ads}" ,
			controlbar: "bottom",
			"logo.file": "{base_url}logo.png",
			"logo.position": "top-right",
			"logo.hide": "false",
			"logo.link": "{NV_MY_DOMAIN}",
			"logo.linktarget": "_top",
			volume: 100,
			height: 260,
			width: 470,
			autostart: "true",
			events: {
			onComplete: function(event) {
			jwplayer().playlistNext();
			}
			}
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
	<li class="active"><a name="{ID}" id="add" class="add" href="#tab4">{LANG.add}</a></li>
	<li class="active"><a class="down" href="#tab5" >Download</a></li>
</ul>
<div style="width:498px;" id="boderfull" class="tab_container">
	<div id="tab1" class="tab_content">
		<div class="sendtool">
			<a target="_blank" href="http://www.facebook.com/sharer.php?u={URL_SONG}&t={name}-{singer}" class="facebook">Facebook   </a>
			<a class="sendtomail" href="javascript:void(0);" onclick="NewWindow('{URL_SENDMAIL}','{TITLE}','500','400','no');return false">{LANG.sendtomail}</a>
		</div>
		<form action="#" method="post">
			<p>{LANG.link_song}:
			<input id="linksong" onClick="SelectAll('linksong');" type="text" value="{URL_SONG}" readonly="readonly" /> </p>
			<p>{LANG.blog_song}:
			<input id="blogsong" onClick="SelectAll('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;60&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{playerurl}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;playlistfile={creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{playerurl}player.swf&quot; width=&quot;500&quot; height=&quot;60&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;playlistfile={creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly" /> </p>
			<p>{LANG.forum_song}:
			<input id="songforum" onClick="SelectAll('songforum');" type="text" value="[FLASH]{playerurl}player.swf?playlistfile={creat_link_url}[/FLASH]" readonly="readonly" /> </p>
			<script type="text/javascript">
				function SelectAll(id)
				{
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
			<p><strong>{LANG.send_to}</strong></p>
		<!-- BEGIN: gift -->
			<p>{LANG.enter_name}:
			<input id="who_send" type="text" value="{USER_NAME}" {NO_CHANGE} /> </p>
			<p>{LANG.who_recive}:
			<input id="who_receive" type="text" value="" /> </p>
			<p>{LANG.message}:</p>
			<textarea id="body" name="message" rows="5"></textarea>
			<p align="center">
			<input style="width: 50px" onclick="sendgift('{ID}');" class="submitbutton" type="button" value="{LANG.send}" />
			</p>
		<!-- END: gift -->
		</form>
		<!-- BEGIN: nogift -->
		<p style="width:100%;float:left;" align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nogift -->
	</div> 
	<div id="tab2" class="tab_content">
		<div id="playlistcotainer">
			<!-- BEGIN: nolyric -->
				<p align="center">{LANG.no_lyric}</p>
			<!-- END: nolyric -->
			<!-- BEGIN: lyric -->
			<div id="list{thisdiv}" class="playlistitem">
				<p style="float:left;width:490px;margin-bottom:-20px;">
				<!-- BEGIN: next -->
				<a id="next" class="next" href="#list{nextdiv}"></a>
				<!-- END: next -->
				<!-- BEGIN: prev -->
				<a id="next" class="frev" href="#list{prevdiv}"></a>
				</p>
				<!-- END: prev -->
				<p><strong><a>{name}</a> - <a>{singer}</a></strong></p>
				<p><span>{LANG.user_lyric}: {uesrlyric}</span></p>
				<p>{lyriccontent}</p>
			</div>
			<!-- END: lyric -->
			<script type="text/javascript">
			$(document).ready(function() {
				$(".playlistitem").hide(); 
				$(".playlistitem:first").show(); 
				$("a#next").click(function() {
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
			<p>{LANG.add_playlist_info}</p>
			<form method="post">
				<input style="width: 250px" type="text" id="user_lyric" value="{USER_NAME}" onfocus="if(this.value=='{USER_NAME}')this.value=''" onblur="if(this.value=='')this.value='{USER_NAME}'" {NO_CHANGE}/>
				<textarea rows="10" id="body_lyric" ></textarea>
				<input style="width: 50px;float:right;margin-right:20px;" onclick="sendlyric('{ID}');" class="submitbutton" type="button" value="{LANG.send}" />
			</form>
		</div>
		<!-- END: accesslyric -->
		<!-- BEGIN: noaccesslyric -->
		<p style="width:100%;float:left;" align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: noaccesslyric -->
	</div> 
	<div id="tab3" class="tab_content">
		<form action="#" method="post">
			<p><strong>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
			<p>{LANG.enter_name}:
			<input id="user" type="text" value="" /> </p>
			<p>{LANG.content}:</p>
			<textarea id="bodyerror" name="message" rows="5"></textarea>
			<p align="center">
			<input style="width: 50px" class="submitbutton" onclick="senderror('{ID}', 'song');" type="button" value="{LANG.send}" />
			</p>
		</form>
	</div> 
	<div id="tab4" class="tab_content">
	</div> 
	<div id="tab5" class="tab_content">
		<p align="center">
		<a href="{URL_DOWN}{ID}" target="_blank">
			<img border="0" src="{img_url}/Down.png" /></a><br />
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
				<form id="addCommentForm" method="post" action="">
					<div>
						<label for="name">{LANG.your_name}:</label>
						<input type="text" name="name" id="name" value="{USER_NAME}" {NO_CHANGE} />
						<textarea name="body" id="commentbody" cols="20" rows="5"></textarea>
						<label for="body">{LANG.content}:</label>
						<input style="width: 50px;float:right;margin-right:20px;" class="submitbutton" type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{ID}' , 'song' );" />
					</div>
				</form>
			</div>
		</div>
		<!-- END: comment -->
		<!-- BEGIN: nocomment -->
		<p align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nocomment -->
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{ID}' , 'song', 0 );
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$("a#add").click(function() {
		$(this).removeClass("add"); 
		$(this).addClass("addedtolist1"); 
		var songid = $(this).attr("name");
		addplaylist(songid);
	});
});
</script>

<!-- END: main -->