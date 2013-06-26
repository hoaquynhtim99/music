/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/09/2011 5:12 PM
 */

// Bao ket qua
function resultgift(res){
	alert(res);
	return false;
}

// Gui bao loi cho quan tri
function senderror(id, where){
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
		nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&senderror=1&id=' + id + '&where=' + where + '&user=' + user.value + '&root_error=' + root_error + '&body=' + encodeURIComponent(body), '', 'resultgift');
	}
	return;
}

// Them paylist
function addplaylist(id) 
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&addplaylist=1&id=' + id, '', 'resultplaylist');
	return;
}
function resultplaylist(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=showplaylist', 'playlist', '');
	} else alert(res);
}

// Xoa paylist (dang luu trong phien lam viec hien tai)
function delplaylist()
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&delplaylist=1' , '', 'resultgift');
	return;
}

// Gui binh luan
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
		var sd = document.getElementById('button-comment');
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
	nv_set_disable_false('button-comment');
	return false;
}

// Hien thi cac binh luan
function show_comment(id, where, page) {
	nv_ajax('get', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&id=' + id + '&where=' + where + '&page=' + page, 'comment-content', '');
}

// Luu album
function saveplaylist(name, singer, message){
	document.getElementById('submitpl').disabled = true;
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&savealbum=1&name=' + name + '&singer=' + singer + '&message=' + encodeURIComponent(message), '', 'aftersavelist');
}
function aftersavelist(res){
	var r_split = res.split("_");
	if (r_split[0] == 1){
		alert(r_split[1]);
		window.location = r_split[2];
	}else{
		document.getElementById('submitpl').disabled = false;
		alert(res);
	}
}

// Xoa mot bai hat tu playlist chua luu
function delsongfrlist(stt, mess) {
	if ( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&delsongfrlist=1&stt=' + stt, '', 'afterdelsong');
}

// Xoa mot bai hat tu playlist da luu
function delsongfrplaylist(id, plid, mess) {
	if ( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&delsongfrplaylist=1&id=' + id + '&plid=' + plid, '', 'afterdelsong');
}

// Xoa mot bai hat cua thanh vien
function delsong(id, mess) {
	if ( confirm ( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&delsong=1&id=' + id , '', 'afterdelsong');
}
function afterdelsong(res)
{
	var r_split = res.split("_");

	if( r_split[0] == "OK" )
	{
		element = document.getElementById("song" + r_split[1]);
		element.parentNode.removeChild(element);
	}
	else
	{
		alert( res );
	}
}

// Xoa playlist da duoc luu vao CSDL
function dellist(id, mess) {
	if( confirm( mess ) )
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&dellist=1&id=' + id, '', 'afterdellist');
}
function afterdellist(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		element = document.getElementById("item" + r_split[1]);
		element.parentNode.removeChild(element);
	} else alert(res);
}

// Binh chon bai hat
function votethissong( id ) {
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&votesong=1&id=' + id, '', 'aftervote');
}
function aftervote(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		document.getElementById("vote").innerHTML = "(" + r_split[1] + ")";
	} 
	alert(r_split[2]);
}


// an hien div
function ShowHide(what)
{
	$("#"+what+"").animate({"height": "toggle"}, { duration: 1 });
}

// Kiem tra thoi gian thuc hien
function nvms_check_timeout(ckname,timeout,lang){
	var timeout_old = nv_getCookie(ckname);
	var timeout_new = new Date();
	timeout_new = timeout_new.getTime();
	if((timeout_old != null)&&((timeout_new - timeout_old)<timeout)){
		alert(lang);
		return false;
	}
	//nv_setCookie(ckname, timeout_new);
	return true;
}