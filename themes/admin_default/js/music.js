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
            editor  = false

        this.$element = $(element)
        this.options = options

        this.$element.attr('novalidate', true) // disable automatic native validation

        this.rebuildForm()

        this.$element.on('submit.bs.validate', $.proxy(this.onSubmit, this))

        this.$element.find("div.required,div.checkbox,div.radio,div.ckeditor,input:not(:button,:submit,:reset),select,textarea").each(function() {
            var element   = this,
                tagName   = $(this).prop('tagName'),
                name

            if( tagName == 'DIV' ){
                if( $(element).is('.ckeditor') ){
                    if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                        CKEDITOR.instances[name].on('change', function(){
                            self.hideError(element)
                        })
                    }
                }else{
                    $(element).on('click.bs.validate', function(e) {
                        self.hideError(element)
                    })
                }
            }else if( tagName == 'SELECT' ){
                $(element).on('click.bs.validate change.bs.validate', function(e) {
                    if( e.which != 13 ){
                        self.hideError(element)
                    }
                })
            }else{
                $(element).on('keydown.bs.validate', function(e) {
                    if( e.which != 13 ){
                        self.hideError(element)
                    }
                })
            }
        })
    }

    Validate.VERSION  = '4.0.23'

    Validate.DEFAULTS = {
        type   : 'normal'      // normal|ajax|file
    }

    Validate.MAIL_FILTER = /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/

    Validate.prototype.onSubmit = function(e) {
        if( ! this.validate() ){
            e.preventDefault()
            return
        }

        this.updateElement()

        if( this.options.type == 'ajax' ){
            e.preventDefault()
             this.submitAjax()
        }else if( this.options.type == 'file' ){
            this.submitFile()
        }

        return true
    }

    Validate.prototype.rebuildForm = function(){
        var html = '';
        if( ! $('.form-element', this.$element).length || ! $('.form-result', this.$element).length ){
            this.$element.find('.form-group').each(function(){
                html += $(this).context.outerHTML;
            })
            this.$element.html('<div class="form-result"></div><div class="form-element">' + html + '</div>');
        }
    }

    Validate.prototype.validate = function(){
        var self = this
        var error = 0

        this.$element.find(".required").each(function(){
            if( "password" == $(this).prop("type") ){
                $(this).val(self.trim(self.stripTags($(this).val())));
            }

            if (!self.check(this)){
                error ++
                if( typeof $(this).data('mess') != 'undefined' && $(this).data('mess') != '' ){
                    $(this).attr("data-current-mess", $(this).data('mess')).data('current-mess', $(this).data('mess'))
                }
                self.showError(this, error)

                return
            }else{
                self.hideError(this)
            }
        })

        return error ? false : true
    }

    Validate.prototype.hideAllError = function(){
        $(".has-error", this.$element).removeClass("has-error")
        $(".required", this.$element).tooltip("destroy")
    }

    Validate.prototype.hideError = function( element ){
        $(element).tooltip("destroy")

        if( $(element).parent().is('.input-group') ){
            $(element).parent().parent().parent().removeClass("has-error")
        }else{
            $(element).parent().parent().removeClass("has-error");
        }
    }

    Validate.prototype.showError = function( element, order ){
        var name

        if( $(element).parent().is('.input-group') ){
            $(element).parent().parent().parent().addClass("has-error")
        }else{
            $(element).parent().parent().addClass("has-error");
        }

        $(element).tooltip({
            placement: "bottom",
            title: function() {
                return ( typeof $(this).data('current-mess') != 'undefined' && $(element).data('current-mess') != '' ) ? $(element).data('current-mess') : ( 'undefined' == typeof nv_required ? 'This field is required!' : nv_required )
            },
            trigger: 'manual'
        });

        $(element).tooltip("show")
        if( order == 1 ){
            if( $(element).prop("tagName") == 'DIV' ){
                if( $(element).is('.ckeditor') ){
                    if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                        CKEDITOR.instances[name].focus()
                    }
                }else{
                    $("input", element)[0].focus()
                }
            }else{
                $(element).focus()
            }
        }
    }

    Validate.prototype.check = function( element ){
        var pattern = $(element).data('pattern'),
            value   = $(element).val(),
             tagName = $(element).prop('tagName'),
             type    = $(element).prop('type'),
             name, text

        if ("INPUT" == tagName && "email" == type) {
            if (!Validate.MAIL_FILTER.test(value)) return false
        } else if ("SELECT" == tagName) {
            if (!$("option:selected", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".radio")) {
            if (!$("[type=radio]:checked", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".checkbox")) {
            if (!$("[type=checkbox]:checked", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".ckeditor")) {
            if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                text = CKEDITOR.instances[name].getData()
                text = this.trim( text )

                if( text != '' ){
                    return true
                }
            }
            return false
        } else if ("INPUT" == tagName || "TEXTAREA" == tagName)
            if ("undefined" == typeof pattern || "" == pattern) {
                if ("" == value) return false
            } else if (!(new RegExp(pattern)).test(value)) return false
        return true
    }

    Validate.prototype.submitAjax = function(){
        var action  = this.$element.prop('action'),
            method  = this.$element.prop('method'),
            data    = this.$element.serialize(),
            self    = this

        if( typeof action == 'undefined' ){
            throw new Error('Missing action attitude for submit form')
        }
        if( typeof method == 'undefined' ){
            throw new Error('Missing method attitude for submit form')
        }

        if( action == '' ){
            action = window.location.href
        }

        this.hideAllError()
        this.$element.find("input,button,select,textarea").prop("disabled", true)

        $.ajax({
            type: method,
            cache: false,
            url: action,
            data: data,
            dataType: 'json',
            success: function(res) {
                var $this, type;

                self.$element.find("input,button,select,textarea").prop("disabled", false)
                if( ( res.status != 'error' && res.status != 'ok' ) || typeof res.message != 'string' || typeof res.input != 'string' ){
                    throw new Error('Response data is invalid!!')
                }
                if( res.status == 'error' ){
                    if( res.input != '' && $('[name="' + res.input + '"]', self.$element).length ){
                        $this = $('[name="' + res.input + '"]:first', self.$element)
                        type = $this.prop('type')

                        if( type == 'checkbox' || type == 'radio' || $this.parent().parent().is('.ckeditor') ){
                            $this = $this.parent().parent();
                        }

                        $this.attr("data-current-mess", res.message).data('current-mess', res.message)
                        self.showError($this, 1)
                    }else{
                        $('.form-result', self.$element).html('<div class="alert alert-danger">' + res.message + '</div>').show()
                        $("html, body").animate({ scrollTop: $('.form-result', self.$element).offset().top }, 500)
                    }

                    return
                }

                $('.form-result', self.$element).html('<div class="alert alert-success">' + res.message + '</div>').show()
                $("html, body").animate({ scrollTop: $('.form-result', self.$element).offset().top }, 500, function(){
                    setTimeout(function() {
                        $('.form-element', self.$element).slideUp(500);
                    }, 200)

                    setTimeout(function() {
                        window.location.href = ( typeof res.redirect == 'string' && res.redirect != '' ) ? res.redirect : window.location.href
                    }, 4000)
                })
            }
        })
    }

    Validate.prototype.updateElement = function(){
        var name

        if( typeof CKEDITOR == 'object' ){
            $('.ckeditor' , this.$element).each(function(){
                if( ( name = $(this).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                    CKEDITOR.instances[name].updateElement()
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

    function Plugin( option ){
        return this.each(function(){
            var $this   = $(this)
            var options = $.extend({}, Validate.DEFAULTS, $this.data(), typeof option == 'object' && option)
            var data    = $this.data('bs.validate')

            if (!data && option == 'destroy') return
            if (!data) $this.data('bs.validate', (data = new Validate(this, options)))
            if (typeof option == 'string') data[option]()
        })
    }

    var old = $.fn.validate

    $.fn.validate = Plugin
    $.fn.validate.Constructor = Validate

    // VALIDATE NO CONFLICT
    // =================
    $.fn.validate.noConflict = function() {
        $.fn.validate = old
        return this
    }

    // VALIDATE DATA-API
    // ==============
    $(window).on('load', function() {
        $('form[data-toggle="validate"]').each(function() {
            var $form = $(this)
            Plugin.call($form, $form.data())
        })
    })
}(jQuery);

var msAllPop = new Array();

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
            $(this).popover('show');
            $(this).on('shown.bs.popover', function() {
                console.log('SHOW');
                /*
                var $this = $(this);
                var ctn = $('#' + $this.attr('aria-describedby'));
                var wrapArea = ctn.find('.dropdown-cattool-ctn');
                var wrapContent = ctn.find('.dropdown-cattool');
                wrapContent.find('[data-value="' + $this.data('current') + '"]').addClass('active');
                if (wrapArea.height() < wrapContent.height()) {
                    var item = wrapContent.find('li:first');
                    var scrollTop = ($this.data('current') - $this.data('min')) * item.height();
                    wrapArea.scrollTop(scrollTop);
                }
                */
            });
        } else {
            msDestroyAllPop();
        }
    });
    $(document).delegate('.ms-dropdown-tool a', 'click', function(e) {
        e.preventDefault();
        msDestroyAllPop();
    });
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
});

$(window).on('load', function() {

});

function msGetPopoverContent() {
    return '<div class="ms-dropdown-tool-ctn"><ul class="ms-dropdown-tool">' + 123 + '</ul></div>';
}
