<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<caption>{LANG.cat_lits}</caption>
		<thead>
			<tr>
				<td style="width:50px">{LANG.weight}</td>
				<td>{LANG.title}</td>
				<td>{LANG.description}</td>
				<td style="width:100px" class="center">{LANG.siteinfo_numsong}</td>
				<td style="width:90px" class="center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr>
				<td>
                    <select class="music-input" name="weight" id="weight{ROW.id}" onchange="nv_change_cat_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.weight}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight -->
                    </select>
				</td>
				<td>{ROW.title}</td>
				<td>{ROW.description}</td>
				<td class="center">{ROW.numsong}</td>
				<td class="center">
					<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_category('{ROW.id}', '_category');">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
	</table>
</form>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" name="levelform" id="levelform">
	<a name="addeditarea"></a>
	<table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<col width="150"/>
		<col width="10"/>
		<tbody>
			<tr>
				<td>{LANG.title}</td>
				<td class="center"><span class="requie">*</span></td>
				<td><input type="text" name="title" value="{DATA.title}" class="music-input txt-half"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>{LANG.description}</td>
				<td>&nbsp;</td>
				<td><input type="text" name="description" value="{DATA.description}" class="music-input txt-full"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>{LANG.keywords}</td>
				<td>&nbsp;</td>
				<td><input type="text" name="keywords" value="{DATA.keywords}" class="music-input txt-full"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5"><input class="music-button" type="submit" name="submit" value="{LANG.submit}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->
