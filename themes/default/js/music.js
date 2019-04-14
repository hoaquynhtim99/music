/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

var loadingHtml = '<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>';
var msAjaxURL = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime();

// Tải và hiển thị lời bài hát
function msLoadLyric(soCode, soTitle, tokend, resTitle, resDoby) {
    $(resTitle).html(soTitle);
    $(resDoby).removeClass('open');
    $(resDoby).html(loadingHtml);
    $.post(msAjaxURL, 'getSongLyric=1&song_code=' + soCode + '&tokend=' + tokend, function(res) {
        $(resDoby).html(res);
    });
}

// Kiểu lời bài hát trong trình phát
function msJwplayerStyleCaption(jw) {
    jw.setCaptions({
        color: '#fff',
        fontSize: 32,
        fontOpacity: 90,
        edgeStyle: 'uniform',
        backgroundOpacity: 0
    });
}

// Các thao tác xử lý khi Facebook SDK load hoàn tất
if (typeof window.fbAsyncInit != "function") {
    window.fbAsyncInit = function() {
        var FBAppID = $("#fb-root").data("fb-app-id");
        FB.init({
            appId: FBAppID,
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v3.2'
        });

        // Cho phép ấn vào các nút chia sẻ
        $('[data-toggle="share-song-fb"]').find("i").removeClass("fa-spin fa-spinner").addClass("fa-share-alt");
    };
}

$(document).ready(function() {
    // Hiển thị danh sách ca sĩ của bài hát có quá nhiều ca sĩ
    $('[data-toggle="show-va-singer"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).data('target'));
    });

    // Hiển thị, thu gọn nội dung
    $('[data-toggle="togglehview"]').click(function(e) {
        e.preventDefault();
        var tg = $(this).data('target');
        var uq = $(this).data('unique');
        var md = $(this).data('mode');
        $(tg).toggleClass('open');
        $('[data-toggle="togglehview"]').each(function() {
            if ($(this).data('unique') == uq) {
                if ($(this).data('mode') == md) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }
        });
    });

    // Cuộn trang tới đối tượng
    $('[data-toggle="scrolltodiv"]').click(function(e) {
        e.preventDefault();
        var target = $($(this).data('target'));
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top
            }, 500);
        }
    });

    // Chọn tất cả
    $(document).delegate('[data-toggle="select-all"]', 'click focus', function() {
        $(this).select();
    });

    // Nút chia sẻ lên facebook
    $('[data-toggle="share-song-fb"]').on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        var icon = $this.find("i");
        if (icon.is(".fa-spin")) {
            return;
        }
        icon.removeClass("fa-share-alt").addClass("fa-spin fa-spinner");
        FB.ui({
            method: 'share',
            display: 'popup',
            href: $this.attr("href"),
        }, function(response) {
            if (typeof response != "undefined") {
                $.post(msAjaxURL, 'updateSongShares=1&song_code=' + $this.data("code") + '&tokend=' + $this.data("tokend"), function(res) {
                    icon.removeClass("fa-spin fa-spinner").addClass("fa-share-alt");
                });
            } else {
                icon.removeClass("fa-spin fa-spinner").addClass("fa-share-alt");
            }
        });
    });
});

$(window).on('load', function() {
    // Load facebook sau khi đã tải trang xong để đảm bảo độ mượt
    var script = document.getElementsByTagName("script")[0];
    var fb_app_id = ($('[property="fb:app_id"]').length > 0) ? $('[property="fb:app_id"]').attr("content") : '';
    var fb_locale = ($('[property="og:locale"]').length > 0) ? $('[property="og:locale"]').attr("content") : ((nv_lang_data == "vi") ? 'vi_VN' : 'en_US');
    var fb_script_id = "facebook-jssdk";

    // Thêm các thành phần cần thiết cho Facebook SDK
    if (!$("#fb-root").length) {
        $("body").append('<div id="fb-root" data-fb-app-id="' + fb_app_id + '"></div>');
    } else {
        $("#fb-root").data("fb-app-id", fb_app_id);
    }

    // Load Facebook SDK nếu chưa có
    if (!$("#" + fb_script_id).length) {
        var js = document.createElement("script");
        js.id = fb_script_id;
        js.src = "https://connect.facebook.net/" + fb_locale + "/all.js";
        script.parentNode.insertBefore(js, script);
    }
});
