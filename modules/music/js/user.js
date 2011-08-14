/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Createdate 26 - 12 - 2010 5 : 12
 */

// gui qua tang
function sendgift(id) {
	var who_send = document.getElementById('who_send');
	var email_receive = document.getElementById('email_receive');
	var who_receive = document.getElementById('who_receive');
	var body  = strip_tags(document.getElementById('body').value);
	if (who_send.value == "") {
		alert(nv_fullname);
		who_send.focus();
	} else if (body == "") {
		alert(nv_content);
		document.getElementById('body').focus();
	} else {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendgift&id=' + id + '&who_send=' + who_send.value + '&who_receive=' + who_receive.value + '&email_receive=' + email_receive.value + '&body=' + encodeURIComponent(body), '', 'resultgift');
	}
	return;
}

// gui loi bai hat
function sendlyric(id) {
	var user_lyric = document.getElementById('user_lyric');
	var body_lyric  = strip_tags(document.getElementById('body_lyric').value, '<br />');
	if (user_lyric.value == "") {
		alert(nv_fullname);
		user_lyric.focus();
	} else if (body_lyric == "") {
		alert(nv_content);
		document.getElementById('body_lyric').focus();
	} else {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendlyric&id=' + id + '&user_lyric=' + user_lyric.value +  '&body_lyric=' + encodeURIComponent(body_lyric), '', 'resultgift');
	}
	return;
}

// bao ket qua
function resultgift(res) {
	alert(res);
	return false;
}

// gui bao loi cho quan tri
function senderror(id, where) {
	var root_error = document.getElementById('root_error').value;
	var user = document.getElementById('user');
	var body  = strip_tags(document.getElementById('bodyerror').value);
	if (user.value == "") {
		alert(nv_fullname);
		user.focus();
	} else if ( (body == "") && ( root_error == "" ) ) {
		alert(nv_content);
		document.getElementById('bodyerror').focus();
	} else {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=senderror&id=' + id + '&where=' + where + '&user=' + user.value + '&root_error=' + root_error + '&body=' + encodeURIComponent(body), '', 'resultgift');
	}
	return;
}

// them paylist
function addplaylist(id) 
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addplaylist&id=' + id, '', 'resultplaylist');
	return;
}

// xoa paylist
function delplaylist() 
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delplaylist' , '', 'resultgift');
	return;
}

// gui binh luan
function sendcommment(id, where) {
	var name = document.getElementById('name');
	var body = strip_tags(document.getElementById('commentbody').value);
	if (name.value == "") {
		alert(nv_fullname);
		name.focus();
	} else if (body == "") {
		alert(nv_content);
		document.getElementById('commentbody').focus();
	} else {
		var sd = document.getElementById('buttoncontent');
		sd.disabled = true;
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=postcomment&id=' + id + '&where=' + where + '&name=' + name.value + '&body=' + encodeURIComponent(body), '', 'comment_result');
	}
	return;
}
// tra ve sau khi binh luan
function comment_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		document.getElementById('commentbody').value = "";
		show_comment(r_split[1], r_split[2], 0);
		alert(r_split[3]);
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_content_failed);
	}
	nv_set_disable_false('buttoncontent');
	return false;
}
// tra ve sau khi them playlist
function resultplaylist(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		//$("a#add").addClass("added");
		nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=showplaylist', 'playlist', '');
	} else alert(res);
}
// hien thi cac binh luan
function show_comment(id, where, page) {
	nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&id=' + id + '&where=' + where + '&page=' + page, 'size', '');
}
// luu album
function saveplaylist(name, singer, message) {
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=savealbum&name=' + name + '&singer=' + singer + '&message=' + encodeURIComponent(message), '', 'aftersavelist');
}
function aftersavelist(res){
	var r_split = res.split("_");
	if (r_split[0] == 1) {
		window.location = r_split[1];
	} else alert(res);

}
// xoa mot bai hat tu playlist
function delsongfrlist(stt, mess) {
	if ( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delsongfrlist&stt=' + stt, '', 'afterdelsong');
}
// xoa mot bai hat tu playlist da luu
function delsongfrplaylist(id, plid, mess) {
	if ( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delsongfrplaylist&id=' + id + '&plid=' + plid, '', 'afterdelsong');
}
// xoa mot bai
function delsong(id, mess) {
	if ( confirm ( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=delsong&id=' + id , '', 'afterdelsong');
}
function afterdelsong(res)
{
	var r_split = res.split("_");

	if( r_split[0] == "OK" )
	{
		$("#song" + r_split[1]).remove();
	}
	else
	{
		alert( res );
	}
}

// xoa playlist
function dellist(id, mess) {
	if( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=dellist&id=' + id, '', 'afterdellist');
}
// binh chon bai hat
function votethissong( id ) {
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=vote&id=' + id, '', 'aftervote');
}
function aftervote(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		document.getElementById("vote").innerHTML = "(" + r_split[1] + ")";
	} 
	alert(r_split[2]);
}
function afterdellist(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		$("#item" + r_split[1]).remove();
	} else alert(res);
}
// an hien div
function ShowHide(what)
{
	$("#"+what+"").animate({"height": "toggle"}, { duration: 90 });
}
