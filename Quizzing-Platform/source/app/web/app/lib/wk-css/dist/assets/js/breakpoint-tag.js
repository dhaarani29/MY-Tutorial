$(document).ready(function () {
    'use strict';

    function onResize () {
        var widthPx = $(window).width();
        var widthEms = widthPx / parseFloat($('body').css('font-size'));
        widthEms = +(widthEms.toFixed(2)) + ' em';

        $('.wk-breakpoint-tag > .width-px').text(widthPx + ' px');
        $('.wk-breakpoint-tag > .width-em').text(widthEms);
    }

    onResize();

    $(window).resize(onResize);
});
