<!-- BEGIN: main -->
<div class="cquicksed-hd">
	<a href="{URL_SEARCH}" title="{LANG.search_width}">{LANG.search_width} <strong>{Q}</strong></a>
</div>
<div class="wquicksed-dt">
	<!-- BEGIN: singer -->
	<div class="mss-item">
		<div class="mssit-left">
			{LANG.singer}
		</div>
		<div class="mssit-right">
			<!-- BEGIN: loop -->
			<a href="{SINGER.link}" class="it">
				<img class="musicsmalllalbum fl" src="{SINGER.thumb}" alt="{SINGER.title}" width="30" height="30"/>
				{SINGER.title}<br />
				<span class="msmall"> {SINGER.singer}</span>
				<div class="clear"></div>
			</a>
			<!-- END: loop -->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: singer -->
	<!-- BEGIN: album -->
	<div class="mss-item">
		<div class="mssit-left">
			{LANG.album}
		</div>
		<div class="mssit-right">
			<!-- BEGIN: loop -->
			<a href="{ALBUM.link}" class="it">
				<img class="musicsmalllalbum fl" src="{ALBUM.thumb}" alt="{ALBUM.title}" width="30" height="30"/>
				{ALBUM.title}<br />
				<span class="msmall"> {ALBUM.singer}</span>
				<div class="clear"></div>
			</a>
			<!-- END: loop -->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: album -->
	<!-- BEGIN: playlist -->
	<div class="mss-item">
		<div class="mssit-left">
			{LANG.search_quick_res_playlist}
		</div>
		<div class="mssit-right">
			<!-- BEGIN: loop -->
			<a href="{PLAYLIST.link}" class="it">
				<img class="musicsmalllalbum fl" src="{PLAYLIST.thumb}" alt="{PLAYLIST.title}" width="30" height="30"/>
				{PLAYLIST.title}<br />
				<span class="msmall"> {PLAYLIST.singer}</span>
				<div class="clear"></div>
			</a>
			<!-- END: loop -->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: playlist -->
	<!-- BEGIN: video -->
	<div class="mss-item">
		<div class="mssit-left">
			{LANG.video}
		</div>
		<div class="mssit-right">
			<!-- BEGIN: loop -->
			<a href="{VIDEO.link}" class="it">
				<img class="musicsmalllalbum fl" src="{VIDEO.thumb}" alt="{VIDEO.title}" width="50" height="30"/>
				{VIDEO.title}<br />
				<span class="msmall"> {VIDEO.singer}</span>
				<div class="clear"></div>
			</a>
			<!-- END: loop -->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: video -->
	<!-- BEGIN: song -->
	<div class="mss-item">
		<div class="mssit-left">
			{LANG.search_quick_res_song}
		</div>
		<div class="mssit-right">
			<!-- BEGIN: loop -->
			<a href="{SONG.link}" class="it">
				{SONG.title}<br />
				<span class="msmall"> {SONG.singer}</span>
			</a>
			<!-- END: loop -->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: song -->
</div>
<div class="clear"></div>
<!-- END: main -->