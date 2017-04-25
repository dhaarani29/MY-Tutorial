$(document).ready(function () {
    'use strict';

    $('.markup-highlight-ctrl').click(function (e) {
        switch(this.id) {
            case 'markup-highlight-forms':
                $('form').toggleClass('markup-highlight-forms');
                break;
            case 'markup-highlight-button-bars':
                $('.wk-button-bar').toggleClass('markup-highlight-button-bars');
                break;
        }
    });
});
