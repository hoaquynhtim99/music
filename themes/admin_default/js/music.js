/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

+function($) {
    'use strict';

    var Validate = function(element, options) {
        var self    = this,
            editor  = false;

        this.$element = $(element);
        this.options = options;

        // Không sử dụng validate của trình duyệt
        this.$element.attr('novalidate', true);

        this.rebuildForm();

        this.$element.on('submit.bs.validate', $.proxy(this.onSubmit, this));

        // Điều khiển ẩn các thông báo lỗi
        this.$element.find("div.required,div.checkbox,div.radio,div.ckeditor,input:not(:button,:submit,:reset),select,textarea").each(function() {
            var element   = this,
                tagName   = $(this).prop('tagName'),
                name;

            if (tagName == 'DIV') {
                if ($(element).is('.ckeditor')) {
                    // Ẩn khi trình soạn thảo có thay đổi
                    if (typeof CKEDITOR == 'object' && (name = $(element).find('textarea:first').prop('id')) && CKEDITOR.instances[name]) {
                        CKEDITOR.instances[name].on('change', function() {
                            self.hideError(element);
                        });
                    }
                } else {
                    // Ấn khi nhấp vào DIV
                    $(element).on('click.bs.validate', function(e) {
                        self.hideError(element);
                    });
                }
            } else if (tagName == 'SELECT') {
                // Ẩn khi select thay đổi
                $(element).on('click.bs.validate change.bs.validate', function(e) {
                    if (e.which != 13) {
                        self.hideError(element);
                    }
                });
            } else {
                // Ẩn khi ấn phím xuống ở input, textarea
                $(element).on('keydown.bs.validate', function(e) {
                    if (e.which != 13) {
                        self.hideError(element);
                    }
                });
            }
        });
    }

    Validate.VERSION  = '4.3.00';

    Validate.DEFAULTS = {
        type   : 'normal'      // normal|ajax|file
    };

    Validate.MAIL_FILTER = /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/;

    /**
     * Submit form
     */
    Validate.prototype.onSubmit = function(e) {
        if (!this.validate()) {
            e.preventDefault();
            return false;
        }

        this.updateElement();

        if (this.options.type == 'ajax') {
            // Submit form bằng Ajax
            e.preventDefault();
            this.submitAjax();
        } else if (this.options.type == 'file') {
            // Submit form có chứa file upload
            this.submitFile();
        }

        return true;
    }

    /**
     * Build lại form nếu không đúng chuẩn
     * Trong trường hợp này xem như form phải đúng chuẩn rồi
     */
    Validate.prototype.rebuildForm = function() {
        var html = '';
        if (!$('.form-element', this.$element).length || !$('.form-result', this.$element).length) {
            this.$element.find('.form-group').each(function() {
                html += $(this).context.outerHTML;
            });
            this.$element.html('<div class="form-result"></div><div class="form-element">' + html + '</div>');
        }
    }

    /**
     * Kiểm tra các ô nhập liệu
     */
    Validate.prototype.validate = function() {
        var self = this;
        var error = 0;

        // Duyệt các thành phần bắt buộc để kiểm tra
        this.$element.find(".required").each(function() {
            if ("password" == $(this).prop("type")) {
                $(this).val(self.trim(self.stripTags($(this).val())));
            }

            if (!self.check(this)) {
                error ++;
                if (typeof $(this).data('mess') != 'undefined' && $(this).data('mess') != '') {
                    $(this).attr("data-current-mess", $(this).data('mess')).data('current-mess', $(this).data('mess'));
                }
                self.showError(this, error);

                return;
            } else {
                self.hideError(this);
            }
        })

        return error ? false : true;
    }

    /**
     * Ẩn toàn bộ các thông báo lỗi
     */
    Validate.prototype.hideAllError = function() {
        $(".has-error", this.$element).removeClass("has-error");
        $(".required", this.$element).tooltip("destroy");
    }

    /**
     * Ẩn thông báo lỗi ở một đối tượng
     */
    Validate.prototype.hideError = function(element) {
        $(element).tooltip("destroy");

        if ($(element).parent().is('.input-group')) {
            $(element).parent().parent().parent().removeClass("has-error");
        } else {
            $(element).parent().parent().removeClass("has-error");
        }
    }

    /**
     * Hiển thị thông báo lỗi ở một đối tượng
     * order: Lỗi xuất hiện thứ tự 1, 2, 3, 4...
     */
    Validate.prototype.showError = function(element, order) {
        var name;

        if ($(element).parent().is('.input-group')) {
            $(element).parent().parent().parent().addClass("has-error");
        } else {
            $(element).parent().parent().addClass("has-error");
        }

        $(element).tooltip({
            placement: "bottom",
            title: function() {
                return (typeof $(this).data('current-mess') != 'undefined' && $(element).data('current-mess') != '') ? $(element).data('current-mess') : ('undefined' == typeof nv_required ? 'This field is required!' : nv_required);
            },
            trigger: 'manual'
        });

        $(element).tooltip("show");

        // Focus, chuyển màn hình vào cái lỗi đầu tiên trong danh sách
        if (order == 1) {
            if ($(element).prop("tagName") == 'DIV') {
                if ($(element).is('.ckeditor')) {
                    if (typeof CKEDITOR == 'object' && (name = $(element).find('textarea:first').prop('id')) && CKEDITOR.instances[name]) {
                        CKEDITOR.instances[name].focus();
                    }
                } else if ($(element).is('.select2') || $(element).is('.hiddeninputlist')) {
                    // Select 2 thì chuyển màn hình về tại vị trí của nó
                    $(window).scrollTop($(element).offset().top);
                } else {
                    // Focus vào input đầu tiên nếu không select2
                    // Vì select2 tự mở gây rắc rối
                    if ($("input", element).length) {
                        $("input", element)[0].focus();
                    }
                }
            } else {
                $(element).focus();
            }
        }
    }

    /**
     * Kiểm tra các phần tử bắt buộc có ok không
     */
    Validate.prototype.check = function(element) {
        var pattern = $(element).data('pattern'),
            value   = $(element).val(),
            tagName = $(element).prop('tagName'),
            type    = $(element).prop('type'),
            name, text;

        if ("INPUT" == tagName && "email" == type) {
            // Kiểm tra email
            if (!Validate.MAIL_FILTER.test(value)) {
                return false;
            }
        } else if ("SELECT" == tagName) {
            // Kiểm tra select đơn có chọn hay không
            if (!$("option:selected", element).length) {
                return false;
            }
        } else if ("DIV" == tagName && $(element).is(".radio")) {
            // Kiểm tra radio có chọn không
            if (!$("[type=radio]:checked", element).length) {
                return false;
            }
        } else if ("DIV" == tagName && $(element).is(".checkbox")) {
            // Kiểm tra checkbox có chọn không
            if (!$("[type=checkbox]:checked", element).length) {
                return false;
            }
        } else if ("DIV" == tagName && $(element).is(".ckeditor")) {
            // Kiểm tra trình soạn thảo CKeditor có nội dung không
            if (typeof CKEDITOR == 'object' && (name = $(element).find('textarea:first').prop('id')) && CKEDITOR.instances[name]) {
                text = CKEDITOR.instances[name].getData();
                text = this.trim(text);
                if (text != '') {
                    return true;
                }
            }
            return false;
        } else if ("INPUT" == tagName || "TEXTAREA" == tagName) {
            // Kiểm tra input, textexare thường
            if ("undefined" == typeof pattern || "" == pattern) {
                if ("" == value) {
                    return false;
                }
            } else if (!(new RegExp(pattern)).test(value)) {
                return false;
            }
        } else if ("DIV" == tagName && $(element).is(".select2")) {
            // Kiểm tra select
            if (!$("option:selected", element).length) {
                return false;
            }
            return true;
        } else if ("DIV" == tagName && $(element).is(".hiddeninputlist")) {
            // Kiểm tra các hidden input trong một khu vực chỉ định
            if (!$('input[type="hidden"]', element).length) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * Submit form dạng ajax
     */
    Validate.prototype.submitAjax = function() {
        var action  = this.$element.prop('action'),
            method  = this.$element.prop('method'),
            data    = this.$element.serialize(),
            self    = this;

        if (typeof action == 'undefined') {
            throw new Error('Missing action attitude for submit form')
        }
        if (typeof method == 'undefined') {
            throw new Error('Missing method attitude for submit form')
        }

        if (action == '') {
            action = window.location.href.replace(/#(.*)/, "");
        }

        this.hideAllError();
        this.$element.find("input,button,select,textarea").prop("disabled", true);

        $.ajax({
            type: method,
            cache: false,
            url: action,
            data: data,
            dataType: 'json',
            success: function(res) {
                var $this, type;

                self.$element.find("input,button,select,textarea").prop("disabled", false);

                // Dữ liệu trả về lỗi
                if ((res.status != 'error' && res.status != 'ok') || typeof res.message != 'string' || typeof res.input != 'string') {
                    throw new Error('Response data is invalid!!');
                }

                // Lỗi trả về
                if (res.status == 'error') {
                    if (res.input != '' && $('[name="' + res.input + '"]', self.$element).length) {
                        // Hiển thị thông báo lỗi ở các input
                        $this = $('[name="' + res.input + '"]:first', self.$element);
                        type = $this.prop('type');

                        if (type == 'checkbox' || type == 'radio' || $this.parent().parent().is('.ckeditor')) {
                            $this = $this.parent().parent();
                        }

                        $this.attr("data-current-mess", res.message).data('current-mess', res.message);
                        self.showError($this, 1);
                    } else {
                        // Hiển thị thông báo lỗi lên đầu
                        $('.form-result', self.$element).html('<div class="alert alert-danger">' + res.message + '</div>').show();
                        $("html, body").animate({ scrollTop: $('.form-result', self.$element).offset().top }, 200);
                    }

                    return false;
                }

                /**
                 * Thành công
                 * Chuyển hướng ngay
                 */
                if (typeof res.redirect == 'string' && res.redirect != '' && res.redirectnow === true) {
                    window.location.href = res.redirect;
                    return true;
                }

                // Thông báo và chuyển hướng
                $('.form-result', self.$element).html('<div class="alert alert-success">' + res.message + '</div>').show();

                $("html, body").animate({scrollTop: $('.form-result', self.$element).offset().top}, 200, function() {
                    setTimeout(function() {
                        $('.form-element', self.$element).slideUp(200);
                    }, 200);

                    setTimeout(function() {
                        window.location.href = ( typeof res.redirect == 'string' && res.redirect != '' ) ? res.redirect : window.location.href
                    }, 4000);
                });
            }
        });
    }

    Validate.prototype.updateElement = function() {
        var name;

        if (typeof CKEDITOR == 'object') {
            $('.ckeditor' , this.$element).each(function() {
                if ((name = $(this).find('textarea:first').prop('id')) && CKEDITOR.instances[name]) {
                    CKEDITOR.instances[name].updateElement();
                }
            })
        }
    }

    Validate.prototype.stripTags = function(str, allowed_tags) {
        var key = '', allowed = false;
        var matches = [];
        var allowed_array = [];
        var allowed_tag = '';
        var i = 0;
        var k = '';
        var html = '';

        var replacer = function(search, replace, str) {
            return str.split(search).join(replace);
        };
        // Build allowes tags associative array
        if (allowed_tags) {
            allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
        }

        str += '';

        // Match tags
        matches = str.match(/(<\/?[\S][^>]*>)/gi);

        // Go through all HTML tags
        for (key in matches) {
            if (isNaN(key)) {
                // IE7 Hack
                continue;
            }

            // Save HTML tag
            html = matches[key].toString();

            // Is tag not in allowed list ? Remove from str !
            allowed = false;

            // Go through all allowed tags
            for (k in allowed_array) {
                // Init
                allowed_tag = allowed_array[k];
                i = -1;

                if (i != 0) {
                    i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
                }
                if (i != 0) {
                    i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
                }
                if (i != 0) {
                    i = html.toLowerCase().indexOf('</' + allowed_tag);
                }

                // Determine
                if (i == 0) {
                    allowed = true;
                    break;
                }
            }

            if (!allowed) {
                str = replacer(html, "", str);
                // Custom replace. No regexing
            }
        }

        return str;
    }

    Validate.prototype.trim = function(str, charlist) {
        var whitespace, l = 0, i = 0;
        str += '';

        if (!charlist) {
            whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
        } else {
            charlist += '';
            whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
        }

        l = str.length;
        for ( i = 0; i < l; i++) {
            if (whitespace.indexOf(str.charAt(i)) === -1) {
                str = str.substring(i);
                break;
            }
        }

        l = str.length;
        for ( i = l - 1; i >= 0; i--) {
            if (whitespace.indexOf(str.charAt(i)) === -1) {
                str = str.substring(0, i + 1);
                break;
            }
        }

        return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
    }

    function Plugin(option) {
        return this.each(function() {
            var $this   = $(this);
            var options = $.extend({}, Validate.DEFAULTS, $this.data(), typeof option == 'object' && option);
            var data    = $this.data('bs.validate');

            if (!data && option == 'destroy') {
                return true;
            }
            if (!data) {
                $this.data('bs.validate', (data = new Validate(this, options)));
            }
            if (typeof option == 'string') {
                data[option]();
            }
        });
    }

    var old = $.fn.validate;

    $.fn.validate = Plugin;
    $.fn.validate.Constructor = Validate;

    // VALIDATE NO CONFLICT
    // =================
    $.fn.validate.noConflict = function() {
        $.fn.validate = old;
        return this;
    }

    // VALIDATE DATA-API
    // ==============
    $(window).on('load', function() {
        $('form[data-toggle="validate"]').each(function() {
            var $form = $(this);
            Plugin.call($form, $form.data());
        });
    });
}(jQuery);

function number_format(number, decimals, dec_point, thousands_point) {
    if (number == null || !isFinite(number)) {
        throw new TypeError("number is not valid");
    }

    if (!decimals) {
        var len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    if (!dec_point) {
        dec_point = '.';
    }

    if (!thousands_point) {
        thousands_point = ',';
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace(".", dec_point);

    var splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
}

function msEllipsisSet() {
    if ($(window).width() < 992) {
        return;
    }
    $('[data-toggle="ellipsis"]').each(function() {
        var $this = $(this);
        (msIsDebug && console.log($this.width()));
        if ($this.width() > widthEllipsis) {
            widthEllipsis = $this.width();
        }
    });
    if (widthEllipsis) {
        (msIsDebug && console.log('Setto: ' + widthEllipsis));
        $('[data-toggle="ellipsis"]').css({
            width: widthEllipsis
        });
        $('[data-toggle="ellipsis"]').find('[data-toggle="items"]').addClass('ms-ellipsis');
    }
}

function msEllipsisCheck(reset = false) {
    (msIsDebug && console.log('msEllipsisCheck'));
    if (reset) {
        widthEllipsis = 0;
        (msIsDebug && console.log('Reset'));
        $('[data-toggle="ellipsis"]').find('[data-toggle="items"]').removeClass('ms-ellipsis');
        $('[data-toggle="ellipsis"]').css({
            width: '100%'
        });
        if (timerEllipsis) {
            clearTimeout(timerEllipsis);
        }
        timerEllipsis = setTimeout(msEllipsisSet(), 100);
    } else {
        msEllipsisSet();
    }
}

var msResizeHandder = function() {
    msEllipsisCheck(true);
}

var widthEllipsis = 0;
var timerEllipsis = null;
var msAllPop = new Array();
var msIsDebug = true;
var msTimerResize = null;

window.chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(35, 138, 230)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};

$(document).ready(function() {
    // Config
    $(document).delegate('[data-toggle="mscfgmainweight"]', 'change', function() {
        var $this = $(this);
        var setVar = $this.val();
        var oldVar = $this.data('value');
        $('[data-toggle="mscfgmainweight"]').each(function() {
            if ($(this).data('value') == setVar) {
                $(this).find('option').prop('selected', false);
                $('option[value="' + oldVar + '"]', $(this)).prop('selected', true);
                $(this).data('value', oldVar);
            }
        });
        $this.data('value', setVar);
    });

    // Xóa dữ liệu input
    $('[data-toggle="msclearval"]').click(function(e) {
        e.preventDefault();
        $($(this).data('target')).val('');
    });

    // Xem ảnh
    $('[data-toggle="msviewimg"]').click(function(e) {
        e.preventDefault();
        var title = $(this).data('title');
        if (typeof title == "undefined") {
            title = 'Image';
        }
        var src = $($(this).data('target')).val();
        if (typeof src == "undefined" || src == '' || !src) {
            return false;
        }
        modalShow($(this).data('title'), '<img src="' + src + '" class="img-responsive"/>');
    });

    // Duyệt file trên hệ thống
    $('[data-toggle="msbrserver"]').click(function(e) {
        e.preventDefault();
        var area = alt = path = currentpath = "";
        var type = "image";
        if (typeof $(this).data('area') != "undefined") {
            area = $(this).data('area');
        }
        if (typeof $(this).data('alt') != "undefined") {
            alt = $(this).data('alt');
        }
        if (typeof $(this).data('path') != "undefined") {
            path = $(this).data('path');
        }
        if (typeof $(this).data('currentpath') != "undefined") {
            currentpath = $(this).data('currentpath');
        }
        if (typeof $(this).data('type') != "undefined") {
            type = $(this).data('type');
        }
        nv_open_browse(script_name + "?" + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    // Scroll to div
    $('[data-toggle="msscrollto"]').click(function(e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 250);
    });

    // Điều khiển các popover
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
                placement: "bottom",
                content: msGetPopoverContent(this),
                trigger: "manual"
            });
            $(this).on('shown.bs.popover', function() {
                // Cuộn tới và đánh dấu current
                var $this = $(this);
                var ctn = $('#' + $this.attr('aria-describedby'));
                var wrapArea = ctn.find('.ms-dropdown-tool-ctn');
                var wrapContent = ctn.find('.ms-dropdown-tool');
                wrapContent.find('[data-value="' + $this.data('value') + '"]').addClass('active');
                if (wrapArea.height() < wrapContent.height()) {
                    var item = wrapContent.find('li:first');
                    var scrollTop = ($this.data('value') - 1) * item.height();
                    wrapArea.scrollTop(scrollTop);
                }
                // Ghi lại một số data
                ctn.data('btn', $this);
            });
            $(this).popover('show');
        } else {
            msDestroyAllPop();
        }
    });

    function msDestroyAllPop() {
        $.each(msAllPop, function(k, v) {
            $(v).popover('destroy');
            $(v).data('havepop', false);
        });
        msAllPop = new Array();
    }

    $(document).delegate('div.popover', 'click', function(e) {
        e.stopPropagation();
    });

    $(window).on('click', function() {
        msDestroyAllPop();
    });

    /**
     * Quản lý các tác vụ bằng popup
     */
    var popupModal = $('#formmodal');
    var popupForm = $('#formmodalctn');
    var popupFubmitNext = $('#formmodalsaveandcon');
    var popupFubmitBack = $('#formmodalsaveandback');
    var msToggleActive = $('[data-toggle="msactive"]');

    $(document).delegate('.ms-dropdown-tool a', 'click', function(e) {
        e.preventDefault();
        msDestroyAllPop();
        var $this = $(this);
        var ctn = $this.parent().parent();
        var btn = ctn.parent().parent().parent().data('btn');
        var btnText = btn.find('span.text');
        var submitID;

        var ajaction = '';
        if (btn.data('type') == 'weight') {
            ajaction = 'weight';
        } else {
            ajaction = $this.data('value');
        }

        // Xử lý ID hoặc IDs
        if (btn.data('type') == 'actions') {
            submitID = new Array();
            $($(btn.data('target'))).each(function(k, v) {
                if ($(this).is(':checked')) {
                    submitID.push($(this).val());
                }
            });
            if (submitID.length < 1) {
                alert(btn.data('msg'));
                return false;
            }
            submitID = submitID.join(',');
        } else {
            submitID = btn.data('id');
        }

        if (ajaction == 'delete' && !confirm($this.data('others') ? $this.data('others') : nv_is_del_confirm[0])) {
            return false;
        } else if (ajaction == 'edit' || ajaction == 'linkcc') {
            (msIsDebug && console.log('Link to ' + ajaction));
            window.location = btn.data('url' + ajaction);
            return false;
        }

        btn.prop('disabled', true);
        btnText.html('<i class="fa fa-spinner fa-spin fa-fw"></i>' + $this.text());

        // Xử lý dữ liệu
        $.ajax({
            cache: false,
            dataType: 'json',
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + btn.data('op') + '&nocache=' + new Date().getTime(),
            data: 'ajaction=' + ajaction + '&id=' + submitID + '&value=' + $this.data('value')
        }).done(function(res) {
            if (res.status == 'ok') {
                if (ajaction == 'ajedit') {
                    btnText.find('i').remove();
                    btnText.html(btnText.data('text'));
                    btn.prop('disabled', false);

                    var e_title = popupModal.find('h4.modal-title .tit');
                    var e_msg = popupModal.find('.alert');

                    e_title.html(e_title.data('msgedit') + ' ' + btn.data('name'));
                    e_msg.removeClass('alert-danger').removeClass('alert-success').addClass('alert-info').html(e_msg.data('msgedit'));

                    $.each(res.data, function(k, v) {
                        $('[name="' + k + '"]', popupForm).val(v);
                    });
                    if (res.datacheckbox) {
                        $.each(res.datacheckbox, function(k, v) {
                            $('[name="' + k + '"]', popupForm).prop('checked', v);
                        });
                    }
                    if (res.datacheckboxid) {
                        $.each(res.datacheckboxid, function(k, v) {
                            $('#' + k, popupForm).prop('checked', v);
                        });
                    }
                    $('[name="id"]', popupForm).val(btn.data('id'));
                    popupForm.find('[name="submittype"]').val('back');
                    popupForm.find('[name="submittype"]').trigger('change');
                    popupFubmitNext.hide();
                    popupModal.modal('show');
                    return true;
                }
                location.reload();
            } else {
                alert(res.message);
                btnText.find('i').remove();
                btnText.html(btnText.data('text'));
                btn.prop('disabled', false);
            }
        }).fail(function() {
            alert("Error request!!!");
            btnText.find('i').remove();
            btnText.html(btnText.data('text'));
            btn.prop('disabled', false);
        }).always(function() {
            // Not things
        });
    });

    // Load lại trang khi đóng cái form popup
    popupModal.on('hide.bs.modal', function() {
        if ($(this).data('changed') == true) {
            location.reload();
        }
    });
    $('input', popupModal).change(function() {
        popupModal.data('changed', true);
    });

    // Nút thêm nội dung bằng popup
    $('[data-toggle="trigerformmodal"]').click(function(e) {
        e.preventDefault();
        popupResetForm();
        popupModal.modal('show');
    });
    // Submit form popup
    popupForm.submit(function(e) {
        e.preventDefault();
        if ($(this).data('busy') == true) {
            return;
        }
        popupFubmitNext.find('.fa').removeClass('fa-angle-double-right').addClass('fa-spinner').addClass('fa-spin');
        popupFubmitBack.find('.fa').removeClass('fa-floppy-o').addClass('fa-spinner').addClass('fa-spin');
        $(this).data('busy', true);

        var data = $(this).serialize();
        var url = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + popupForm.data('op') + '&ajaxrequest=1&nocache=' + new Date().getTime();

        $(this).find('input').prop('disabled', true);

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            dataType: 'json',
            cache: false
        }).done(function(data) {
            popupPostResult(data);
        }).fail(function(data) {
            popupPostResult({
                status: 'error',
                message: 'System error! Please try again!'
            });
        });
    });
    popupFubmitNext.click(function(e) {
        e.preventDefault();
        popupForm.find('[name="submittype"]').val('continue');
        popupForm.submit();
    });
    popupFubmitBack.click(function(e) {
        e.preventDefault();
        popupForm.find('[name="submittype"]').val('back');
        popupForm.submit();
    });
    function popupResetForm() {
        var e_title = popupModal.find('h4.modal-title .tit');
        var e_msg = popupModal.find('.alert');

        e_title.html(e_title.data('msgadd'));
        e_msg.removeClass('alert-danger').removeClass('alert-success').addClass('alert-info').html(e_msg.data('msgadd'));

        popupForm.find('input[type="text"]').val('');

        var eleCheck = popupForm.find('input[type="checkbox"]');
        eleCheck.each(function() {
            $(this).prop('checked', $(this).data('checked') == 1 ? true : false);
        });
        $('[name="id"]', popupForm).val('0');
        popupFubmitNext.show();
    }
    function popupPostResult(data) {
        popupForm.find('input').prop('disabled', false);
        popupForm.data('busy', false);
        popupFubmitNext.find('.fa').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-angle-double-right');
        popupFubmitBack.find('.fa').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-floppy-o');
        $(this).data('busy', true);
        if (data.status == 'ok') {
            if (data.mode == 'continue') {
                popupResetForm();
            } else {
                popupModal.modal('hide');
            }
        } else {
            popupModal.find('.alert').removeClass('alert-info').removeClass('alert-success').addClass('alert-danger').html(data.message);
        }
    }
    /**
     * Toggle trạng thái đối tượng 0:1 ví dụ đình chỉ/kích hoạt.
     * Mặc định là active:deactive hoặc cái gì đó tùy thiết lập
     */
    msToggleActive.click(function(e) {
        //e.preventDefault();
        var $this = $(this);
        if ($this.is(':disabled')) {
            (msIsDebug && console.log('busy'));
            return false;
        }
        var active = $this.is(':checked');
        (msIsDebug && console.log('Set to ' + active));
        msToggleActive.prop('disabled', true);
        var url = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + $this.data('op') + '&ajaxrequest=1&nocache=' + new Date().getTime();
        var ajaction = ['deactive', 'active'];
        if ($this.data('action')) {
            var _action = $this.data('action').split('|');
            if (_action[1]) {
                ajaction = [_action[0], _action[1]];
            }
        }
        var data = {
            'ajaction': (active ? ajaction[1] : ajaction[0]),
            'id': $this.data('id')
        };
        $.ajax({
            method: "POST",
            url: url,
            data: data,
            dataType: 'json',
            cache: false
        }).done(function(data) {
            msToggleActiveRes(data);
        }).fail(function(data) {
            msToggleActiveRes({
                status: 'error',
                message: 'System error! Please try again!'
            });
        });
    });
    function msToggleActiveRes(data) {
        msToggleActive.prop('disabled', false);
        if (data.status != 'ok') {
            alert(data.message);
        }
    }
    // Hiển thị list ca sĩ
    $('[data-toggle="show-va-singer"]').click(function(e) {
        e.preventDefault();
        modalShow($($(this).data('target')).attr('title'), $($(this).data('target')).html());
    });
    msEllipsisCheck();

    // Duyệt ảnh, file trên server
    $('[data-toggle="browse"]').click(function() {
        var area = $(this).data('area');
        var path = $(this).data('path');
        var currentpath = $(this).data('currentpath');
        var type = $(this).data('type');
        var currentfile = $('#' + area).val();
        nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath + "&currentfile=" + currentfile, "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });

    /*
     * Modal chọn ca sĩ, nhạc sĩ cho bài hát, video, album
     */

    // Hàm build list sắp xếp các nghệ sĩ đã chọn khi mới mở popup và khi ấn nút chọn từ danh sách tìm kiếm
    function buildSortableArtistLists(id, title) {
        var html = '';
        html += '<li>';
        html += '<input type="hidden" name="ids[]" data-title="' + title + '" value="' + id + '">';
        html += '<div class="ctn">';
        html += '<div class="sicon pull-left"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
        html += '<div class="sdel pull-right"><a href="#" data-toggle="delPickArtist"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>';
        html += '<div class="sval"><strong>' + title + '</strong></div>';
        html += '</div>';
        html += '</li>';
        return html;
    }

    var btnTriggerPickArtists = $('[data-toggle="modalPickArtists"]');
    var modalPickArtists = $("#modalPickArtists");

    btnTriggerPickArtists.on("click", function(e) {
        e.preventDefault();
        modalPickArtists.data("list", $(this).data("list"));
        modalPickArtists.data("inputname", $(this).data("inputname"));
        modalPickArtists.find("h4.modal-title").html($(this).data("title"));
        modalPickArtists.find('[name="q"]').val("");
        modalPickArtists.find('[name="mode"]').val($(this).data("mode"));
        modalPickArtists.find('[name="nation_id"] option').prop("selected", false);
        modalPickArtists.modal("show");
    });

    // Ấn nút tìm kiếm ca sĩ, nhạc sĩ
    $('[name="submit"]', modalPickArtists).on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        var loader = $this.find(".load");
        var itemsCtn = $('.item-lists', modalPickArtists);
        if (loader.is(":visible")) {
            return;
        }
        loader.removeClass("hidden");
        itemsCtn.html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i></div>');
        var page = ($this.data("allowedpage") ? $('[name="page"]', modalPickArtists).val() : 1);
        $this.data("allowedpage", false);
        $.ajax({
            method: "POST",
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax-search-artists&nocache=' + new Date().getTime(),
            data: {
                mode: $('[name="mode"]', modalPickArtists).val(),
                q: $('[name="q"]', modalPickArtists).val(),
                nation_id: $('[name="nation_id"]', modalPickArtists).val(),
                artist_id_selected: $('[name="artist_id_selected"]', modalPickArtists).val(),
                submit: 1,
                page: page
            }
        }).done(function(data) {
            itemsCtn.html(data);
            loader.addClass("hidden");
        }).fail(function(data) {
            itemsCtn.html("Error!");
            loader.addClass("hidden");
        });
    });

    // Ấn tìm kiếm khi ấn enter ở ô tìm
    $('[name="q"]', modalPickArtists).on("keyup", function(e) {
        if (e.which == 13) {
            $('[name="submit"]', modalPickArtists).trigger("click");
        }
    });

    // Xử lý dữ liệu khi bắt đầu mở modal lên
    modalPickArtists.on('show.bs.modal', function() {
        $('[name="artist_id_selected"]', modalPickArtists).val("");
        $('[name="page"]', modalPickArtists).val("1");

        // Build lại danh sách nghệ sĩ đã chọn
        var list = $(modalPickArtists.data("list"));
        var ids = new Array();

        $("li", list).each(function() {
            var id = $(this).find("input").val();
            var title = $(this).find(".val").html();
            var html = buildSortableArtistLists(id, title);
            ids.push(id);
            $(".item-selected", modalPickArtists).append(html);
        });

        $('[name="artist_id_selected"]', modalPickArtists).val(ids.join(","));
    });

    // Trigger trình sort khi mở modal lên hoàn thành
    modalPickArtists.on('shown.bs.modal', function() {
        // Build lại trình sort
        $(".item-selected", modalPickArtists).sortable();
        $(".item-selected", modalPickArtists).disableSelection();
    });

    // Xử lý dữ liệu khi đóng modal lại
    modalPickArtists.on('hide.bs.modal', function() {
        $(".item-selected", modalPickArtists).sortable("destroy");
        $(".item-selected", modalPickArtists).html("");
        $(".item-lists", modalPickArtists).html("");
    });

    // Chọn một nghệ sĩ
    modalPickArtists.delegate('[data-toggle="selPickArtist"]', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data("selected")) {
            return;
        }
        $this.html($this.data("selected-mess"));
        $this.data("selected", true);

        $(".item-selected", modalPickArtists).sortable("destroy");

        var html = buildSortableArtistLists($this.data("id"), $this.data("title"));

        $(".item-selected", modalPickArtists).append(html);
        $(".item-selected", modalPickArtists).sortable();
        $(".item-selected", modalPickArtists).disableSelection();
    });

    // Xóa nghệ sĩ đã chọn (trong modal)
    modalPickArtists.delegate('[data-toggle="delPickArtist"]', "click", function(e) {
        e.preventDefault();

        $(".item-selected", modalPickArtists).sortable("destroy");
        $(this).parent().parent().parent().remove();
        $(".item-selected", modalPickArtists).sortable();
        $(".item-selected", modalPickArtists).disableSelection();

        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickArtists).each(function() {
            ids.push($(this).val());
        });
        $('[name="artist_id_selected"]', modalPickArtists).val(ids.join(","));

        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickArtists).data("allowedpage", true);
        $('[name="submit"]', modalPickArtists).trigger("click");
    });

    // Xóa nghệ sĩ đã chọn (ngoài list)
    $(document).delegate('[data-toggle="delPickedArtist"]', "click", function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    // CLick phân trang trong list nghệ sĩ được tìm thấy
    modalPickArtists.delegate('.item-lists .pagination a', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.attr("href") == "" || $this.attr("href") == "#") {
            return;
        }
        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickArtists).each(function() {
            ids.push($(this).val());
        });
        $('[name="artist_id_selected"]', modalPickArtists).val(ids.join(","));
        // Xác định trang của nút chọn
        var page = 1;
        var matches_array = $this.attr("href").match(/page\=(\d+)/i);
        if (matches_array && matches_array.length > 1) {
            page = matches_array[1];
        }
        $('[name="page"]', modalPickArtists).val(page);
        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickArtists).data("allowedpage", true);
        $('[name="submit"]', modalPickArtists).trigger("click");
    });

    // Xác nhận các nghệ sĩ đã chọn
    $('[data-toggle="completePickArtist"]').on("click", function(e) {
        e.preventDefault();

        // Lấy lại các list id đã chọn
        var html = '';
        $('[name="ids[]"]', modalPickArtists).each(function() {
            html += '<li>';
            html += '<input type="hidden" name="' + modalPickArtists.data("inputname") +'" value="' + $(this).val() + '">';
            html += '<a class="delitem" href="#" data-toggle="delPickedArtist"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><strong class="val ms-ellipsis">' + $(this).data("title") + '</strong>';
            html += '</li>';
        });

        var list = $(modalPickArtists.data("list"));
        list.html(html);

        // Ẩn modal đi
        modalPickArtists.modal("hide");
    });

    /*
     * Modal chọn một, nhiều bài hát
     */

    // Hàm build list sắp xếp các bài hát đã chọn khi mới mở popup và khi ấn nút chọn từ danh sách tìm kiếm
    function buildSortableSongLists(id, title, des) {
        var html = '';
        html += '<li>';
        html += '<input type="hidden" name="ids[]" data-title="' + title + '" data-des="' + des + '" value="' + id + '">';
        html += '<div class="ctn">';
        html += '<div class="sicon pull-left"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
        html += '<div class="sdel pull-right"><a href="#" data-toggle="delPickSong"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>';
        html += '<div class="sval"><strong class="ms-ellipsis">' + title + '</strong></div>';
        html += '<div class="sdes"><small class="ms-ellipsis">' + des + '</small></div>';
        html += '</div>';
        html += '</li>';
        return html;
    }

    var btnTriggerPickSongs = $('[data-toggle="modalPickSongs"]');
    var modalPickSongs = $("#modalPickSongs");

    btnTriggerPickSongs.on("click", function(e) {
        e.preventDefault();
        modalPickSongs.data("list", $(this).data("list"));
        modalPickSongs.data("inputname", $(this).data("inputname"));
        modalPickSongs.data("multiple", $(this).data("multiple"));
        modalPickSongs.find("h4.modal-title").html($(this).data("title"));
        modalPickSongs.find('[name="q"]').val("");
        modalPickSongs.find('[name="cat_id"] option').prop("selected", false);
        modalPickSongs.modal("show");
    });

    // Ấn nút tìm kiếm bài hát
    $('[name="submit"]', modalPickSongs).on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        var loader = $this.find(".load");
        var itemsCtn = $('.item-lists', modalPickSongs);
        if (loader.is(":visible")) {
            return;
        }
        loader.removeClass("hidden");
        itemsCtn.html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i></div>');
        var page = ($this.data("allowedpage") ? $('[name="page"]', modalPickSongs).val() : 1);
        $this.data("allowedpage", false);
        $.ajax({
            method: "POST",
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax-search-songs&nocache=' + new Date().getTime(),
            data: {
                q: $('[name="q"]', modalPickSongs).val(),
                cat_id: $('[name="cat_id"]', modalPickSongs).val(),
                song_id_selected: $('[name="song_id_selected"]', modalPickSongs).val(),
                submit: 1,
                page: page
            }
        }).done(function(data) {
            itemsCtn.html(data);
            loader.addClass("hidden");
        }).fail(function(data) {
            itemsCtn.html("Error!");
            loader.addClass("hidden");
        });
    });

    // Ấn tìm kiếm khi ấn enter ở ô tìm
    $('[name="q"]', modalPickSongs).on("keyup", function(e) {
        if (e.which == 13) {
            $('[name="submit"]', modalPickSongs).trigger("click");
        }
    });

    // Xử lý dữ liệu khi bắt đầu mở modal lên
    modalPickSongs.on('show.bs.modal', function() {
        $('[name="song_id_selected"]', modalPickSongs).val("");
        $('[name="page"]', modalPickSongs).val("1");

        // Build lại danh sách bài hát đã chọn
        var list = $(modalPickSongs.data("list"));
        var ids = new Array();

        $("li", list).each(function() {
            var id = $(this).find("input").val();
            var title = $(this).find(".val").html();
            var des = $(this).find(".sval").html();
            var html = buildSortableSongLists(id, title, des);
            ids.push(id);
            $(".item-selected", modalPickSongs).append(html);
        });

        $('[name="song_id_selected"]', modalPickSongs).val(ids.join(","));
    });

    // Trigger trình sort khi mở modal lên hoàn thành
    modalPickSongs.on('shown.bs.modal', function() {
        // Build lại trình sort
        $(".item-selected", modalPickSongs).sortable();
        $(".item-selected", modalPickSongs).disableSelection();
    });

    // Xử lý dữ liệu khi đóng modal lại
    modalPickSongs.on('hide.bs.modal', function() {
        $(".item-selected", modalPickSongs).sortable("destroy");
        $(".item-selected", modalPickSongs).html("");
        $(".item-lists", modalPickSongs).html("");
    });

    // Chọn một bài hát
    modalPickSongs.delegate('[data-toggle="selPickSong"]', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data("selected")) {
            return;
        }
        $this.html($this.data("selected-mess"));
        $this.data("selected", true);

        $(".item-selected", modalPickSongs).sortable("destroy");

        var html = buildSortableSongLists($this.data("id"), $this.data("title"), $this.data("des"));

        if (modalPickSongs.data("multiple")) {
            $(".item-selected", modalPickSongs).append(html);
        } else {
            $(".item-selected", modalPickSongs).html(html);
        }

        $(".item-selected", modalPickSongs).sortable();
        $(".item-selected", modalPickSongs).disableSelection();

        if (!modalPickSongs.data("multiple")) {
            $('[data-toggle="completePickSong"]', modalPickSongs).trigger("click");
        }
    });

    // Xóa bài hát đã chọn (trong modal)
    modalPickSongs.delegate('[data-toggle="delPickSong"]', "click", function(e) {
        e.preventDefault();

        $(".item-selected", modalPickSongs).sortable("destroy");
        $(this).parent().parent().parent().remove();
        $(".item-selected", modalPickSongs).sortable();
        $(".item-selected", modalPickSongs).disableSelection();

        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickSongs).each(function() {
            ids.push($(this).val());
        });
        $('[name="song_id_selected"]', modalPickSongs).val(ids.join(","));

        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickSongs).data("allowedpage", true);
        $('[name="submit"]', modalPickSongs).trigger("click");
    });

    // Xóa bài hát đã chọn (ngoài list)
    $(document).delegate('[data-toggle="delPickedSong"]', "click", function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    // CLick phân trang trong list bài hát được tìm thấy
    modalPickSongs.delegate('.item-lists .pagination a', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.attr("href") == "" || $this.attr("href") == "#") {
            return;
        }
        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickSongs).each(function() {
            ids.push($(this).val());
        });
        $('[name="song_id_selected"]', modalPickSongs).val(ids.join(","));
        // Xác định trang của nút chọn
        var page = 1;
        var matches_array = $this.attr("href").match(/page\=(\d+)/i);
        if (matches_array && matches_array.length > 1) {
            page = matches_array[1];
        }
        $('[name="page"]', modalPickSongs).val(page);
        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickSongs).data("allowedpage", true);
        $('[name="submit"]', modalPickSongs).trigger("click");
    });

    // Xác nhận các bài hát đã chọn
    $('[data-toggle="completePickSong"]').on("click", function(e) {
        e.preventDefault();

        // Lấy lại các list id đã chọn
        var html = '';
        $('[name="ids[]"]', modalPickSongs).each(function() {
            html += '<li>';
            html += '<input type="hidden" name="' + modalPickSongs.data("inputname") +'" value="' + $(this).val() + '">';
            html += '<a class="delitem" href="#" data-toggle="delPickedSong"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><strong class="val ms-ellipsis">' + $(this).data("title") + '</strong><small class="sval ms-ellipsis">' + $(this).data("des") + '</small>';
            html += '</li>';
        });

        var list = $(modalPickSongs.data("list"));
        list.html(html);

        // Ẩn modal đi
        modalPickSongs.modal("hide");
    });

    /*
     * Modal chọn một, nhiều video
     */

    // Hàm build list sắp xếp các video đã chọn khi mới mở popup và khi ấn nút chọn từ danh sách tìm kiếm
    function buildSortableVideoLists(id, title, des) {
        var html = '';
        html += '<li>';
        html += '<input type="hidden" name="ids[]" data-title="' + title + '" data-des="' + des + '" value="' + id + '">';
        html += '<div class="ctn">';
        html += '<div class="sicon pull-left"><i class="fa fa-arrows" aria-hidden="true"></i></div>';
        html += '<div class="sdel pull-right"><a href="#" data-toggle="delPickVideo"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>';
        html += '<div class="sval"><strong class="ms-ellipsis">' + title + '</strong></div>';
        html += '<div class="sdes"><small class="ms-ellipsis">' + des + '</small></div>';
        html += '</div>';
        html += '</li>';
        return html;
    }

    var btnTriggerPickVideos = $('[data-toggle="modalPickVideos"]');
    var modalPickVideos = $("#modalPickVideos");

    btnTriggerPickVideos.on("click", function(e) {
        e.preventDefault();
        modalPickVideos.data("list", $(this).data("list"));
        modalPickVideos.data("inputname", $(this).data("inputname"));
        modalPickVideos.data("multiple", $(this).data("multiple"));
        modalPickVideos.find("h4.modal-title").html($(this).data("title"));
        modalPickVideos.find('[name="q"]').val("");
        modalPickVideos.find('[name="cat_id"] option').prop("selected", false);
        modalPickVideos.modal("show");
    });

    // Ấn nút tìm kiếm video
    $('[name="submit"]', modalPickVideos).on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
        var loader = $this.find(".load");
        var itemsCtn = $('.item-lists', modalPickVideos);
        if (loader.is(":visible")) {
            return;
        }
        loader.removeClass("hidden");
        itemsCtn.html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i></div>');
        var page = ($this.data("allowedpage") ? $('[name="page"]', modalPickVideos).val() : 1);
        $this.data("allowedpage", false);
        $.ajax({
            method: "POST",
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax-search-videos&nocache=' + new Date().getTime(),
            data: {
                q: $('[name="q"]', modalPickVideos).val(),
                cat_id: $('[name="cat_id"]', modalPickVideos).val(),
                video_id_selected: $('[name="video_id_selected"]', modalPickVideos).val(),
                submit: 1,
                page: page
            }
        }).done(function(data) {
            itemsCtn.html(data);
            loader.addClass("hidden");
        }).fail(function(data) {
            itemsCtn.html("Error!");
            loader.addClass("hidden");
        });
    });

    // Ấn tìm kiếm khi ấn enter ở ô tìm
    $('[name="q"]', modalPickVideos).on("keyup", function(e) {
        if (e.which == 13) {
            $('[name="submit"]', modalPickVideos).trigger("click");
        }
    });

    // Xử lý dữ liệu khi bắt đầu mở modal lên
    modalPickVideos.on('show.bs.modal', function() {
        $('[name="video_id_selected"]', modalPickVideos).val("");
        $('[name="page"]', modalPickVideos).val("1");

        // Build lại danh sách bài hát đã chọn
        var list = $(modalPickVideos.data("list"));
        var ids = new Array();

        $("li", list).each(function() {
            var id = $(this).find("input").val();
            var title = $(this).find(".val").html();
            var des = $(this).find(".sval").html();
            var html = buildSortableVideoLists(id, title, des);
            ids.push(id);
            $(".item-selected", modalPickVideos).append(html);
        });

        $('[name="video_id_selected"]', modalPickVideos).val(ids.join(","));
    });

    // Trigger trình sort khi mở modal lên hoàn thành
    modalPickVideos.on('shown.bs.modal', function() {
        // Build lại trình sort
        $(".item-selected", modalPickVideos).sortable();
        $(".item-selected", modalPickVideos).disableSelection();
    });

    // Xử lý dữ liệu khi đóng modal lại
    modalPickVideos.on('hide.bs.modal', function() {
        $(".item-selected", modalPickVideos).sortable("destroy");
        $(".item-selected", modalPickVideos).html("");
        $(".item-lists", modalPickVideos).html("");
    });

    // Chọn một video
    modalPickVideos.delegate('[data-toggle="selPickVideo"]', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data("selected")) {
            return;
        }
        $this.html($this.data("selected-mess"));
        $this.data("selected", true);

        $(".item-selected", modalPickVideos).sortable("destroy");

        var html = buildSortableVideoLists($this.data("id"), $this.data("title"), $this.data("des"));

        if (modalPickVideos.data("multiple")) {
            $(".item-selected", modalPickVideos).append(html);
        } else {
            $(".item-selected", modalPickVideos).html(html);
        }

        $(".item-selected", modalPickVideos).sortable();
        $(".item-selected", modalPickVideos).disableSelection();

        if (!modalPickVideos.data("multiple")) {
            $('[data-toggle="completePickVideo"]', modalPickVideos).trigger("click");
        }
    });

    // Xóa video đã chọn (trong modal)
    modalPickVideos.delegate('[data-toggle="delPickVideo"]', "click", function(e) {
        e.preventDefault();

        $(".item-selected", modalPickVideos).sortable("destroy");
        $(this).parent().parent().parent().remove();
        $(".item-selected", modalPickVideos).sortable();
        $(".item-selected", modalPickVideos).disableSelection();

        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickVideos).each(function() {
            ids.push($(this).val());
        });
        $('[name="video_id_selected"]', modalPickVideos).val(ids.join(","));

        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickVideos).data("allowedpage", true);
        $('[name="submit"]', modalPickVideos).trigger("click");
    });

    // Xóa video đã chọn (ngoài list)
    $(document).delegate('[data-toggle="delPickedVideo"]', "click", function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    // CLick phân trang trong list bài hát được tìm thấy
    modalPickVideos.delegate('.item-lists .pagination a', "click", function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.attr("href") == "" || $this.attr("href") == "#") {
            return;
        }
        // Lấy lại các list id đã chọn
        var ids = new Array();
        $('[name="ids[]"]', modalPickVideos).each(function() {
            ids.push($(this).val());
        });
        $('[name="video_id_selected"]', modalPickVideos).val(ids.join(","));
        // Xác định trang của nút chọn
        var page = 1;
        var matches_array = $this.attr("href").match(/page\=(\d+)/i);
        if (matches_array && matches_array.length > 1) {
            page = matches_array[1];
        }
        $('[name="page"]', modalPickVideos).val(page);
        // Cho phép submit trang ở nút submit + submit
        $('[name="submit"]', modalPickVideos).data("allowedpage", true);
        $('[name="submit"]', modalPickVideos).trigger("click");
    });

    // Xác nhận các bài hát đã chọn
    $('[data-toggle="completePickVideo"]').on("click", function(e) {
        e.preventDefault();

        // Lấy lại các list id đã chọn
        var html = '';
        $('[name="ids[]"]', modalPickVideos).each(function() {
            html += '<li>';
            html += '<input type="hidden" name="' + modalPickVideos.data("inputname") +'" value="' + $(this).val() + '">';
            html += '<a class="delitem" href="#" data-toggle="delPickedVideo"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a><strong class="val ms-ellipsis">' + $(this).data("title") + '</strong><small class="sval ms-ellipsis">' + $(this).data("des") + '</small>';
            html += '</li>';
        });

        var list = $(modalPickVideos.data("list"));
        list.html(html);

        // Ẩn modal đi
        modalPickVideos.modal("hide");
    });

    // Chọn ca sĩ, nhạc sĩ từ lần trước
    $('[data-toggle="PickArtistFromLastTime"]').on('click', function(e) {
        e.preventDefault();
        var source = $($(this).data('source'));
        var target = $($(this).data('target'));
        target.html(source.html());
    });
});

