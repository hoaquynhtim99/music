<!-- BEGIN: main -->
<form>
    <div class="table-responsive">
        <table class="table ms-table-list-with-action-bottom">
            <thead>
                <tr>
                    <th style="width:5%">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                    </th>
                    <th style="width:10%">{LANG.weight}</th>
                    <th style="width:25%">{LANG.title}</th>
                    <th style="width:20%">{LANG.stat}</th>
                    <th style="width:15%">{LANG.create}</th>
                    <th style="width:15%">{LANG.update}</th>
                    <th style="width:10%" class="text-right">{LANG.action}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <input class="ms-check-in-list" type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                    </td>
                    <td>
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm btn-block btn-changeweight ms-btn-in-list" data-type="weight" data-max="{MAX_WEIGHT}" data-value="{ROW.weight}" data-op="{OP}" data-id="{ROW.nation_id}">
                            <span class="text">{ROW.weight}</span>
                            <span class="caret"></span>
                        </button>
                    </td>
                    <td>
                        <h3>{ROW.nation_name}</h3>
                        <small class="text-muted">{ROW.nation_code}</small>
                    </td>
                    <td>
                        <span title="{LANG.stat_singers}"><span class="text-muted">{LANG.acr_singer}</span>&nbsp;{ROW.stat_singers}</span><br />
                        <span title="{LANG.stat_authors}"><span class="text-muted">{LANG.acr_author}</span>&nbsp;{ROW.stat_authors}</span>
                    </td>
                    <td>{ROW.time_add}<br /><small class="text-muted">{ROW.time_add_time}</small></td>
                    <td>{ROW.time_update}<br /><small class="text-muted">{ROW.time_update_time}</small></td>
                    <td class="text-right">
                        <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm ms-btn-in-list">
                            {LANG.select} <span class="caret"></span>
                        </button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="">
                        <div class="row">
                            <div class="col-sm-10 form-inline">
                                <div class="form-group">
                                    <button data-toggle="mscallpop" type="button" class="btn btn-default btn-sm">
                                        {LANG.with_selected} <span class="caret"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-14">
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->