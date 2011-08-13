<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.gift_list}</strong>
		</div>
	</div>
	<!-- BEGIN: loop -->
	<div class="gift-item">
		<a href="{ROW.url_listen}" title="{ROW.songtitle}">{ROW.songtitle}</a>
		<div style="padding:5px">
			<ul>
				<li class="recieve">{LANG.who_recive}: <strong>{ROW.who_send}</strong></li>
				<li class="from">{LANG.request}: <strong>{ROW.who_receive}</strong></li>
				<li class="message">{LANG.message}:</li>
			</ul>
			<div>{ROW.body}</div>
		</div>
	</div>
	<div class="hr"></div>
	<!-- END: loop -->
	<div class="clear"></div>
	<!-- BEGIN: generate_page -->
	<div class="generate_page">
		{generate_page}
	</div>
	<div class="clear"></div>
	<!-- END: generate_page -->
</div>
<div class="clear"></div>
<!-- END: main -->