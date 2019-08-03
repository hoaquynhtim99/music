<div id="msAbSoLrtSheetArea">
    <div id="msAbSoLrtSheetAreaLoader" class="hidden">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>
            </div>
        </div>
    </div>
    <div class="ms-detailso-lrt-sheet-tags hidden" id="msAbSoLrtSheetAreaCtn">
        <ul class="nav nav-tabs" role="tablist">
            <li data-toggle="msSoTabLrtSheetItem" role="presentation" id="tabctr-ms-detailso-tab-text"><a href="#ms-detailso-tab-text" aria-controls="ms-detailso-tab-text" role="tab" data-toggle="tab">{LANG.lyric}</a></li>
            <li data-toggle="msSoTabLrtSheetItem" role="presentation" id="tabctr-ms-detailso-tab-pdf"><a href="#ms-detailso-tab-pdf" aria-controls="ms-detailso-tab-pdf" role="tab" data-toggle="tab">{LANG.sheet}</a></li>
            <li data-toggle="msSoTabLrtSheetItem" role="presentation" id="tabctr-ms-detailso-tab-iframe"><a href="#ms-detailso-tab-iframe" aria-controls="ms-detailso-tab-iframe" role="tab" data-toggle="tab">{LANG.sheet}</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="ms-detailso-tab-text" data-toggle="msSoTabLrtSheetItem">
                <div class="ms-detailso-lrt">
                    <h3 class="ms-detailso-lrt-title">{LANG.lyric}: <span id="solrtName">...</span></h3>
                    <div class="ms-detailso-lrt-body" id="detail-song-lrt"></div>
                    <div class="ms-detailso-lrt-control">
                        <a href="#" class="ms-detailso-lrt-control-f" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="F" data-unique="detail-song-lrt">{LANG.view_full}</a>
                        <a href="#" class="ms-detailso-lrt-control-h" data-toggle="togglehview" data-target="#detail-song-lrt" data-mode="H" data-unique="detail-song-lrt">{LANG.view_haft}</a>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="ms-detailso-tab-pdf" data-toggle="msSoTabLrtSheetItem">
                <div class="ms-detailso-responsive-iframe ipdf">
                    <div class="inner-fixed-height"></div>
                    <div class="inner-content" data-toggle="msSoIframeSheet"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="ms-detailso-tab-iframe" data-toggle="msSoTabLrtSheetItem">
                <div class="ms-detailso-responsive-iframe">
                    <div class="inner-fixed-height"></div>
                    <div class="inner-content" data-toggle="msSoIframeSheet"></div>
                </div>
            </div>
        </div>
    </div>
</div>
