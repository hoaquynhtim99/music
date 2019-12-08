/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

var loadingHtml = '<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>';
var msAjaxURL = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime();
var msAllPop = new Array();

/*
 * Tải và hiển thị lời bài hát, sheet nhạc
 * Tại phần
 */
function msLoadLyric(soCode, soTitle, tokend, sheetlink) {
    $('#msAbSoLrtSheetAreaCtn').addClass('hidden');
    $('#detail-song-lrt').removeClass('open');
    $('#detail-song-lrt').html('');
    $('[data-toggle="msSoIframeSheet"]').html('');
    $('[data-toggle="msSoTabLrtSheetItem"]').removeClass('active');
    $('#msAbSoLrtSheetAreaLoader').removeClass('hidden');

    $('#tabctr-ms-detailso-tab-text').hide();
    $('#tabctr-ms-detailso-tab-pdf').hide();
    $('#tabctr-ms-detailso-tab-iframe').hide();

    $('#solrtName').html(soTitle);

    $.ajax({
        type: 'POST',
        url: msAjaxURL,
        dataType: 'json',
        data: {
            'getSongLyric': 1,
            'song_code': soCode,
            'tokend': tokend
        }
    }).done(function(res) {
        $('#msAbSoLrtSheetAreaLoader').addClass('hidden');

        if (res.status == 'SUCCESS' && (res.caption_text != '' || res.caption_file != '')) {
            // Hiển thị tab khi có cả lời bài hát và sheet nhạc
            if (res.caption_text != '' && res.caption_file != '') {
                $('#tabctr-ms-detailso-tab-text').show();
                if (res.caption_file_ext == 'pdf') {
                    $('#tabctr-ms-detailso-tab-pdf').show();
                } else {
                    $('#tabctr-ms-detailso-tab-iframe').show();
                }
                $('#tabctr-ms-detailso-tab-text').addClass('active');
            }

            if (res.caption_text != '') {
                $('#detail-song-lrt').html(res.caption_text);
                $('#ms-detailso-tab-text').addClass('active');
            }
            if (res.caption_file != '') {
                if (res.caption_file_ext == 'pdf') {
                    $('#ms-detailso-tab-pdf').find('.inner-content').html('<iframe class="ms-detailso-iframe-lrt" frameborder="0" scrolling="no" src="' + sheetlink + '"></iframe>');
                 // Hiển thị PDF khi không có lời bài hát
                    if (res.caption_text == '') {
                        $('#ms-detailso-tab-pdf').addClass('active');
                    }
                } else {
                    $('#ms-detailso-tab-iframe').find('.inner-content').html('<iframe class="ms-detailso-iframe-lrt" frameborder="0" scrolling="no" src="' + sheetlink + '"></iframe>');
                    // Hiển thị iframe khi không có lời bài hát
                    if (res.caption_text == '') {
                        $('#ms-detailso-tab-iframe').addClass('active');
                    }
                }
            }

            $('#msAbSoLrtSheetAreaCtn').removeClass('hidden');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown);
        $('#msAbSoLrtSheetAreaLoader').addClass('hidden');
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
    // Xác định giao diện
    var moduleTheme = "default";
    var linkCheck = $('link[href*="music.css"]:first');
    if (linkCheck.length) {
        var check = linkCheck.attr("href").match(/\/themes\/([a-zA-Z0-9\_\-]+)\/css/i);
        if (check) {
            moduleTheme = check[1];
        }
    }

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

    // Xử lý các Popover
    $(document).delegate('div.popover', 'click', function(e) {
        e.stopPropagation();
    });

    $(window).on('click', function() {
        msDestroyAllPop();
    });

    function msDestroyAllPop() {
        $.each(msAllPop, function(k, v) {
            $(v).popover('destroy');
            $(v).data('havepop', false);
        });
        msAllPop = new Array();
    }

    // Nút thêm vào yêu thích, danh sách phát
    $(document).delegate('[data-toggle="mscallpop"]', 'click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!$(this).data('havepop')) {
            msAllPop.push(this);
            $(this).data('havepop', true);
            $(this).popover({
                animation: "",
                container: "body",
                html: true,
                placement: "auto right",
                content: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i></div>',
                trigger: "manual",
                viewport: {
                    selector: 'body',
                    padding: 10
                },
                template: '<div class="popover ms-popover" role="tooltip"><div class="arrow hidden"></div><h3 class="popover-title px-2"></h3><div class="popover-content"></div></div>'
            });

            $(this).on('shown.bs.popover', function(e) {
                var popID = $(this).attr("aria-describedby");
                var btn = $(e.currentTarget);
                var pop = $("#" + popID);

                if (btn.data("mode") == "downloadsong") {
                    // Load HTML download bài hát
                    $.post(msAjaxURL, 'getDownloadSongHtml=1&song_code=' + btn.data("code") + '&tokend=' + btn.data("tokend"), function(res) {
                        $(".popover-content", pop).html(res);
                    });
                } else if (btn.data("mode") == "downloadvideo") {
                    // Load HTML download video
                    $.post(msAjaxURL, 'getDownloadVideoHtml=1&video_code=' + btn.data("code") + '&tokend=' + btn.data("tokend"), function(res) {
                        $(".popover-content", pop).html(res);
                    });
                } else if (btn.data("mode") == "addsongtolist") {
                    // Thêm bài hát vào playlist
                    $.post(msAjaxURL, 'getAddSongToPLHtml=1&song_code=' + btn.data("code") + '&tokend=' + btn.data("tokend") + '&nv_redirect=' + btn.data("redirect"), function(res) {
                        $(".popover-content", pop).html(res);

                        // Tạo thanh cuộn khi nội dung quá dài
                        if ($('.ms-playlist-popover-ctn').length) {
                            $('.ms-playlist-popover-ctn', pop).jScrollPane({
                                animateScroll: true
                            });
                        }

                        // Ấn chữ tạo mới playlist => Mở form tạo
                        $('[data-toggle="creatNew"]', pop).on('click', function(e) {
                            e.preventDefault();
                            $(this).addClass('hidden');
                            $('form', pop).removeClass('hidden');
                        });

                        // Ấn nút tạo playlist => Submit form tạo
                        $('[data-toggle="creatNewSubmit"]', pop).on('click', function(e) {
                            e.preventDefault();
                            $('form', pop).submit();
                        });

                        // Xử lý khi submit form tạo
                        $('form', pop).on('submit', function(e) {
                            e.preventDefault();
                            var btn = $(this).find('[data-toggle="creatNewSubmit"] .load');
                            var $form = $(this);
                            if (btn.is(':visible')) {
                                return;
                            }
                            btn.removeClass('hidden');
                            $.ajax({
                                type: 'POST',
                                url: msAjaxURL,
                                dataType: 'json',
                                data: $form.serialize()
                            }).done(function(res) {
                                btn.addClass('hidden');
                                if (res.status != 'SUCCESS') {
                                    $.gritter.add({
                                        title: "",
                                        text: res.message,
                                        class_name: "color danger"
                                    });
                                    return;
                                }
                                $.gritter.add({
                                    title: "",
                                    text: res.message,
                                    class_name: "color success"
                                });
                                // Đóng popover
                                msDestroyAllPop();
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                btn.addClass('hidden');
                                $.gritter.add({
                                    title: "",
                                    text: MSLANG['unknow_error'],
                                    class_name: "color danger"
                                });
                            });
                        });

                        // Thêm hoặc hủy thêm ở những playlist đã có
                        $('[data-toggle="addOrRemove"]', pop).on('change', function() {
                            $('[data-toggle="addOrRemove"]', pop).prop('disabled', true);
                            $.ajax({
                                type: 'POST',
                                url: msAjaxURL,
                                dataType: 'json',
                                data: {
                                    'togglePlaylistSong': 1,
                                    'song_code': $(this).data('code'),
                                    'tokend': $(this).data('tokend'),
                                    'playlist_code': $(this).data('playlist'),
                                    'is_add': ($(this).is(':checked') ? 1 : 0)
                                }
                            }).done(function(res) {
                                $('[data-toggle="addOrRemove"]', pop).prop('disabled', false);
                                if (res.status != 'SUCCESS') {
                                    $.gritter.add({
                                        title: "",
                                        text: res.message,
                                        class_name: "color danger"
                                    });
                                    return;
                                }
                                $.gritter.add({
                                    title: "",
                                    text: res.message,
                                    class_name: "color success"
                                });
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                $('[data-toggle="addOrRemove"]', pop).prop('disabled', false);
                                $.gritter.add({
                                    title: "",
                                    text: MSLANG['unknow_error'],
                                    class_name: "color danger"
                                });
                            });
                        });
                    });
                }
            });

            $(this).on('hide.bs.popover', function(e) {
                // Hủy khống chế hiện các nút tool tại danh sách bài hát nếu kiểu hiển thị mặc định ẩn
                if ($(e.currentTarget).is(".song-hidden-inline")) {
                    $(e.currentTarget).parents(".ms-main-list-song-action").removeClass("ms-show");
                }
            });

            $(this).popover('show');

            // Khống chế hiện các nút công cụ tại danh sách bài hát khi Popover show
            if ($(this).is(".song-hidden-inline")) {
                $(this).parents(".ms-main-list-song-action").addClass("ms-show");
            }
        } else {
            msDestroyAllPop();
        }
    });

    // Load các file JS
    function loadJS(url, urlnext) {
        $.ajax({
            url: url,
            dataType: "script",
            success: function() {
                $(document).trigger("nv.music.resourceloaded", 1);
                if (typeof urlnext != "undefined") {
                    loadJS(urlnext);
                }
            },
            cache: true
        });
    }

    // Load các file CSS
    function loadCSS(url) {
        var head = document.getElementsByTagName("head")[0]
        var link = document.createElement('link');
        link.rel  = 'stylesheet';
        link.href = url;
        head.appendChild(link);
        link.onload = function() {
            $(document).trigger("nv.music.resourceloaded", 0);
        };
    }

    // Load các thư viện cần thiết
    var numberScriptLoaded = 0;
    var numberCssLoad = 0;

    $(document).on("nv.music.resourceloaded", function(event, type) {
        if (type == 1) {
            numberScriptLoaded++;
        } else {
            numberCssLoad++;
        }
        if (numberScriptLoaded >= 5 && numberCssLoad >= 0) {
            $(document).trigger("nv.music.ready");
        }
    });

    // Load jquery gritter
    if (typeof $.gritter == "undefined") {
        loadJS(nv_base_siteurl + "themes/" + moduleTheme + "/images/music/gritter/js/jquery.gritter.min.js");
    } else {
        $(document).trigger("nv.music.resourceloaded", 1);
    }

    // Load jquery jScrollPane
    if (typeof $.fn.jScrollPane == "undefined") {
        loadJS(nv_base_siteurl + "themes/" + moduleTheme + "/images/music/jscrollpane/jquery.jscrollpane.min.js");
    } else {
        $(document).trigger("nv.music.resourceloaded", 1);
    }

    // Load jquery mousewheel
    if (typeof $.fn.mousewheel == "undefined") {
        loadJS(nv_base_siteurl + "themes/" + moduleTheme + "/images/music/jscrollpane/jquery.mousewheel.js");
    } else {
        $(document).trigger("nv.music.resourceloaded", 1);
    }

    // Load jquery mwheelIntent
    if (typeof $.fn.mwheelIntent == "undefined") {
        loadJS(nv_base_siteurl + "themes/" + moduleTheme + "/images/music/jscrollpane/mwheelIntent.js");
    } else {
        $(document).trigger("nv.music.resourceloaded", 1);
    }

    // Load ngôn ngữ
    loadJS(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=language');
});