$(window).on('load', function() {
});

$(window).on('resize', function() {
    if (msTimerResize) {
        clearTimeout(msTimerResize);
    }
    if (timerEllipsis) {
        clearTimeout(timerEllipsis);
    }
    msTimerResize = setTimeout(msResizeHandder, 50);
});

var msIconSheets = {};
msIconSheets.none = '<i class="fa fa-fw fa-angle-right"></i>';
msIconSheets.edit = '<i class="fa fa-fw fa-pencil"></i>';
msIconSheets.ajedit = '<i class="fa fa-fw fa-edit"></i>';
msIconSheets.delete = '<i class="fa fa-fw fa-trash"></i>';
msIconSheets.active = '<i class="fa fa-fw fa-circle"></i>';
msIconSheets.deactive = '<i class="fa fa-fw fa-circle-o"></i>';
msIconSheets.setdefault = '<i class="fa fa-fw fa-check-circle"></i>';
msIconSheets.setonlinesupported = '<i class="fa fa-fw fa-microphone"></i>';
msIconSheets.unsetonlinesupported = '<i class="fa fa-fw fa-microphone-slash"></i>';
msIconSheets.linkcc = '<i class="fa fa-fw fa-file-audio-o" aria-hidden="true"></i>';

function msGetPopoverContent(e) {
    var popKeys;
    var popType = $(e).data('type');
    if (popType == 'weight') {
        popKeys = "ms_tmppop_" + popType + '_' + $(e).data('max');
    } else {
        popKeys = "ms_tmppop_" + popType + '_' + $(e).data('options').replace(/\|/g, '_');
    }
    var popContents = $('#' + popKeys);
    if (!popContents.length) {
        $('body').append('<ul id="' + popKeys + '" class="hidden"></ul>');
        popContents = $('#' + popKeys);
        if (popType == 'weight') {
            // Xây dựng nội dung dạng weight
            for (var i = 1; i <= $(e).data('max'); i++) {
                popContents.append('<li><a href="#" data-value="' + i + '">' + i + '</a></li>');
            }
        } else {
            // Xây dựng nội dung dạng các công cụ options
            var options = $(e).data('options').split('|');
            var langs = $(e).data('langs').split('|');
            var others = $(e).data('others') ? $(e).data('others').split('|') : false;
            $.each(options, function(k, v) {
                popContents.append('<li><a href="#" data-value="' + v + '"' + (others === false ? '' : (' data-others="' + others[k] + '"')) + '>' + msIconSheets[v] + langs[k] + '</a></li>');
            });
        }
    }
    return '<div class="ms-dropdown-tool-ctn clearfix"><ul class="ms-dropdown-tool">' + popContents.html() + '</ul></div>';
}
