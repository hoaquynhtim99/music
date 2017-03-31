/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

$(document).ready(function() {
    $('[data-toggle="show-va-singer"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).data('target'));
    });
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
    $(document).delegate('[data-toggle="select-all"]', 'click focus', function() {
        $(this).select();
    });
});