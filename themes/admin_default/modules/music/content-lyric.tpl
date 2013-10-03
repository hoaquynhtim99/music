<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form method="post" name="add_pic">
	<table class="tab1">
		<col width="200px"/>
		<tbody>
			<tr>
				<td class="strong aright">{LANG.upboi}<span class="requie"> (*)</span></td>
				<td>
					<input name="user" class="music-input txt-half" value="{DATA.user}" type="text" original-title="{LANG.tip_user}"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="3">
					<p><strong>{LANG.add_lyric}<span class="requie"> (*)</span></strong></p>
					{DATA.body}
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="center"><input class="music-button" name="submit" value="{LANG.save}" type="submit"></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->