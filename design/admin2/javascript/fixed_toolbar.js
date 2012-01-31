YUI(YUI3_config).use('event', 'node-screen', 'node-style', 'selector-css3', 'transition', function (Y) {

    Y.on('domready', function() {
        var toolbar, formY, form, fixed = true, firstInput,
            toolbarHeight, toTop = Y.one('.scroll-to-top');

        var handleScroll = function () {
            if ( !fixed && toolbar.get('docScrollY') > formY ) {
                toolbar.addClass('controlbar-fixed');
                fixed = true;
            } else if ( toolbar.get('docScrollY') > (formY+20) && toTop ) {
                toTop.show('fadeIn', {opacity: 0.6, duration: 0.5});
            } else if ( fixed && toolbar.get('docScrollY') < formY ) {
                toolbar.removeClass('controlbar-fixed');
                fixed = false;
                if ( toTop )
                    toTop.hide('fadeOut', {opacity: 0, duration: 0.5});
            }
        }

        if ( toTop ) {
            toTop.setStyle('opacity', 0);
            toTop.on('click', function (e) {
                toTop.hide('fadeOut', {opacity: 0, duration: 0.5});
            });
        }

        toolbar = Y.one('#controlbar-top');
        form = Y.one('#editform,#ClassEdit');
        if ( form && toolbar ) {
            toolbarHeight = parseInt(toolbar.getStyle('height')) + parseInt(toolbar.getStyle('top'));
            formY = parseInt(form.getY()) - toolbarHeight;
            Y.on('scroll', handleScroll);
            if ( form.get('docScrollY') < formY ) {
                window.scrollTo(0, formY + 1, 0);
                firstInput = form.one('input[type=text]:enabled');
                if ( firstInput )
                    firstInput.focus();
            }
            handleScroll();
        }
    });
});

