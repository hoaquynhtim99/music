/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 12/11/2011 5:12 PM
 */

// Packpage in jquery 1.3 + only

// Replace broken images
$(window).load(function() { 
	$("img").each(function(){
		var image = $(this);
		if(image.context.naturalWidth == 0 || image.readyState == 'uninitialized'){
			$(image).unbind("error").attr("src", nv_siteroot + "images/logo.png");
		}
	});
});

var NVMS = {};

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

// Tim kiem
NVMS.advsearch = {};
NVMS.advsearch.show = false;
NVMS.advsearch.target = null;
NVMS.advsearch.prosess = function(e){
	NVMS.advsearch.target = $('#ms-advwrap');
	if( e == 'close' ){
		NVMS.advsearch.target.slideUp(500);
		NVMS.advsearch.show = false;
		return;
	}
	if( NVMS.advsearch.show == false ){
		NVMS.advsearch.target.slideDown(500);
		NVMS.advsearch.show = true;
	}else{
		NVMS.advsearch.target.slideUp(500);
		NVMS.advsearch.show = false;
	}
}

// Tim kiem nhanh
NVMS.search = {};
NVMS.search.minchar = 2;
NVMS.search.showres = false;
NVMS.search.timeout = 500;
NVMS.search.timer = null;
NVMS.search.allowclose = true;

NVMS.search.load = function(){
	$('#msressearch').load( nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&quicksearch&checksess=' + NVMS.search.txtsearch.attr('accesskey') + '&q=' + encodeURIComponent( NVMS.search.q ), function(){
		NVMS.search.imgloader.hide();
		
		if( NVMS.search.showres == false ){
			$('#msressearch').show();
			NVMS.search.showres = true;
		}
	} );
};

$(document).ready(function(){
	// Set add song to BOX
	$("ul.mtool a.madd").click(function(){
		$(this).removeClass("madd").addClass("madded"); 
		addplaylist($(this).attr("name"));
	});
	
	// Autocomplete search
	NVMS.search.imgloader = $('#msimgload');
	NVMS.search.txtsearch = $('#mstxtsearch');
	
	NVMS.search.txtsearch.attr('autocomplete', 'off');
	
	// Load search
	$('#mstxtsearch').keyup(function(){
		var q = trim( $('#mstxtsearch').val() ); // Only NukeViet has trim()

		if( q.length >= NVMS.search.minchar ){
			NVMS.search.q = q;
		
			// Hien thi anh load
			NVMS.search.imgloader.show();
				
			// Xoa dinh thoi
			clearTimeout( NVMS.search.timer );
				
			// Xac lap dinh thoi load ket qua
			NVMS.search.timer = setTimeout( "NVMS.search.load()", NVMS.search.timeout );
		}else{
			clearTimeout( NVMS.search.timer );
			NVMS.search.imgloader.hide();
			$('#msressearch').hide();
			NVMS.search.showres = false;
		}
	});
	
	// Close search
	$(document).click(function(){
		$('#msressearch, #mstxtsearch').click(function(){
			NVMS.search.allowclose = false;
		});
		if( NVMS.search.allowclose == true ){
			$('#msressearch').hide();
			NVMS.search.showres = false;
		}else{
			NVMS.search.allowclose = true;
		}
	});
	
	// An hien thong tin
	$('.ms-shd').click(function(){
		var v = $(this).attr('rel');
		v = v.split('|');
		
		if( v[0] == '0' ){
			$(this).removeClass('zoomin').addClass('zoomout').attr('rel', '1|' + v[1] + '|' + v[2] + '|' + v[3] ).attr('title',v[2]).text(v[2]);
			$('#'+v[3]).removeClass('ms-shdetail').addClass('ms-shdetaile');
		}else{
			$(this).removeClass('zoomout').addClass('zoomin').attr('rel', '0|' + v[1] + '|' + v[2] + '|' + v[3] ).attr('title',v[1]).text(v[1]);
			$('#'+v[3]).removeClass('ms-shdetaile').addClass('ms-shdetail');
		}
	});
});