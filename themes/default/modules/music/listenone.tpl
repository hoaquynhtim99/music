<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div id="listen_head">
	<h2>{LANG.listen_song}</h2>
</div>
<div id="listen_main">
	<h2>{name}</h2>
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
		    <li class="lyric"><a href="#tab2">Lyric</a></li>
		    <li class="error"><a href="#tab3">{LANG.give_error}</a></li>
		    <li class="add"><a href="#tab4">{LANG.add}</a></li>
		    <li class="down"><a href="#tab5" >Download</a></li>
		</ul>
<div class="tab_container">
	<div id="tab1" class="tab_content">
		<div class="sendtool">
			<a target="_blank" href="http://www.facebook.com/sharer.php?u={URL_SONG}&t={name}-{singer}" class="facebook">Facebook   </a>
			<a class="sendtomail" href="javascript:void(0);" onclick="NewWindow('{URL_SENDMAIL}','{TITLE}','500','400','no');return false">{LANG.sendtomail}</a>
		</div>
		<form action="#" method="post">
			<p>{LANG.link_song}:
			<input type="text" value="{URL_SONG}" /> </p>
			<p><strong>{LANG.send_to}</strong></p>
			<p>{LANG.enter_name}:
			<input id="who_send" type="text" value="" /> </p>
			<p>{LANG.who_recive}:
			<input id="who_receive" type="text" value="" /> </p>
			<p>{LANG.message}:</p>
			<textarea id="body" name="message" rows="5"></textarea>
			<p align="center"><input onclick="sendgift('{ID}');" class="submit" type="button" value="{LANG.send}" /></p>
		</form>
	</div> 
	<div id="tab2" class="tab_content">
		<div id="playlistcotainer">
			<!-- BEGIN: nolyric -->
				<p align="center">{LANG.no_lyric}</p>
			<!-- END: nolyric -->
			<!-- BEGIN: lyric -->
			<div id="list{thisdiv}" class="playlistitem">
				<!-- BEGIN: next -->
				<a id="next" class="next" href="#list{nextdiv}"></a>
				<!-- END: next -->
				<!-- BEGIN: prev -->
				<a id="next" class="frev" href="#list{prevdiv}"></a>
				<!-- END: prev -->
				<p><strong><a>{name}</a> - <a>{singer}</a></strong></p>
				<p><span>{LANG.user_lyric}: {uesrlyric}</span></p>
				<p>{lyriccontent}</p>
			</div>
			<!-- END: lyric -->
			<script type="text/javascript">
			$(document).ready(function() {
				//When page loads...
				$(".playlistitem").hide(); //Hide all content
				$(".playlistitem:first").show(); //Show first tab content
				//On Click Event
				$("a#next").click(function() {
					$(".playlistitem").hide(); //Hide all tab content
					var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content
					$(activeTab).fadeIn(); //Fade in the active ID content
					return false;
				});
			});
			</script>
		</div>
		<a onclick="ShowHide('sendlyric'); return false;" class="show_lyric">{LANG.send} {LANG.lyric}</a>
		<div id="sendlyric" class="send_lyric">
			<form method="post">
				<input type="text" id="user_lyric" value="{LANG.your_name}" onfocus="if(this.value=='{LANG.your_name}')this.value=''" onblur="if(this.value=='')this.value='{LANG.your_name}'"/>
				<textarea rows="10" id="body_lyric" ></textarea>
				<input onclick="sendlyric('{ID}');" type="button" class="send" value="{LANG.send}" />
			</form>
		</div>
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
	<div id="tab4" class="tab_content">
		<a href="#tab4">
		<p align="center" onclick="addplaylist('{ID}');">
			{LANG.add_playlist}
		</p></a>
	</div> 
	<div id="tab5" class="tab_content">
		<a target="_blank" href="{link}">
		<p align="center">
			<img border="0" src="{img_url}/Down.png" /><br /> {LANG.down_info}
		</p></a>
	</div> 
</div>

<script type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide(); //Hide all content
	$("ul.tool li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
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
</div>

<div id="main">
<br /><h2>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;{LANG.comment}</h2>
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
<input type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{ID}' , 'song' );" /></p>
</div>
</form>
</div>
</div></div>

<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{ID}' , 'song', 0 );
});
</script>

<!-- END: main -->