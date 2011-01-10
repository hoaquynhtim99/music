<!-- BEGIN: main -->
<div id="giftcontainer">
<!-- BEGIN: loop -->
<div class="item">
<a href="{url_listen}" class="songname">{songname}</a>
<p><span>{time}</span> {message}
</p>

<div id="message{DIV}" style="display:none;color:#000;font-size:11px;text-align:justify;">
{fullmessage}
</div>
<p>{LANG.request}: <b>{from}</b><br />
{LANG.who_recive}: <b>{to}</b></p>
</div>
<a class="showmess" onclick="ShowHide('message{DIV}')" style="float:right;padding-right:10px;"><span style="color: #f70e03;">Xem chi tiết</span></a>
<div class="clear" style="border-bottom-style: dotted;border-bottom-width: 1px;border-bottom-color: #999999;"></div>
<!-- END: loop -->
</div>
<!-- END: main -->