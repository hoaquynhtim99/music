<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div id="listen_head">
	<h2>{LANG.listen_album}</h2>
</div>
<div id="listen_main">
	<h2>{name} - {singer}</h2>
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
			skin: "{base_url}skewd.zip",
			autostart: "true",
			
			events: {
			onComplete: function(event) {
			jwplayer().playlistNext();
			}
			}
			
			});
		</script>
	</div>
		<ul class="tool">
		    <li class="give"><a href="#tab1">{LANG.send_to}</a></li>
		    <li class="error"><a href="#tab3">{LANG.give_error}</a></li>
		</ul>
	<div class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="sendtool">
				<a target="_blank" href="http://www.facebook.com/sharer.php?u={URL_ALBUM}&t={name}-{singer}" class="facebook">Facebook   </a>
			</div>
			<form action="#" method="post">
				<p>Link Album:
				<input type="text" value="{URL_ALBUM}" /> </p>
			</form>
		</div> 
		<div id="tab3" class="tab_content">
			<form action="#" method="post">
				<p><strong>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
				<p>{LANG.enter_name}:
				<input id="user" type="text" value="" /> </p>
				<p>{LANG.content}:</p>
				<textarea id="bodyerror" name="message" rows="5"></textarea>
				<p align="center">
				<a onclick="senderror();" class="submit">{LANG.send}</a></p>
			</form>
		</div> 
	</div>
	
	<script>
	$(document).ready(function() {
		//When page loads...
		$(".tab_content").hide(); //Hide all content
		$("ul.tool li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
		//On Click Event
		$("ul.tool li").click(function() {
			$("ul.tool li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
		});
	});
	</script>
	<div id="album_info">
		<h2>{LANG.album_info}</h2>
		<img border="0" src="{album_thumb}" width="100px" height="100px" />
		<p><span><strong>{name} - {singer}</strong><br />
		{LANG.who_create}: {who_post}<br />
		{LANG.view}: {numview}</span><br />
		{LANG.message}: {describe}</p>
	</div>	
</div>

<div id="main">
<p class="tttt">&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;{LANG.comment}</p>
<div id="size" class="commentcontainer"></div>
<div class="commentcontainerbo">
<div id="addCommentContainer">
<form id="addCommentForm" method="post" action="">
<div>
<label for="name">{LANG.your_name}</label>
<input type="text" name="name" id="name" value="".$name."" />
<label for="body">{LANG.content}</label>
<textarea name="body" id="commentbody" cols="20" rows="5"></textarea>
<p style="padding-right:50px;float:left;" align="center">
<input type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{ID}' , 'album' );" /></p>
</div>
</form>
</div>
</div></div>

<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{ID}' , 'album', 0 );
});
</script>


<!-- END: main -->