<!-- BEGIN: empty -->
<div class="alboxw"><div class="alwrap alcontent information"><div>{LANG.gift_list_empty}<a title="{LANG.close_info}" href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="fr musicicon mcancel">&nbsp;</a></div></div></div>
<!-- END: empty -->
<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span>{LANG.gift_list}</span>
		</div>
	</div>
</div>
<!-- BEGIN: loop -->
<div class="alboxw">
	<div class="alwrap alcontent">
		<h2 class="gift"><a class="greencolor musicicon mplay" href="{ROW.url_listen}" title="{ROW.songtitle}">{ROW.songtitle}</a></h2>
		<span class="musicicon mplay msmall">{LANG.who_recive}: <strong class="greencolor">{ROW.who_send}</strong></span><br />
		<span class="musicicon mplay msmall">{LANG.request}: <strong class="greencolor">{ROW.who_receive}</strong></span><br />
		<div>{ROW.body}</div>
	</div>
</div>
<!-- END: loop -->
<div class="clear"></div>
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{generate_page}
</div>
<div class="clear"></div>
<!-- END: generate_page -->
<div class="clear"></div>
<!-- END: main -->