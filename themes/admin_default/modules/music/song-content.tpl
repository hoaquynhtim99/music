<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="form-group">
            <label for="title" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> Tên video-clip:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control required" type="text" name="title" id="title" value="" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="tid" class="control-label col-sm-8">Nằm trong thể loại:</label>
            <div class="col-sm-16 col-lg-6">
                <select class="form-control" name="tid" id="tid">
                                        <option value="2">
                        Thời sự
                    </option>
                    <option value="6">
                        |--&gt; Trong nước
                    </option>
                    <option value="7">
                        |--&gt; Quốc tế
                    </option>
                    <option value="1">
                        Xã hội
                    </option>
                    <option value="3">
                        Giải Trí
                    </option>
                    <option value="4">
                        Thể thao
                    </option>
                    <option value="5">
                        Thế giới muôn màu
                    </option>
                    <option value="8">
                        Hướng dẫn Guitar
                    </option>
                    <option value="9">
                        Guitar Cover
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="internalpath" class="control-label col-sm-8">Tập tin nội bộ:</label>
            <div class="col-sm-16 col-lg-6">
                <div class="input-group">
                    <input class="form-control" type="text" name="internalpath" id="internalpath" value="" maxlength="255" />
                    <span class="input-group-btn">
                        <button class="btn btn-success selectfile" type="button">Duyệt trên máy chủ</button>
                      </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="externalpath" class="control-label col-sm-8">Tập tin bên ngoài:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="externalpath" id="externalpath" value="" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="img" class="control-label col-sm-8">Hình minh họa:</label>
            <div class="col-sm-16 col-lg-6">
                <div class="input-group">
                    <input class="form-control" type="text" name="img" id="img" value="" maxlength="255" />
                    <span class="input-group-btn">
                        <button class="btn btn-success selectimg" type="button">Duyệt trên máy chủ</button>
                      </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="hometext" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> Mô tả ngắn gọn:</label>
            <div class="col-sm-16 col-lg-6">
                <textarea class="form-control required" name="hometext" id="hometext" rows="5"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="keywords" class="control-label col-sm-8">Từ khóa tìm kiếm:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="keywords" id="keywords" value="" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="showcover" id="showcover" value="1"  checked="checked" /> Dùng ảnh đại diện làm ảnh cover Video (Không có tác dụng nếu không có ảnh đại diện)
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-24">
                <div class="ckeditor required">
                    <label class="control-label">Mô tả chi tiết <i class="fa fa-asterisk"></i></label>
                    <div class="clearfix">
                        <textarea style="width: 100%; height:300px;" id="video_clip_bodytext" name="bodytext"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
                <input type="hidden" name="submit" value="1"/>
                <input name="redirect" type="hidden" value="0" />
                <input type="submit" value="Lưu lại" class="btn btn-primary"/>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->