<!-- BEGIN: main -->
<!--<meta name="verify-v1" content="nPO+sJiorgxine7U88iTPkF/yVTxi3t9QOi9Wi8z60w=" />-->
<link rel="image_src" href="{base_url}ember.jpg" />

<meta name="video_height" content="60" /> 
<meta name="video_width" content="400" /> 
<meta name="video_type" content="application/x-shockwave-flash" />

<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_album}</strong>
		</div>
	</div>
<div id="listen_main">
	<strong>{name} - {singer}</strong>
	<p>{LANG.who_post}:<a href="{url_search_upload}"> {who_post}&nbsp;</a> | {LANG.view}: {numview}</p>
	<div class="playercontainer">
		<div id="player">Loading the player a1binhdinh.com ...</div>
		
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{song_url}", image: "{ads}", title: "{song_name} - ", description: " {song_singer}" },
			<!-- END: song -->
			],
			"playlist.position": "bottom",
			"playlist.size": 1,
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
	<li class="active"><a class="error" href="#tab3">{LANG.give_error}</a></li>
</ul>
	<div style="width:498px;" id="boderfull" class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="sendtool">
				<a target="_blank" href="http://www.facebook.com/sharer.php?u={URL_ALBUM}&t={name}-{singer}" class="facebook">Facebook   </a>
			</div>
			<form action="#" method="post">
				<p>Link Album:
				<input id="songforum" onClick="SelectAll('songforum');" type="text" value="{URL_ALBUM}" readonly="readonly" /> </p>
			</form>
			<script type="text/javascript">
				function SelectAll(id)
				{
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
		</div> 
		<div id="tab3" class="tab_content">
			<form action="#" method="post">
				<p><strong>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
				<p>{LANG.enter_name}:
				<input id="user" type="text" value="" /> </p>
				<p>{LANG.content}:</p>
				<textarea id="bodyerror" name="message" rows="5"></textarea>
				<p align="center">
				<a onclick="senderror('{ID}', 'album');" class="submitbutton">{LANG.send}</a></p>
			</form>
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
<div class="box-border-shadow m-bottom">
<div class="cat-box-header"> 
<div class="cat-nav"> 
<strong>{LANG.album_info}</strong>
</div>
</div>
<div id="album_info">
	<img border="0" src="{album_thumb}" width="90px" height="90px" />
	<p><span><strong>{name} - {singer}</strong><br />
	{LANG.who_create}: {who_post}<br />
	{LANG.view}: {numview}</span><br />
	{LANG.message}: {describe}</p>
</div>	
<div class="clear"> </div>
</div>

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
						<input style="width: 50px;float:right;margin-right:20px;" class="submitbutton" type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{ID}' , 'album' );" />
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
	show_comment( '{ID}' , 'album', 0 );
});
</script>
<!-- END: main -->