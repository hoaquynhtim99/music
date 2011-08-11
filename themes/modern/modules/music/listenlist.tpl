<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jquery.playlist.js"></script>
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.listen_album}</strong>
		</div>
	</div>
<div id="listen_main">
	<strong>{ALBUM.name} - {ALBUM.singer}</strong>
	<p>{LANG.who_post}:<a title="{ALBUM.who_post}" href="{ALBUM.url_search_upload}"> {ALBUM.who_post}&nbsp;</a> | {LANG.view}: {ALBUM.numview}</p>
	<div class="playercontainer">
		<div style="float:left;background:black;cursor:pointer;width:470px;height:236px;">
		<!--[if !IE]> -->
		<object onclick="window.open('{ads.url}'); return false;" type="application/x-shockwave-flash" data="{ads.link}" width="470" height="236">
		<!-- <![endif]-->
		<!--[if IE]>
		<object onclick="window.open('{ads.url}'); return false;" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="479" height="236"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">
			<param name="movie" value="{ads.link}" />
		<!--><!--dgx-->
			<param name="loop" value="true" />
			<param name="wmode" value="transparent" />
			<param name="menu" value="false" />
		</object>
		<!-- <![endif]-->
		</div>
		<div id="player">Loading the player ...</div>	
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			playlist: [
			<!-- BEGIN: song -->
			{ file: "{SONG.song_url}", title: "{SONG.stt}. {SONG.song_name} - ", description: " {SONG.song_singer}", },
			<!-- END: song -->
			],
			controlbar: "bottom",
			volume: 100,
			height: 24,
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
	<li class="active"><a title="" class="give" href="#tab1">{LANG.send_to}</a></li>
	<li class="active"><a title=""class="error" href="#tab3">{LANG.give_error}</a></li>
</ul>
	<div style="width:498px;" id="boderfull" class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="sendtool">
				<a onclick="this.target='_blank';" href="http://www.facebook.com/sharer.php?u={ALBUM.URL_ALBUM}&amp;t={ALBUM.name}-{ALBUM.singer}" class="facebook">Facebook   </a>
			</div>
			<form action="#" method="post">
				<p>Link Album:
				<input id="albumlink" onclick="select_all('albumlink')" type="text" value="{ALBUM.URL_ALBUM}" readonly="readonly" /> </p>
				<p>{LANG.blog_song}:
				<input id="blogsong" onclick="Select_all('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;60&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{playerurl}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;playlistfile={ALBUM.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{playerurl}player.swf&quot; width=&quot;500&quot; height=&quot;60&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;playlistfile={ALBUM.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=60&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly" /> </p>
				<p>{LANG.forum_song}:
				<input id="songforum" onclick="Select_all('songforum');" type="text" value="[FLASH]{playerurl}player.swf?playlistfile={ALBUM.creat_link_url}[/FLASH]" readonly="readonly" /> </p>
			</form>
			<script type="text/javascript">
				function Select_all(id)
				{
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
		</div> 
		<div id="tab3" class="tab_content">
			<form action="#" method="post">
			<fieldset>
				<p><strong>{LANG.give_error}</strong>: {LANG.give_error_info}</p>
				<p>&nbsp;
				<select id="root_error" name="root_error" style=";float:right;width: 346px;margin-right:34px;">
					<option value="">{LANG.error_choose}</option>
					<option>{LANG.error_a_1}</option>
					<option>{LANG.error_a_2}</option>
					<option>{LANG.error_a_3}</option>
					<option>{LANG.error_a_4}</option>
					<option>{LANG.error_a_5}</option>
					<option>{LANG.error_a_6}</option>
					<option>{LANG.error_a_7}</option>
					<option>{LANG.error_a_8}</option>
				</select></p>	
				<p>{LANG.enter_name}:
				<input id="user" type="text" value="{USER_NAME}" {NO_CHANGE}/> </p>
				<p style="width:460px">{LANG.give_error_diff}></p>
				<textarea id="bodyerror" name="message" rows="1" cols="auto" ></textarea>
				<p style="text-align:center">
				<a onclick="senderror('{ID}', 'album');" class="submitbutton">{LANG.send}</a></p>
			</fieldset>
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
	<img src="{ALBUM.album_thumb}" width="90" height="90" alt="{ALBUM.name}" />
	<p><span><strong>{ALBUM.name} - {ALBUM.singer}</strong><br />
	{LANG.who_create}: {ALBUM.who_post}<br />
	{LANG.view}: {ALBUM.numview}</span><br />
	{LANG.message}: {ALBUM.describe}</p>
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
						<div>
						<input style="width: 50px;float:right;margin-right:20px;" class="submitbutton" type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{ID}' , 'album' );" />
						<input id="showemotion" style="width: 130px;float:right;margin-right:20px;" class="submitbutton" type="button"  value="{LANG.emotion}" />
						<div class="clear"></div>
						<div style="position:relative;">
						<div id="emotion">{EMOTIONS}</div>
						<script type="text/javascript" src="{base_url}showemotion.js"></script>
						</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- END: comment -->
		<!-- BEGIN: nocomment -->
		<p style="text-align:center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nocomment -->
		<!-- BEGIN: stopcomment -->
		<p style="text-align:center"><em>{LANG.setting_stop}</em></p>
		<!-- END: stopcomment -->
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{ID}' , 'album', 0 );
});
</script>
<!-- END: main -->