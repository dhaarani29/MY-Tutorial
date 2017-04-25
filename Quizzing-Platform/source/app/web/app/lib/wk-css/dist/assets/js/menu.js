(function (window, document, Modernizr, $) {
    'use strict';

    var navbarItem = '.wk-nav-item';
    var navbarMenu = '.wk-navbar .wk-more-menu';
    var navbarMenuNav = '.wk-navbar-container > .wk-nav';
    var RESIZE_DELAY = 700;

    function debounce(fn, wait) {
        var timeout;

        return function () {
            var context = this,
            args = arguments,
            later = function () {
                timeout = null;
                fn.apply(context, args);
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function biggerThanMobile () {
        if (Modernizr.mq('(min-width: 37.5em)')) {
            return true;
        }
        return false;
    }

    function closeAllSubMenus () {
        $(navbarItem).closest('li').find('.wk-sub-menu').each(function (i, subMenu) {
            var $subMenu = $(subMenu);
            $subMenu.removeClass('open');
            unbindBody($subMenu);
        });

    }

    function toggleTopMenuOpen (e) {
        var menu = $(navbarMenu).parent().parent();
        menu.toggleClass('open');
        $(navbarMenuNav).toggleClass('open');
        menu.data('bodyListener', handleCloseEvent(menu, true));

        if (e) {
            e.stopPropagation(); //don’t fire on the body listener that might be there
        }

        //close sub menus
        if(!$(navbarMenuNav).hasClass('open')) {
            closeAllSubMenus();
        }

        if(menu.hasClass('open')) {
            bindBody(menu);
        }
        else {
            unbindBody(menu);
        }
    }

    function bindBody (menu) {
        //start listening on body for a click or touch to close it
        $('body').on('click touch', menu.data('bodyListener'));
    }

    function unbindBody (menu) {
        $('body').unbind('click touch', menu.data('bodyListener')); //a click or touch on the body is executed, don’t listen anymore
        menu.data('bodyListener', null); //wipe out the reference to the function
    }

    function toggleItemOpen (e) {
        var subMenu = $(e.target).closest('li').find('.wk-sub-menu');
        subMenu.data('bodyListener', handleCloseEvent(subMenu));

        //if bigger than mobile, close other submenus
        if(biggerThanMobile()) {
            //closest 'a' in case it’s the arrow clicked
            // .not() because I don’t want to close myself
            $(navbarItem).not($(e.target).closest('a')).closest('li').find('.wk-sub-menu').each(function (i, subItem) {
                var $subItem = $(subItem);
                $subItem.removeClass('open');

                unbindBody($subItem);
            });
        }

        subMenu.toggleClass('open');
        e.stopPropagation(); //don’t fire on the body listener that might be there

        if(subMenu.hasClass('open')) {
            bindBody(subMenu);
        }
        else {
            unbindBody(subMenu);
        }
    }

    function handleCloseEvent (menu, topLevelMenu) {
        return function bodyCloseHandler () {
            menu.removeClass('open');

            if (topLevelMenu) {
                $(navbarMenuNav).removeClass('open');
            }

            unbindBody(menu);
        };
    }

    function handleMenuEvent (e) {
        if (isValidEvent(e)) {
            toggleTopMenuOpen(e);
        }
    }

    function handleItemEvent (e) {
        if (isValidEvent(e)) {
            toggleItemOpen(e);
        }
    }

    function isValidEvent (e) {
        if (e.type === 'click' || e.type === 'touch') {
            return true;
        }
        else if (e.type === 'keypress') {
            var code = e.charCode || e.keyCode; //normalize char codes cross browser

            //enter or spacebar
            if (code === 32 || code === 13) {
                e.preventDefault();
                return true;
            }
        }

        return false;
    }

    function doResize () {
        if(biggerThanMobile() && $(navbarMenuNav).hasClass('open')) {
            toggleTopMenuOpen();
        }
    }

    function ready () {
        $(navbarMenu).on('click keypress touch', handleMenuEvent);
        $(navbarItem).on('click keypress touch', handleItemEvent);
        $(window).bind('resize', debounce(doResize, RESIZE_DELAY));
    }

    function unload () {
        $(window).unbind();
        $(navbarMenu).unbind();
        $(navbarItem).unbind();
        $('body').unbind();
    }

    $(document).ready(ready);
    $(window).unload(unload);
}(window, document, Modernizr, jQuery));
