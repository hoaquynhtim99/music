<!-- BEGIN: main -->
<div id="playlist"></div>
<div id="playlisttool">
	<a title="{LANG.playlist_showhide}" class="showplaylist"></a>
	<a href="{URL_CREAT}" title="{LANG.playlist_save}" class="saveplaylist"></a>
	<a title="{LANG.playlist_listen}" href="{URL}" class="listenplaylist"></a>
	<a title="{LANG.playlist_delete}" class="delplaylist"></a>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function(){
	resultplaylist( 'OK_');
	$("#playlist").hide();
    $("a.showplaylist").click(function(){
      $("#playlist").slideToggle("slow");
    });
    $("a.delplaylist").click(function(){
		if (confirm('{LANG.playlist_delete_confirm}')){
			$("#playlist").slideUp('fast', function(){$("#playlist").empty(); delplaylist();});
		}
    });
});
</script>
<!-- END: main -->
  