YUI(YUI3_config).add('updatepanels', function(Y) {
    Y.namespace('eZ');
    Y.eZ.updatePanelsHeight = function () {
        // do not use this script on login and register pages
        if (Y.one('body').hasClass('loginpage')) {return true}

        // at first "releasing" fixed height
        Y.all('#columns, #leftmenu, #rightmenu, #maincontent,#leftmenu-design, #rightmenu-design, #maincontent-design').setStyle('height', 'auto');
        
        // counting and setting height
        var columnsHeight = parseInt(Y.one('#page').getComputedStyle('height'),10) - parseInt(Y.one('#header').getComputedStyle('height'),10) - 25,
            footer = Y.one('#footer');
            
        if (footer && !(footer.getStyle('display') == 'none')){
            columnsHeight -= parseInt(footer.getComputedStyle('height'),10);
        }
        
        Y.one('#columns').setStyle('height', columnsHeight);
        Y.all('#leftmenu, #rightmenu, #maincontent').setStyle('height', columnsHeight - 3);
        Y.all('#leftmenu-design, #rightmenu-design, #maincontent-design').setStyle('height', columnsHeight - 63);
    }
}, '1.0.0', {'requires': ['node']});

YUI(YUI3_config).use('updatepanels','event', 'node-screen', 'node-style', 'selector-css3', 'transition', function (Y) {

    Y.on('domready', function() {
        
        Y.eZ.updatePanelsHeight();
        
        var toolbar, formY, form, fixed = true, firstInput, columns,
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
        columns = Y.one('#columns');
        if ( form && toolbar ) {
            toolbarHeight = parseInt(toolbar.getStyle('height')) + parseInt(toolbar.getStyle('top'));
            formY = parseInt(columns.getY()) - toolbarHeight;
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
    
    Y.on('windowresize',function() {
        
        Y.eZ.updatePanelsHeight();
        
    });

    
});

