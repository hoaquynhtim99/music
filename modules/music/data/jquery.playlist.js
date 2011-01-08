try {
	var playlistReady = playerReady;
} catch (err){
}

playerReady = function(obj) {
	setTimeout(function(){checkPlaylistLoaded(obj)}, 1);
	try {
		playlistReady(obj);
	} catch (err){
	}
}

function itemHandler(obj) {
	var item = obj['index'];
	var playlist = $("#"+obj['id']).next();
	var currentItem = 0;
	playlist.children().each(function(){
		if (currentItem == item) {
			$(this).addClass("playing");
		} else {
			$(this).removeClass("playing");
		}
		currentItem++;
	});
}

function checkPlaylistLoaded(obj) {
	var player = document.getElementById(obj['id']);
	var jsPlaylist = player.getPlaylist();
	if (jsPlaylist.length > 0) {
		var playlist = createPlaylist(obj);
		populatePlaylist(player, jsPlaylist, playlist);
		player.addControllerListener("PLAYLIST","playlistHandler");
		player.addControllerListener("ITEM","itemHandler");
	} else {
		setTimeout(function(){checkPlaylistLoaded(obj)}, 150);
	}
}

function createPlaylist(obj){
	var playerDiv = $("#"+obj['id']);
	playerDiv.after("<div class='jw_playlist_playlist'></div>");
	return playerDiv.next();
}

function playlistHandler(obj){
	var player = document.getElementById(obj['id']);
	var jsPlaylist = player.getPlaylist();
	var playerDiv = $("#"+obj['id']);
	var playlist = playerDiv.next();
	populatePlaylist(player, jsPlaylist, playlist);
}

function populatePlaylist(player, jsPlaylist, playlist){
	playlist.empty();
	for (var i=0;i<jsPlaylist.length;i++) {
		var jsItem = jsPlaylist[i];
		var alternate = "even";
		if (i % 2) {
			alternate = "odd";
		}
		playlist.append("<div class='jw_playlist_item "+alternate+"'>"+dump(jsItem)+"</div>");
	}
	var playlistItem = 0;
	playlist.children().each(function(){
		var currentItem = playlistItem;
		$(this).click(function () {
			player.sendEvent("ITEM", currentItem);
		});
		playlistItem++;
	});
}

function dump(arr) {
	var output = "<div class='jw_playlist_image_div'><img src='${image}' class='jw_playlist_image' /></div><div class='jw_playlist_title'>${title}</div><div class='jw_playlist_description'>&nbsp;${description}</div><div class='clear'></div>";
	var variables = getVars(output);
	for (var j=0; j<variables.length; j++) {
		var variable = variables[j];
		var varName = variable.replace('${','').replace('}','');
		var value = arr[varName];
		if (!value) {
			value = '';
		}
		output = output.replace(variable, value);
	}
	output = output.replace("<div class='jw_playlist_image_div'><img src='' class='jw_playlist_image' /></div>","");
	return output;
}

function dumpText(arr) {
	var dumped_text = "";
	if(typeof(arr) == 'object') {
		for(var item in arr) {
			var value = arr[item];		
			if(typeof(value) == 'object') {
				dumped_text += "<div class='"+item+"'>";
				dumped_text += dump(value);
				dumped_text += "</div>";
			} else {
				dumped_text += "<div class='"+item+"'>"+ value + "</div>";
			}
		}
	} else {
		dumped_text += arr+" ("+typeof(arr)+")";
	}
	return dumped_text;
}

function getVars(str){
	return str.match(/\$\{(.*?)\}/g);
}