<!-- BEGIN: main -->
<div class="alboxw">
	<div class="alwrap">
		<div class="alheader"> 
			<span>{LANG.upload_song}</span>
		</div>
	</div>
</div>
<!-- BEGIN: noaccess -->
<div class="alboxw">
	<div class="alwrap alcontent infoerror">
		<div>
			{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}
		</div>
	</div>
</div>
<!-- END: noaccess -->
<!-- BEGIN: stopaccess -->
<div class="alboxw">
	<div class="alwrap alcontent infoerror">
		<div>
			{LANG.setting_stop}
		</div>
	</div>
</div>
<!-- END: stopaccess -->
<!-- BEGIN: access -->
<blockquote><strong>{LANG.upload_rule}</strong></blockquote>
<span class="musicicon mplay">{LANG.upload_rule1}</span><br />
<span class="musicicon mplay">{LANG.upload_rule2}</span><br />
<span class="musicicon mplay">{LANG.upload_rule3} {MAXUPLOAD}MB</span><br />
<blockquote><strong>{LANG.upload_guide}</strong></blockquote>
<span class="musicicon mplay">{LANG.upload_guide1}</span><br />
<span class="musicicon mplay">{LANG.upload_guide2}</span><br />
<span class="musicicon mplay">{LANG.upload_guide3}</span><br />
<span class="musicicon mplay">{LANG.upload_guide4}</span><br />
<blockquote><strong>{LANG.upload_note}</strong></blockquote>
<span class="musicicon mplay">{LANG.upload_note1}</span><br />
<span class="musicicon mplay">{LANG.upload_note2}</span><br />
<div class="hr"></div>
<script type="text/javascript">
(function($){
	$.fileUploader = {version: '1.0'};
	$.fn.fileUploader = function(config){
		config = $.extend({},{
			imageLoader: '',
			buttonUpload: '#pxUpload',
			buttonClear: '#pxClear',
			successOutput: '{LANG.upload_s}',
			errorOutput: '{LANG.upload_f}',
			inputName: 'userfile',
			inputSize: 40,
			allowedExtension: 'jpg|jpeg|gif|png'
		}, config);
		var itr = 0;
		$.fileUploader.change = function(){
			var fname = px.validateFile( $("#pxupload" + itr + "_input").val() );
			var song = document.getElementById('song' + itr).value;
			var newsinger = $("#newsinger" + itr).val();
			var singer = $("#singer" + itr).val();
			if ( newsinger != '' ){
				singer = newsinger;
			}
			if ( song == '' ){
				alert ("{LANG.upload_notsong}");
				return false;
			}
			if ( singer == 'ns' ){
				if( !confirm( "{LANG.upload_allow_notsinger}" )){
					return false;
				}
			}
			if( $("#pxupload" + itr + "_input").val() == '' ){
				alert ("{LANG.upload_error_empty_song}");
				return false;
			}
			if (fname == -1){
				alert ("{LANG.upload_inviad}!");
				$("#pxupload" + itr + "_input").val("");
				return false;
			}
			$('#px_button input').removeAttr("disabled");
			var imageLoader = '';
			if ($.trim(config.imageLoader) != ''){
				imageLoader = '<img src="'+ config.imageLoader +'" alt="Uploader" />';
			}
			
			var display = '<div class="uploadData" id="pxupload'+ itr +'_text" title="pxupload'+ itr +'">' + 
			'<div class="close">&nbsp;</div>' +
			'<strong class="greencolor">' + song + '</strong> - <strong class="greencolor">' + singer + '</strong>' +
			' <span class="loader" style="display:none">'+ imageLoader +'</span>' +
			'<div class="status">{LANG.upload_ready}...</div></div>';
			
			$("#px_display").append(display);
			$("#pxupload" + itr).hide();
			px.appendForm();
		}
		$(config.buttonUpload).click(function(){
			if (itr > 1){
				$('#px_button input').attr("disabled","disabled");
				$("#pxupload_form form").each(function(){
					e = $(this);
					var id = "#" + $(e).attr("id");
					var input_id = id + "_input";
					var input_val = $(input_id).val();
					if (input_val != ""){
						$(id + "_text .status").text("{LANG.upload_prosess}...");
						$(id + "_text").css("background-color", "#FFF0E1");
						$(id + "_text .loader").show();
						$(id + "_text .close").hide();
						
						$(id).submit();
						$(id +"_frame").load(function(){
							$(id + "_text .loader").hide();
							up_output = $(this).contents().find("#output").text();
							if (up_output == "success"){
								$(id + "_text").css("background-color", "#F0F8FF");
								up_output = config.successOutput;
							}else{
								$(id + "_text").css("background-color", "#FF0000");
								up_output = config.errorOutput;
							}
							up_output += '<br />' + $(this).contents().find("#message").html();
							$(id + "_text .status").html(up_output);
							$(e).remove();
							$(config.buttonClear).removeAttr("disabled");
						});
					}
				});
			}
		});
		$(".close").live("click", function(){
			var id = "#" + $(this).parent().attr("title");
			$(id+"_frame").remove();
			$(id).remove();
			$(id+"_text").fadeOut("slow",function(){
				$(this).remove();
			});
			return false;
		});
		$(config.buttonClear).click(function(){
			$("#px_display").fadeOut("slow",function(){
				$("#px_display").html("");
				$("#pxupload_form").html("");
				itr = 0;
				px.appendForm();
				$('#px_button input').attr("disabled","disabled");
				$(this).show();
			});
		});
		var px = {
			init: function(e){
				var form = $(e).parents('form');
				px.formAction = $(form).attr('action');
				$(form).before('<div id="pxupload_form"></div><div id="px_display"></div><div id="px_button"></div>');
				$(config.buttonUpload+','+config.buttonClear).appendTo('#px_button');
				if ( $(e).attr('name') != '' ){
					config.inputName = $(e).attr('name');
				}
				if ( $(e).attr('size') != '' ){
					config.inputSize = $(e).attr('size');
				}
				$(form).hide();
				this.appendForm();
			},
			appendForm: function(){
				itr++;
				var formId = "pxupload" + itr;
				var iframeId = "pxupload" + itr + "_frame";
				var inputId = "pxupload" + itr + "_input";
				var contents = '<form method="post" id="'+ formId +'" action="'+ px.formAction +'" enctype="multipart/form-data" target="'+ iframeId +'">' +
				'<table cellpadding="0" cellspacing="0" class="musictable">' +
				'<tr><td style="width:150px">{LANG.song_name}</td><td><input class="txt-full" id="song' + itr + '" name="song" type="text" value=""/></td></tr>' +
				'<tr><td>{LANG.singer}</td><td><select class="txt-full" name="singer" id="singer' + itr + '">{GDATA.singerdata}</td></tr>' +
				'<tr><td>{LANG.upload_quicksinger}</td><td><input class="txt-full" id="newsinger' + itr + '" name="newsinger" type="text" value=""/></td></tr>' +
				'<tr><td>{LANG.author}</td><td><select class="txt-full" name="author" id="author' + itr + '">{GDATA.authordata}</td></tr>' +
				'<tr><td>{LANG.upload_quickauthor}</td><td><input class="txt-full" id="newauthor' + itr + '" name="newauthor" type="text" value=""/></td></tr>' +
				'<tr><td>{LANG.category_2}</td><td><select class="txt-full" name="category" id="category' + itr + '">{GDATA.category}</td></tr>' +
				'<tr><td>{LANG.upload_selectfile}</td><td><input class="txt-full" type="file" name="'+ config.inputName +'" id="'+ inputId +'" size="'+ config.inputSize +'" /></td></tr>' +
				'<tr><td colspan="2" class="mcenter"><input class="mbutton" id="add' + itr + '" type="button" value="{LANG.add}" onclick="$.fileUploader.change();" /></td></tr>' +
				'</table>' +
				'</form>' + 
				'<iframe id="'+ iframeId +'" name="'+ iframeId +'" src="about:blank" style="display:none"></iframe>';
				$("#pxupload_form").append(contents);
			},
			validateFile: function(file){
				if (file.indexOf('/') > -1){
					file = file.substring(file.lastIndexOf('/') + 1);
				}else if (file.indexOf('\\') > -1){
					file = file.substring(file.lastIndexOf('\\') + 1);
				}
				var extensions = new RegExp(config.allowedExtension + '$', 'i');
				if (extensions.test(file)){
					return file;
				}else{
					return -1;
				}
			}
		}
		px.init(this);	
		return this;
	}
})(jQuery);
</script>
<form action="{DATA_ACTION}" method="post" enctype="multipart/form-data">
	<input id="yourInputFileId" name="uploadfile" type="file" />
	<input class="mbutton" type="submit" value="{LANG.upload_ok}" id="pxUpload" />
	<input class="mbutton" type="reset" value="{LANG.cancel}" id="pxClear" />
</form>
<script type="text/javascript">
$(function(){
	$('#yourInputFileId').fileUploader({
		imageLoader: nv_siteroot + 'images/load_bar.gif',
		allowedExtension: 'mp3'
	});
});
</script>
<!-- END: access -->
<!-- END: main -->