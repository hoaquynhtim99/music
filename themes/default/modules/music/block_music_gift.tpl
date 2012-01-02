<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div style="padding-bottom:5px;margin-bottom:5px;border-bottom:1px #dadada solid;font-size:11px">
	<a style="font-size:11px;color:#17658C" href="{url_listen}" title="{LANG.listen} {songname}"><strong>&gt; {songname}</strong></a> - <span style="font-size:11px;color:#999">{time}</span><br />
	{message}
	<a title="{LANG.readmore}" class="showmess fr" onclick="ShowHide('message{DIV}');">{LANG.readmore}</a>
	<div id="message{DIV}" style="display:none;color:#000;font-size:11px;text-align:justify;">{fullmessage}</div>
	<div class="clear"></div>
	<span style="font-size:11px;color:#666">{LANG.from}: <strong style="color:#17658C">{from}</strong></span><br />
	<span style="font-size:11px;color:#666">{LANG.to}: <strong style="color:#17658C">{to}</strong></span>
</div>
<!-- END: loop -->
<div class="clear"></div>
<div class="fr">
	<a href="{GIFT_LINK}" title="{LANG.readmore}">{LANG.readmore}</a>
</div>
<div class="clear"></div>
<!-- END: main -->