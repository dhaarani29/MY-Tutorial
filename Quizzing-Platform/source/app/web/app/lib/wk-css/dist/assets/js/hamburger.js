$(document).ready(function () {
    'use strict';

    $('.wk-menu-toggle').click(function () {
        $(this).toggleClass('wk-icon-menu');
        $(this).toggleClass('wk-icon-cancel');
        $('.wk-icons-list').toggle();

    });

    function biggerThanMobile () {
        if (Modernizr.mq('(min-width: 37.5em)')) {
            return true;
        }
        return false;
    }

    function onResize () {
        if(biggerThanMobile() && !$('.wk-icons-list').is(':visible')) {
            $('.wk-icons-list').show();
        }
    }

    $(window).resize(onResize);
});
