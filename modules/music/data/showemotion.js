/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Createdate 26 - 12 - 2010 5 : 12
 */

$(function(){
	$("#showemotion").click(function(){
		$("#emotion").show();
	});
	$("#emotion a").click(function(){
		var value = $("#commentbody").val();
		var add = $(this).attr("title");
		$("#commentbody").val( value + add);
		$("#emotion").hide();
	});
});
