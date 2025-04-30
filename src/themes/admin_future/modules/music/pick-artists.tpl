<div class="modal modalPickItems" tabindex="-1" role="dialog" id="modalPickArtists" data-backdrop="static" data-changed="false" data-list="" data-inputname="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" name="q" value="" class="form-control" placeholder="{LANG.enter_keyword}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="nation_id" class="form-select">
                                <option value="0">{LANG.search_all_nation}</option>
                                <!-- BEGIN: nation -->
                                <option value="{NATION.nation_id}">{NATION.nation_name}</option>
                                <!-- END: nation -->
                            </select>
                        </div>
                        <input type="hidden" name="artist_id_selected" value="">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="mode" value="">
                        <button type="button" name="submitform" class="btn btn-primary" data-allowedpage="false"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{GLANG.search}</button>
                    </div>
                </div>
                <div class="item-lists"></div>
                <h2>{LANG.picked_list}</h2>
                <p class="text-info"><i>{LANG.drag_and_drop_to_sort}</i></p>
                <ul class="item-selected"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="completePickArtist">{LANG.save}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{LANG.close}</button>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN: last_singers -->
<ul class="hidden d-none hide" id="LastPickedSingers">
    <!-- BEGIN: loop -->
    <li>
        <input type="hidden" name="singer_ids[]" value="{SINGER.artist_id}">
        <a class="delitem" href="#" data-toggle="delPickedArtist"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
        <strong class="val ms-ellipsis">{SINGER.artist_name}</strong>
    </li>
    <!-- END: loop -->
</ul>
<!-- END: last_singers -->

<!-- BEGIN: last_authors -->
<ul class="hidden d-none hide" id="LastPickedAuthors">
    <!-- BEGIN: loop -->
    <li>
        <input type="hidden" name="author_ids[]" value="{AUTHOR.artist_id}">
        <a class="delitem" href="#" data-toggle="delPickedArtist"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
        <strong class="val ms-ellipsis">{AUTHOR.artist_name}</strong>
    </li>
    <!-- END: loop -->
</ul>
<!-- END: last_authors -->
