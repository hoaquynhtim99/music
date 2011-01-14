<!-- BEGIN: main -->
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.upload_song}</strong>
		</div>
	</div>
	<div style="padding:5px;">
<!-- BEGIN: noaccess -->
<p align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
<!-- END: noaccess -->
<!-- BEGIN: access -->
<strong>{LANG.upload_rule}</strong>
<ul>
    <li>{LANG.upload_rule1}</li>
    <li>{LANG.upload_rule2}</li>
    <li>{LANG.upload_rule3}</li>
</ul>
<strong>{LANG.upload_guide}</strong>
<ul>
    <li>{LANG.upload_guide1}</li>
    <li>{LANG.upload_guide2}</li>
    <li>{LANG.upload_guide3}</li>
    <li>{LANG.upload_guide4}</li>
</ul>
<strong>{LANG.upload_note}</strong>
<ul>
    <li>{LANG.upload_note1}</li>
    <li>{LANG.upload_note2}</li>
</ul>

<script type="text/javascript">
(function($) 
{
	$.fileUploader = {version: '1.0'};
	$.fn.fileUploader = function(config)
	{
		config = $.extend({}, 
		{
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
			if ( newsinger != '{LANG.upload_quicksinger}' ){
				singer = newsinger;
			}
			if ( song == '{LANG.song_name}' ){
				alert ("{LANG.upload_notsong}");
				return false;
			}
			if ( singer == 'ns' ){
				if ( confirm( "{LANG.upload_allow_notsinger}" ) == 0 )
				{
					return false;
				}
			}
			if (fname == -1){
				alert ("{LANG.upload_inviad}!");
				$("#pxupload" + itr + "_input").val("");
				return false;
			}
			$('#px_button input').removeAttr("disabled");
			var imageLoader = '';
			if ($.trim(config.imageLoader) != ''){
				imageLoader = '<img src="'+ config.imageLoader +'" alt="uploader" />';
			}
			var singeropinion = '{singerdata}';
			
			var display = '<div class="uploadData" id="pxupload'+ itr +'_text" title="pxupload'+ itr +'">' + 
				'<div class="close">&nbsp;</div>' +
				'<span class="fname">{LANG.song_name}: ' + song + '</span>' +
				'<span class="fname">{LANG.singer}: ' + singer + '</span>' +
				'<span class="loader" style="display:none">'+ imageLoader +'</span>' +
				'<div class="status">{LANG.upload_ready}...</div></div>';
			
			$("#px_display").append(display);
			$("#pxupload" + itr + "_input").hide();
			$("#song" + itr).hide();
			$("#singer" + itr).hide();
			$("#newsinger" + itr).hide();
			$("#category" + itr).hide();
			$("#add" + itr).hide();
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
				$(form).before(' \
					<div id="pxupload_form"></div> \
					<div id="px_display"></div> \
					<div id="px_button"></div> \
				');
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
				
				'<input style="width:400px;" id="song' + itr + '" name="song" type="text" value="{LANG.song_name}"/>' +
				'<select style="width:404px;" name="singer" id="singer' + itr + '">{singerdata}' +
				'<input style="width:400px;" id="newsinger' + itr + '" name="newsinger" type="text" value="{LANG.upload_quicksinger}"/>' +
				'<select style="width:200px;" name="category" id="category' + itr + '">{category}' +
				'<input type="file" name="'+ config.inputName +'" id="'+ inputId +'" size="'+ config.inputSize +'" />' +
				
				
				'<input id="add' + itr + '" type="button" value="{LANG.add}" onclick="$.fileUploader.change();" />' +
				'</form>' + 
				'<iframe id="'+ iframeId +'" name="'+ iframeId +'" src="about:blank" style="display:none"></iframe>';
				
				$("#pxupload_form").append( contents );
			},
			validateFile: function(file) {
				if (file.indexOf('/') > -1){
					file = file.substring(file.lastIndexOf('/') + 1);
				}else if (file.indexOf('\\') > -1){
					file = file.substring(file.lastIndexOf('\\') + 1);
				}
				var extensions = new RegExp(config.allowedExtension + '$', 'i');
				if (extensions.test(file)){
					return file;
				} else {
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
<input class="submitbutton" type="submit" value="{LANG.upload_ok}" id="pxUpload" />
<input class="submitbutton" type="reset" value="{LANG.cancel}" id="pxClear" />
</form>
<script type="text/javascript">
$(function(){
	$('#yourInputFileId').fileUploader({
		imageLoader: '{DATA_URL}image_upload.gif',
		allowedExtension: 'mp3'
	});
});
</script>
<!-- END: access -->
</div>
<div class="clear"></div>
</div>
<!-- END: main -->