$(document).ready(function () {
    'use strict';

    $('.wk-search-input').keyup(function () {
        if($(this).val() === 'a') {
            $('.wk-list-format-a').show();
        }
        else if($(this).val() === 'b') {
            $('.wk-list-format-b').show();
        }
        else {
            $('.wk-list-format-b, .wk-list-format-a').hide()
        }
    });
});
