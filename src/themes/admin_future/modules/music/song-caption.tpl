<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger" role="alert">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}" autocomplete="off">
    <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_files} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row mb-3">
                <label for="caption_file" class="col-form-label text-sm-end col-sm-4">{LANG.mana_cc_webvtt}:</label>
                <div class="col-sm-8 col-md-5 col-lg-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="caption_file" name="caption_file" value="{DATA.caption_file}">
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="button" data-toggle="browse" data-area="caption_file" data-type="file" data-path="{RESOURCE_PATH}" data-currentpath="{RESOURCE_CURRPATH}">{GLANG.browse_file}</button>
                        </div>
                    </div>
                    <i class="form-text text-muted">{LANG.mana_cc_webvtt_help}.</i>
                </div>
            </div>
            <div class="row">
                <label for="caption_pdf" class="col-form-label text-sm-end col-sm-4">{LANG.mana_cc_pdf}:</label>
                <div class="col-sm-8 col-md-5 col-lg-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="caption_pdf" name="caption_pdf" value="{DATA.caption_pdf}">
                        <div class="input-group-btn">
                            <button class="btn btn-success" type="button" data-toggle="browse" data-area="caption_pdf" data-type="file" data-path="{RESOURCE_PATH}" data-currentpath="{RESOURCE_CURRPATH}">{GLANG.browse_file}</button>
                        </div>
                    </div>
                    <i class="form-text text-muted">{LANG.mana_cc_pdf_help}.</i>
                </div>
            </div>
        </div>
    </div>
    <h2><i class="fa fa-file-o" aria-hidden="true"></i> {LANG.mana_cc_text} ({LANG.apply_for} <strong>{LANG_DATA_NAME}</strong>):</h2>
    <div class="panel panel-default">
        <div class="panel-body">
            {DATA.caption_data}
        </div>
    </div>
    <div class="row mb-3">
        <div class="text-center">
            <input type="hidden" name="submitform" value="1"/>
            <input type="submit" value="{GLANG.save}" class="btn btn-primary"/>
        </div>
    </div>
</form>
<!-- END: main -->
