<!-- BEGIN: main -->
<div id="giftcontainer">
	<!-- BEGIN: loop -->
	<div class="item">
		<a href="{url_listen}" title="{songname}" class="songname">{songname}</a>
		<p>
			<span>{time}</span> {message}
			<a title="{LANG.gift_wiew}" class="showmess fr" onclick="ShowHide('message{DIV}');">{LANG.gift_wiew}</a>
		</p>
		<div id="message{DIV}" style="display:none;color:#000;font-size:11px;text-align:justify;">
		{fullmessage}
		</div>
		<p>
			{LANG.request}: <b>{from}</b><br />
			{LANG.who_recive}: <b>{to}</b>
		</p>
	</div>
	<!-- END: loop -->
</div>
<!-- END: main -->