// Các thao tác sau khi tài nguyên load xong
$(document).on('nv.music.ready', function() {
    $.extend($.gritter.options, {
        position: "bottom-left"
    });
    // Option: color success, danger, warning, primary, dark

    // Thêm album vào danh sách yêu thích
    $('[data-toggle="favoriteAlbum"]').on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        if ($this.data('reqlogin')) {
            $.gritter.add({
                title: "",
                text: sprintf(MSLANG['favorite_add_album_login'], $this.data('urllogin')),
                class_name: "color primary"
            });
            return;
        }
        $this.data('busy', true);
        $.ajax({
            type: 'POST',
            url: msAjaxURL,
            dataType: 'json',
            data: {
                'updateUserFavoriteAlbum': 1,
                'album_code': $this.data("code"),
                'tokend': $this.data("tokend")
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.status != 'SUCCESS') {
                $.gritter.add({
                    title: "",
                    text: res.message,
                    class_name: "color danger"
                });
                return;
            }
            if (res.favorited) {
                $this.removeClass('btn-default').addClass('btn-success');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_added_album'],
                    class_name: "color success"
                });
            } else {
                $this.removeClass('btn-success').addClass('btn-default');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_removed_album'],
                    class_name: "color success"
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            $.gritter.add({
                title: "",
                text: MSLANG['unknow_error'],
                class_name: "color danger"
            });
        });
    });

    // Thêm video vào danh sách yêu thích
    $('[data-toggle="favoriteVideo"]').on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        if ($this.data('reqlogin')) {
            $.gritter.add({
                title: "",
                text: sprintf(MSLANG['favorite_add_video_login'], $this.data('urllogin')),
                class_name: "color primary"
            });
            return;
        }
        $this.data('busy', true);
        $.ajax({
            type: 'POST',
            url: msAjaxURL,
            dataType: 'json',
            data: {
                'updateUserFavoriteVideo': 1,
                'video_code': $this.data("code"),
                'tokend': $this.data("tokend")
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.status != 'SUCCESS') {
                $.gritter.add({
                    title: "",
                    text: res.message,
                    class_name: "color danger"
                });
                return;
            }
            if (res.favorited) {
                $this.removeClass('btn-default').addClass('btn-success');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_added_video'],
                    class_name: "color success"
                });
            } else {
                $this.removeClass('btn-success').addClass('btn-default');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_removed_video'],
                    class_name: "color success"
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            $.gritter.add({
                title: "",
                text: MSLANG['unknow_error'],
                class_name: "color danger"
            });
        });
    });

    // Thêm bài hát vào danh sách yêu thích
    $('[data-toggle="favoriteSong"]').on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        if ($this.data('reqlogin')) {
            $.gritter.add({
                title: "",
                text: sprintf(MSLANG['favorite_add_song_login'], $this.data('urllogin')),
                class_name: "color primary"
            });
            return;
        }
        $this.data('busy', true);
        $.ajax({
            type: 'POST',
            url: msAjaxURL,
            dataType: 'json',
            data: {
                'updateUserFavoriteSong': 1,
                'song_code': $this.data("code"),
                'tokend': $this.data("tokend")
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.status != 'SUCCESS') {
                $.gritter.add({
                    title: "",
                    text: res.message,
                    class_name: "color danger"
                });
                return;
            }
            if (res.favorited) {
                $this.removeClass('btn-default').addClass('btn-success');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_added_song'],
                    class_name: "color success"
                });
            } else {
                $this.removeClass('btn-success').addClass('btn-default');
                $.gritter.add({
                    title: "",
                    text: MSLANG['favorite_removed_song'],
                    class_name: "color success"
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            $.gritter.add({
                title: "",
                text: MSLANG['unknow_error'],
                class_name: "color danger"
            });
        });
    });

    // Xóa bỏ playlist của thành viên
    $('[data-toggle="delMyPlaylist"]').on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        if (!confirm(MSLANG['mymusic_playlist_cdel'])) {
            return;
        }
        $this.data('busy', true);
        $.ajax({
            type: 'POST',
            url: $this.data('url'),
            dataType: 'json',
            data: {
                'delete': 1,
                'tokend': $this.data("tokend")
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.status != 'SUCCESS') {
                $.gritter.add({
                    title: "",
                    text: res.message,
                    class_name: "color danger"
                });
                return;
            }
            location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            $.gritter.add({
                title: "",
                text: MSLANG['unknow_error'],
                class_name: "color danger"
            });
        });
    });

    /*
     * Thao tác chỉnh sửa các thành phần của playlist
     */
    function cancelEditInlineForm(ele) {
        var input = $(ele);
        var ctn = input.parent().parent();
        $('.ms-form', ctn).hide();
        $('.ms-val', ctn).show();
    }

    function startEditInlineForm(ele) {
        var input = $(ele);
        var ctn = input.parent().parent();
        $('.ms-val', ctn).hide();
        $('.ms-form', ctn).show();
        $('.ms-form [data-toggle="msInlineFormInput"]', ctn).select();
    }

    // Ấn nút sửa các trường dữ liệu của playlist
    $('[data-toggle="editPlaylistField"]').on("click", function(e) {
        e.preventDefault();
        startEditInlineForm(this);
    });

    // Ấn phím tại các ô sửa ở playlist
    $('[data-toggle="msInlineFormInput"]').on('keyup', function(e) {
        if (e.which == 27) {
            // Nút ESC
            cancelEditInlineForm(this);
        }
    });

    // Submit form sửa
    $('[data-toggle="msInlineForm"]').on('submit', function(e) {
        e.preventDefault();
        var $this = $(this);
        var field = $this.data('field');
        var value = trim($('[data-toggle="msInlineFormInput"]', $this).val());
        if ($this.data('busy')) {
            return;
        }
        if (value == '') {
            $.gritter.add({
                title: "",
                text: MSLANG['error_value_empty'],
                class_name: "color danger"
            });
            return false;
        }
        $this.data('busy', true);
        $.ajax({
            type: 'POST',
            url: $this.attr('action'),
            dataType: 'json',
            data: {
                'updateField': 1,
                'tokend': $this.data("tokend"),
                'field': field,
                'value': value
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.status != 'SUCCESS') {
                $.gritter.add({
                    title: "",
                    text: res.message,
                    class_name: "color danger"
                });
                return;
            }
            location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            $.gritter.add({
                title: "",
                text: MSLANG['unknow_error'],
                class_name: "color danger"
            });
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

/*! sprintf-js v1.1.2 | Copyright (c) 2007-present, Alexandru Mărășteanu <hello@alexei.ro> | BSD-3-Clause */
!function(){"use strict";var g={not_string:/[^s]/,not_bool:/[^t]/,not_type:/[^T]/,not_primitive:/[^v]/,number:/[diefg]/,numeric_arg:/[bcdiefguxX]/,json:/[j]/,not_json:/[^j]/,text:/^[^\x25]+/,modulo:/^\x25{2}/,placeholder:/^\x25(?:([1-9]\d*)\$|\(([^)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,key:/^([a-z_][a-z_\d]*)/i,key_access:/^\.([a-z_][a-z_\d]*)/i,index_access:/^\[(\d+)\]/,sign:/^[+-]/};function y(e){return function(e,t){var r,n,i,s,a,o,p,c,l,u=1,f=e.length,d="";for(n=0;n<f;n++) {
    if("string"==typeof e[n]){d+=e[n];}else if("object"==typeof e[n]){if((s=e[n]).keys) {
        for(r=t[u],i=0;i<s.keys.length;i++){if(null==r) {
            throw new Error(y('[sprintf] Cannot access property "%s" of undefined value "%s"',s.keys[i],s.keys[i-1]));
        }r=r[s.keys[i]]}
    } else {r=s.param_no?t[s.param_no]:t[u++];}if(g.not_type.test(s.type)&&g.not_primitive.test(s.type)&&r instanceof Function&&(r=r()),g.numeric_arg.test(s.type)&&"number"!=typeof r&&isNaN(r)) {
        throw new TypeError(y("[sprintf] expecting number but found %T",r));
    }switch(g.number.test(s.type)&&(c=0<=r),s.type){case"b":r=parseInt(r,10).toString(2);break;case"c":r=String.fromCharCode(parseInt(r,10));break;case"d":case"i":r=parseInt(r,10);break;case"j":r=JSON.stringify(r,null,s.width?parseInt(s.width):0);break;case"e":r=s.precision?parseFloat(r).toExponential(s.precision):parseFloat(r).toExponential();break;case"f":r=s.precision?parseFloat(r).toFixed(s.precision):parseFloat(r);break;case"g":r=s.precision?String(Number(r.toPrecision(s.precision))):parseFloat(r);break;case"o":r=(parseInt(r,10)>>>0).toString(8);break;case"s":r=String(r),r=s.precision?r.substring(0,s.precision):r;break;case"t":r=String(!!r),r=s.precision?r.substring(0,s.precision):r;break;case"T":r=Object.prototype.toString.call(r).slice(8,-1).toLowerCase(),r=s.precision?r.substring(0,s.precision):r;break;case"u":r=parseInt(r,10)>>>0;break;case"v":r=r.valueOf(),r=s.precision?r.substring(0,s.precision):r;break;case"x":r=(parseInt(r,10)>>>0).toString(16);break;case"X":r=(parseInt(r,10)>>>0).toString(16).toUpperCase()}g.json.test(s.type)?d+=r:(!g.number.test(s.type)||c&&!s.sign?l="":(l=c?"+":"-",r=r.toString().replace(g.sign,"")),o=s.pad_char?"0"===s.pad_char?"0":s.pad_char.charAt(1):" ",p=s.width-(l+r).length,a=s.width&&0<p?o.repeat(p):"",d+=s.align?l+r+a:"0"===o?l+a+r:a+l+r)}
}return d}(function(e){if(p[e]) {
    return p[e];
}var t,r=e,n=[],i=0;for(;r;){if(null!==(t=g.text.exec(r))){n.push(t[0]);}else if(null!==(t=g.modulo.exec(r))){n.push("%");}else{if(null===(t=g.placeholder.exec(r))) {
    throw new SyntaxError("[sprintf] unexpected placeholder");
}if(t[2]){i|=1;var s=[],a=t[2],o=[];if(null===(o=g.key.exec(a))) {
    throw new SyntaxError("[sprintf] failed to parse named argument key");
}for(s.push(o[1]);""!==(a=a.substring(o[0].length));) {
    if(null!==(o=g.key_access.exec(a))){s.push(o[1]);}else{if(null===(o=g.index_access.exec(a))) {
        throw new SyntaxError("[sprintf] failed to parse named argument key");
    }s.push(o[1])}
}t[2]=s} else {i|=2;}if(3===i) {
    throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");
}n.push({placeholder:t[0],param_no:t[1],keys:t[2],sign:t[3],pad_char:t[4],align:t[5],width:t[6],precision:t[7],type:t[8]})}r=r.substring(t[0].length)}return p[e]=n}(e),arguments)}function e(e,t){return y.apply(null,[e].concat(t||[]))}var p=Object.create(null);"undefined"!=typeof exports&&(exports.sprintf=y,exports.vsprintf=e),"undefined"!=typeof window&&(window.sprintf=y,window.vsprintf=e,"function"==typeof define&&define.amd&&define(function(){return{sprintf:y,vsprintf:e}}))}();
