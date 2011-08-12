/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Createdate 26 - 12 - 2010 5 : 12
 */
 
function play_song(player, o)
{
	jwplayer(player).load(o.name);
	
	var this_song = o.id;
	this_song = this_song.split('-');
	nv_current_song = this_song[1];
	nv_control_playlist(nv_current_song);
}

function nv_start_player(player)
{
	var url = $('#song-' + nv_current_song).attr('name');
	jwplayer(player).load(url);
	nv_control_playlist(nv_current_song);
}

function nv_complete_song(player)
{	
	if( nv_current_song < nv_num_song )
	{
		nv_current_song ++;
	}
	else
	{
		nv_current_song = 1;
	}
	var url = $('#song-' + nv_current_song).attr('name');
	jwplayer(player).load(url);
	nv_control_playlist(nv_current_song);
}

function nv_control_playlist(this_song)
{
	$('#playlist-container').find('div').removeClass('iteming');
	$('#song-wrap-'+this_song).addClass('iteming');
}