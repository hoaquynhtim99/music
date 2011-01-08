/**
 * @Project NUKEVIET 3.0
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Createdate 26 - 12 - 2010 5 : 12
 */

// gui qua tang
function sendgift(id) {
	var who_send = document.getElementById('who_send');
	var who_receive = document.getElementById('who_receive');
	var body  = strip_tags(document.getElementById('body').value);
	if (who_send.value == "") {
		alert(nv_fullname);
		who_send.focus();
	} else if (body == "") {
		alert(nv_content);
		document.getElementById('body').focus();
	} else {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendgift&id=' + id + '&who_send=' + who_send.value + '&who_receive=' + who_receive.value + '&body=' + encodeURIComponent(body), '', 'resultgift');
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
function senderror() {
	var user = document.getElementById('user');
	var body  = strip_tags(document.getElementById('bodyerror').value);
	if (user.value == "") {
		alert(nv_fullname);
		user.focus();
	} else if (body == "") {
		alert(nv_content);
		document.getElementById('bodyerror').focus();
	} else {
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=senderror&user=' + user.value + '&body=' + encodeURIComponent(body), '', 'resultgift');
	}
	return;
}

// them paylist
function addplaylist(id) 
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addplaylist&id=' + id, '', 'resultgift');
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
// hien thi cac binh luan
function show_comment(id, where, page) {
	nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&id=' + id + '&where=' + where + '&page=' + page, 'size', '');
}


// an hien div
function ShowHide(what)
{
	$("#"+what+"").animate({"height": "toggle"}, { duration: 90 });
}
