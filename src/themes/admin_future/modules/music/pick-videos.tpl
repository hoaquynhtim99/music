<div class="modal modalPickItems" tabindex="-1" role="dialog" id="modalPickVideos" data-bs-backdrop="static" data-changed="false" data-list="" data-inputname="" data-multiple="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{LANG.close}"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-inline">
                        <div class="form-group">
                            <input type="text" name="q" value="" class="form-control" placeholder="{LANG.enter_keyword}">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="cat_id" class="form-select">
                                <option value="0">{LANG.search_all_cat}</option>
                                <!-- BEGIN: cat1 -->
                                <option value="{CAT.cat_id}">{CAT.cat_name}</option>
                                <!-- END: cat1 -->
                            </select>
                        </div>
                        <input type="hidden" name="video_id_selected" value="">
                        <input type="hidden" name="page" value="1">
                        <button type="button" name="submitform" class="btn btn-primary" data-allowedpage="false"><span class="load hidden"><i class="fa fa-spin fa-spinner"></i> </span>{GLANG.search}</button>
                    </div>
                </div>
                <div class="item-lists"></div>
                <h2>{LANG.picked_list}</h2>
                <p class="text-info"><i>{LANG.drag_and_drop_to_sort}</i></p>
                <ul class="item-selected"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-toggle="completePickVideo">{LANG.save}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{LANG.close}</button>
            </div>
        </div>
    </div>
</div>
