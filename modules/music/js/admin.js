/**
 * @Project NUKEVIET 3.0
 * @Author PHAN TAN DUNG (phantandung92@vinades.vn)
 * @Createdate 1 - 31 - 2010 5 : 12
 */
// Tao ten ngan gon
function get_alias( id, func ) {
	var title = strip_tags(document.getElementById(id).value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&tenthat=' + encodeURIComponent(title), '', func);
	}
	return false;
}
// tra ve gia tri ten ngan gon cua bai hat
function res_get_alias(res) {
	if (res != "") {
		document.getElementById('idalias').value = res;
	} else {
		document.getElementById('idalias').value = '';
	}
	return false;
}
// tra ve gia tri cua ten ngan gon ca si
function res_get_gingername(res) {
	if (res != "") {
		document.getElementById('singer_sortname').value = res;
	} else {
		document.getElementById('singer_sortname').value = '';
	}
	return false;
}

//get ID3
function getsonginfo() {
	var link = document.getElementById('duongdan');
	var button = document.getElementById('get_info');
	button.disabled = true;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getsonginfo&link=' + encodeURIComponent(link.value), '', 'returnsonginfo');
	return;
}
//check song
function checksong(id) {
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=checklink&id=' + id, '', 'alertres');
	return;
}
// alertres
function alertres(res) {
	alert(res);
	return false;
}
// tra ve gia tri
function returnsonginfo(res) {
	var r_split = res.split("_");
	var button = document.getElementById('get_info');
	button.disabled = false;
	if (r_split[0] != 'mp3') 
	{
		document.getElementById('duongdan').value = "";
		alert(r_split[4]);
		alert(res);
	} 
	else 
	{
		document.getElementById('bitrate').value = r_split[1] ;
		document.getElementById('size').value = r_split[2] ;
		document.getElementById('duration').value = r_split[3] ;
	}
	return false;
}
