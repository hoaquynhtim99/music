<!-- BEGIN: main -->
<!-- BEGIN: css -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE_CSS}/css/{MODULE_THEME}.css">
<!-- END: css -->
<div class="ms-block-auto-search">
    <form method="get" action="{FORM_ACTION}" data-tokend="{TOKEND}" data-toggle="msAutoSearchForm{CONFIG.bid}">
        <!-- BEGIN: no_rewrite -->
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
        <input type="hidden" name="{NV_OP_VARIABLE}" value="search">
        <!-- END: no_rewrite -->
        <input type="text" class="form-control" name="q" value="{Q}" autocomplete="off" placeholder="{LANG.search_placeholder}" data-toggle="msAutoSearchIpt{CONFIG.bid}">
        <button class="btn btn-default msSubmitAutoSearch{CONFIG.bid}" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
    </form>
    <div class="search-result" data-toggle="msAutoSearchResult{CONFIG.bid}"></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var form = $('[data-toggle="msAutoSearchForm{CONFIG.bid}"]');
    var input = $('[data-toggle="msAutoSearchIpt{CONFIG.bid}"]');
    var resultArea = $('[data-toggle="msAutoSearchResult{CONFIG.bid}"]');
    var timerSearch;
    var offsetResult = 0;
    var numberResult = 12;
    var oldkey = '';

    input.on('keyup', function(e) {
        if (e.keyCode == 27) {
            // Ấn ESC thì đóng kết quả tìm kiếm
            resultArea.html('').hide();
            numberResult = 0;
            offsetResult = 0;
            return false;
        } else if (e.keyCode == 38 || e.keyCode == 40) {
            // Phím mũi tên lên xuống để chọn kết quả
            if (numberResult > 0) {
                if (e.keyCode == 38) {
                    offsetResult--;
                } else {
                    offsetResult++;
                }
                if (offsetResult < 1) {
                    offsetResult = numberResult;
                } else if (offsetResult > numberResult) {
                    offsetResult = 1;
                }
                $('.body-item', resultArea).removeClass('hover');
                $('.body-item[data-offset="' + offsetResult + '"]', resultArea).addClass('hover');
                return false;
            }
        } else if (e.keyCode == 13) {
            // Phím Enter để đi đến kết quả nếu đang chọn một kết quả nào đó
        }
        var key = trim(input.val());
        if (key == oldkey) {
            return false;
        }
        oldkey = key;
        if (key != '') {
            if (timerSearch) {
                clearTimeout(timerSearch);
            }
            timerSearch = setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
                    data: {
                        'autoCompleteSearch': 1,
                        'key': key,
                        'tokend': '{TOKEND}'
                    }
                }).done(function(res) {
                    numberResult = res.data_songs.length + res.data_videos.length + res.data_albums.length + res.data_artists.length;
                    offsetResult = 0;
                    if (numberResult < 1) {
                        resultArea.html('').hide();
                        return true;
                    }
                    var htmlAll = '', htmlItem = '', offset = 0;
                    // Bài hát
                    if (res.data_songs.length) {
                        htmlItem = '';
                        $.each(res.data_songs, function(key, row) {
                            offset++;
                            htmlItem += '<div class="body-item" data-offset="' + offset + '"><a href="' + row.link + '" class="ms-so">' + row.title + '</a> - <a href="' + row.link + '" class="ms-singer">' + row.singer + '</a></div>';
                        });
                        htmlAll += '<div class="result-heading"><i class="fa fa-music" aria-hidden="true"></i> {LANG.song}</div><div class="result-body">' + htmlItem + '</div>';
                    }

                    // MV
                    if (res.data_videos.length) {
                        htmlItem = '';
                        $.each(res.data_videos, function(key, row) {
                            offset++;
                            htmlItem += '<div class="body-item" data-offset="' + offset + '"><a href="' + row.link + '" class="ms-so">' + row.title + '</a> - <a href="' + row.link + '" class="ms-singer">' + row.singer + '</a></div>';
                        });
                        htmlAll += '<div class="result-heading"><i class="fa fa-file-video-o" aria-hidden="true"></i> {LANG.video_alias}</div><div class="result-body">' + htmlItem + '</div>';
                    }

                    // Album
                    if (res.data_albums.length) {
                        htmlItem = '';
                        $.each(res.data_albums, function(key, row) {
                            offset++;
                            htmlItem += '<div class="body-item" data-offset="' + offset + '"><a href="' + row.link + '" class="ms-so">' + row.title + '</a> - <a href="' + row.link + '" class="ms-singer">' + row.singer + '</a></div>';
                        });
                        htmlAll += '<div class="result-heading"><i class="fa fa-briefcase" aria-hidden="true"></i> {LANG.album}</div><div class="result-body">' + htmlItem + '</div>';
                    }

                    // Nghệ sĩ
                    if (res.data_artists.length) {
                        htmlItem = '';
                        $.each(res.data_artists, function(key, row) {
                            offset++;
                            htmlItem += '<div class="body-item" data-offset="' + offset + '"><a href="' + row.link + '" class="ms-so">' + row.title + '</a></div>';
                        });
                        htmlAll += '<div class="result-heading"><i class="fa fa-user-circle" aria-hidden="true"></i> {LANG.artist}</div><div class="result-body">' + htmlItem + '</div>';
                    }

                    resultArea.html(htmlAll).show();
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                });
            }, 300);
        } else if (timerSearch) {
            clearTimeout(timerSearch);
        }
        if (key == '') {
            numberResult = 0;
            offsetResult = 0;
            resultArea.html('').hide();
        }
    });

    form.on('submit', function(e) {
        // Không submit khi chưa nhập từ khóa
        if (trim(input.val()) == '') {
            input.focus();
            e.preventDefault();
            return false;
        }
        // Chuyến đến trang kết quả
        var item = $('.body-item[data-offset="' + offsetResult + '"]', resultArea);
        if (item.length && resultArea.is(':visible')) {
            window.location = $('.ms-so', item).attr('href');
            e.preventDefault();
            return false;
        }
    });

    // Đóng kết quả tìm kiếm khi nhấp chuột vào chỗ khác
    $(document).on('click', function(e) {
        if (!$(e.target).is('a') && ($(e.target).closest('[data-toggle="msAutoSearchResult{CONFIG.bid}"]').length || $(e.target).is('[data-toggle="msAutoSearchResult{CONFIG.bid}"]'))) {
            return false;
        }
        if (!$(e.target).is('.msSubmitAutoSearch{CONFIG.bid}')) {
            numberResult = 0;
            offsetResult = 0;
            resultArea.hide();
        }
    });
});
</script>
<!-- END: main -->
