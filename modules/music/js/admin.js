/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/09/2011 5:12 PM
 */

// Tao ten ngan gon
function get_alias( id, func ) {
	var title = strip_tags(document.getElementById(id).value);
	if (title != '') {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&tenthat=' + encodeURIComponent(title), '', func);
	}
	return false;
}
// tra ve gia tri ten ngan gon
function res_get_alias(res) {
	if (res != "") {
		document.getElementById('idalias').value = res;
	} else {
		document.getElementById('idalias').value = '';
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
	} 
	else 
	{
		document.getElementById('bitrate').value = r_split[1] ;
		document.getElementById('size').value = r_split[2] ;
		document.getElementById('duration').value = r_split[3] ;
	}
	return false;
}

//get ID3
function getsonginfo1(id) {
	var link = document.getElementById('duongdan' + id);
	var button = document.getElementById('get_info');
	button.disabled = true;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=getsonginfolist&id=' + id + '&link=' + encodeURIComponent(link.value), '', 'returnsonginfo1');
	return;
}
function returnsonginfo1(res) {
	var r_split = res.split("_");
	var button = document.getElementById('get_info');
	button.disabled = false;
	if (r_split[1] != 'mp3') 
	{
		document.getElementById('duongdan' + r_split[0]).value = "";
		alert(r_split[5]);
	} 
	else 
	{
		document.getElementById('bitrate' + r_split[0]).value = r_split[2] ;
		document.getElementById('size' + r_split[0]).value = r_split[3] ;
		document.getElementById('duration' + r_split[0]).value = r_split[4] ;
	}
	return false;
}

//  ---------------------------------------
function nv_chang_hotalbum_weight( id )
{
   var nv_timer = nv_settimeout_disable( 'weight' + id, 5000 );
   var newpos = document.getElementById( 'weight' + id ).options[document.getElementById( 'weight' + id ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=hotalbum&changeweight=1&id=' + id + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_hotalbum_weight_result' );
   return;
}

//  ---------------------------------------

function nv_chang_hotalbum_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

// Chinh thu tu chu de am nhac
function nv_change_cat_weight( id )
{
   var nv_timer = nv_settimeout_disable( 'weight' + id, 5000 );
   var newpos = document.getElementById( 'weight' + id ).options[document.getElementById( 'weight' + id ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=category&changeweight=1&id=' + id + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_change_cat_weight_result' );
   return;
}
function nv_change_cat_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

// Chinh thu tu chu de video clip
function nv_change_vcat_weight( id )
{
   var nv_timer = nv_settimeout_disable( 'weight' + id, 5000 );
   var newpos = document.getElementById( 'weight' + id ).options[document.getElementById( 'weight' + id ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=video_category&changeweight=1&id=' + id + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_change_vcat_weight_result' );
   return;
}
function nv_change_vcat_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

// Cap nhat lai album HOT
function nv_update_hot_album(stt,id)
{
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=hotalbum&update=1&id=' + id + '&stt=' + stt + '&num=' + nv_randomPassword( 8 ), '', 'nv_update_hot_album_result' );
   return;
}
function nv_update_hot_album_result( res )
{
	if ( res != 'OK' )
	{
		alert( nv_is_change_act_confirm[2] );
	}else{
	   window.location.href = window.location.href;
	}
   return;
}

// Xoa chu de bai hat
function nv_delete_category( id, where ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'get', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&where=' + where + '&id=' + id, '', 'nv_delete_category_result' );
	}
	return false;
}
function nv_delete_category_result(res){
	alert( res );
	window.location.href = window.location.href;
	return false;
}
