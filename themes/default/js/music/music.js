/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 12/11/2011 5:12 PM
 */

// Packpage in jquery 1.3 + only
function Select_all(id){ document.getElementById(id).focus(); document.getElementById(id).select(); }

function play_song(player, o){
	jwplayer(player).load(o.name);
	
	var this_song = o.id;
	this_song = this_song.split('-');
	nv_current_song = this_song[1];
	nv_control_playlist(nv_current_song);
}

function nv_start_player(player){
	var url = $('#song-' + nv_current_song).attr('name');
	jwplayer(player).load(url);
	nv_control_playlist(nv_current_song);
}

function nv_complete_song(player){
	if( nv_current_song < nv_num_song ){
		nv_current_song ++;
	}else{
		nv_current_song = 1;
	}
	var url = $('#song-' + nv_current_song).attr('name');
	jwplayer(player).load(url);
	nv_control_playlist(nv_current_song);
}

function nv_control_playlist(this_song){
	$('#playlist-container').find('div').removeClass('iteming');
	$('#song-wrap-'+this_song).addClass('iteming');
}

function nv_show_emotions(target){
	if($("#"+target).css("display")=="none"){
		$("#"+target).css("display","block");
		if($("#"+target).html()==""){
			nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&loademotion=1', target, '');
		}
	}else{
		$("#"+target).css("display","none");
	}
}

$(document).ready(function(){
	$("ul.mtool a.madd").click(function(){
		$(this).removeClass("madd").addClass("madded"); 
		addplaylist($(this).attr("name"));
	});
});