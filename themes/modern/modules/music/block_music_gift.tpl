<!-- BEGIN: main -->
<div id="giftcontainer">
	<!-- BEGIN: loop -->
	<div class="item">
		<a href="{url_listen}" title="{songname}" class="songname">{songname}</a>
		<p>
			<span>{time}</span> {message}
			<a title="{LANG.readmore}" class="showmess fr" onclick="ShowHide('message{DIV}');">{LANG.readmore}</a>
		</p>
		<div id="message{DIV}" style="display:none;color:#000;font-size:11px;text-align:justify;">
		{fullmessage}
		</div>
		<p>
			{LANG.from}: <b>{from}</b><br />
			{LANG.to}: <b>{to}</b>
		</p>
	</div>
	<!-- END: loop -->
	<div class="clear"></div>
	<div class="fr">
		<a href="{GIFT_LINK}" title="{LANG.readmore}">{LANG.readmore}</a>
	</div>
	<div class="clear"></div>
</div>
<!-- END: main -->