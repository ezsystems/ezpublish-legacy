/**
 * editor_template_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

/* 
 * ez theme is a fork of advance theme modified for eZ Online Editor MCE integration
*/

(function(tinymce) {
	var DOM = tinymce.DOM, Event = tinymce.dom.Event, extend = tinymce.extend, each = tinymce.each, Cookie = tinymce.util.Cookie, lastExtID, explode = tinymce.explode, BIND = function()
	{
	    // eZ: Binds arguments to a function, so when you call the returned wrapper function,
	    // arguments are intact and arguments passed to the wrapper function is appended.
	    // first argument is function, second is 'this' and the rest is arguments
	    var __args = Array.prototype.slice.call( arguments ), __fn = __args.shift(), __obj = __args.shift();
	    return function(){return __fn.apply( __obj, __args.concat( Array.prototype.slice.call( arguments ) ) )};
	};

	// eZ: Not needed, as we handle language in design/standard/templates/content/datatype/edit/ezxmltext_ezoe.tp
	//tinymce.ThemeManager.requireLangPack('advanced');

	tinymce.create('tinymce.themes.eZTheme', {
        sizes : [8, 10, 12, 14, 18, 24, 36],

        // Control name lookup, format: title, command
        controls : {
            bold : ['bold_desc', 'Bold'],
            italic : ['italic_desc', 'Italic'],
            underline : ['underline_desc', 'Underline'],
            strikethrough : ['striketrough_desc', 'Strikethrough'],
            justifyleft : ['justifyleft_desc', 'JustifyLeft'],
            justifycenter : ['justifycenter_desc', 'JustifyCenter'],
            justifyright : ['justifyright_desc', 'JustifyRight'],
            justifyfull : ['justifyfull_desc', 'JustifyFull'],
            bullist : ['bullist_desc', 'InsertUnorderedList'],
            numlist : ['numlist_desc', 'InsertOrderedList'],
            outdent : ['outdent_desc', 'Outdent'],
            indent : ['indent_desc', 'Indent'],
            cut : ['cut_desc', 'Cut'],
            copy : ['copy_desc', 'Copy'],
            paste : ['paste_desc', 'Paste'],
            undo : ['undo_desc', 'Undo'],
            redo : ['redo_desc', 'Redo'],
            link : ['link_desc', 'mceLink'],
            unlink : ['unlink_desc', 'unlink'],
            image : ['image_desc', 'mceImage'],
            // START eZ: Additional buttons for ezoe handled by theme
            object : ['object_desc', 'mceObject'],
            file : ['file_desc', 'mceFile'],
            custom : ['custom_desc', 'mceCustom'],
            literal : ['literal_desc', 'mceLiteral'],
            pagebreak : ['pagebreak_desc', 'mcePageBreak'],
            disable : ['disable_desc', 'mceDisableEditor'],
            store : ['store_desc', 'mceStoreDraft'],
            publish : ['publish_desc', 'mcePublishDraft'],
            discard : ['discard_desc', 'mceDiscard'],
            // END eZ: Additional buttons for ezoe handled by theme
            cleanup : ['cleanup_desc', 'mceCleanup'],
            help : ['help_desc', 'mceHelp'],
            code : ['code_desc', 'mceCodeEditor'],
            hr : ['hr_desc', 'InsertHorizontalRule'],
            removeformat : ['removeformat_desc', 'RemoveFormat'],
            sub : ['sub_desc', 'subscript'],
            sup : ['sup_desc', 'superscript'],
            forecolor : ['forecolor_desc', 'ForeColor'],
            forecolorpicker : ['forecolor_desc', 'mceForeColor'],
            backcolor : ['backcolor_desc', 'HiliteColor'],
            backcolorpicker : ['backcolor_desc', 'mceBackColor'],
            charmap : ['charmap_desc', 'mceCharMap'],
            visualaid : ['visualaid_desc', 'mceToggleVisualAid'],
            anchor : ['anchor_desc', 'mceInsertAnchor'],
            newdocument : ['newdocument_desc', 'mceNewDocument'],
            blockquote : ['blockquote_desc', 'mceBlockQuote']
        },

        stateControls : ['bold', 'italic', 'underline', 'strikethrough', 'bullist', 'numlist', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'sub', 'sup', 'blockquote'],

        init : function(ed, url) {
            var t = this, s, v, o;
    
            t.editor = ed;
            t.url = url;
            t.onResolveName = new tinymce.util.Dispatcher(this);

            // Default settings
            t.settings = s = extend({
                theme_advanced_path : true,
                theme_advanced_toolbar_location : 'bottom',
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect",
                theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code",
                theme_advanced_buttons3 : "hr,removeformat,visualaid,|,sub,sup,|,charmap",
				theme_advanced_blockformats : "p,address,pre,h1,h2,h3,h4,h5,h6",
                theme_advanced_toolbar_align : "center",
                theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
                theme_advanced_more_colors : 1,
                theme_advanced_row_height : 23,
                theme_advanced_resize_horizontal : 1,
                theme_advanced_resizing_use_cookie : 1,
                theme_advanced_font_sizes : "1,2,3,4,5,6,7",
                theme_advanced_font_selector : "span",
                theme_advanced_show_current_color: 0,
                readonly : ed.settings.readonly
            }, ed.settings);

            // Setup default font_size_style_values
            if (!s.font_size_style_values)
                s.font_size_style_values = "8pt,10pt,12pt,14pt,18pt,24pt,36pt";

            if (tinymce.is(s.theme_advanced_font_sizes, 'string')) {
                s.font_size_style_values = tinymce.explode(s.font_size_style_values);
                s.font_size_classes = tinymce.explode(s.font_size_classes || '');

                // Parse string value
                o = {};
                ed.settings.theme_advanced_font_sizes = s.theme_advanced_font_sizes;
                each(ed.getParam('theme_advanced_font_sizes', '', 'hash'), function(v, k) {
                    var cl;

                    if (k == v && v >= 1 && v <= 7) {
                        k = v + ' (' + t.sizes[v - 1] + 'pt)';
                        cl = s.font_size_classes[v - 1];
                        v = s.font_size_style_values[v - 1] || (t.sizes[v - 1] + 'pt');
                    }

                    if (/^\s*\./.test(v))
                        cl = v.replace(/\./g, '');

                    o[k] = cl ? {'class' : cl} : {fontSize : v};
                });

                s.theme_advanced_font_sizes = o;
            }

            if ((v = s.theme_advanced_path_location) && v != 'none')
                s.theme_advanced_statusbar_location = s.theme_advanced_path_location;

            if (s.theme_advanced_statusbar_location == 'none')
                s.theme_advanced_statusbar_location = 0;

            // Init editor
            ed.onInit.add(function() {
                if (!ed.settings.readonly) {
                    ed.onNodeChange.add(t._nodeChanged, t);
                    ed.onKeyUp.add(t._updateUndoStatus, t);
                    ed.onMouseUp.add(t._updateUndoStatus, t);
                    ed.dom.bind(ed.dom.getRoot(), 'dragend', function() {
                        t._updateUndoStatus(ed);
                    });
                }

                // eZ: Override ctrl+8 & 9 and make them create pre tag
                ed.addShortcut('ctrl+8', '', ['FormatBlock', false, '<pre>']);
                ed.addShortcut('ctrl+9', '', ['FormatBlock', false, '<pre>']);

                // eZ: Support several theme css files using custom ez setting
                if ( s.theme_ez_content_css )
                {
                    var css_arr = s.theme_ez_content_css.split(',');
                    for ( var ind = 0, len = css_arr.length; ind < len; ind++ )
                       ed.dom.loadCSS( css_arr[ind] );
                }
                else if (ed.settings.content_css !== false)
                    ed.dom.loadCSS(ed.baseURI.toAbsolute(url + "/skins/" + ed.settings.skin + "/content.css"));

                // eZ : underline is represented with a custom tag
                // ie: <span class="ezoeItemCustomTag underline" type="custom">foo bar</span>
                ed.formatter.register({
                    underline : {inline : 'span',
                                 classes : 'ezoeItemCustomTag underline',
                                 attributes : {'type': 'custom'},
                                 exact: true,
                                 remove: 'all'}
                });

                // START eZ: alignment customizations
                // Add support for align attribute (taken from legacyoutput plugin)
                var alignElements = 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', serializer = ed.serializer;

                // Override some internal formats to produce legacy elements and attributes
                ed.formatter.register({
                    // Change alignment formats to use the deprecated align attribute
                    alignleft : {selector : alignElements, attributes : {align : 'left'}},
                    aligncenter : {selector : alignElements, attributes : {align : 'center'}},
                    alignright : {selector : alignElements, attributes : {align : 'right'}},
                    alignfull : {selector : alignElements, attributes : {align : 'full'}}
                });

                // Force parsing of the serializer rules
                serializer._setup();

                // Add the missing and depreacted align attribute for the serialization engine
                tinymce.each(alignElements.split(','), function(name) {
                    var rule = serializer.rules[name], found;

                    if (rule) {
                        tinymce.each(rule.attribs, function(name, attr) {
                            if (attr.name == 'align') {
                                found = true;
                                return false;
                            }
                        });

                        if (!found)
                            rule.attribs.push({name : 'align'});
                    }
                });
                // End eZ: alignment customizations
            });

            ed.onSetProgressState.add(function(ed, b, ti) {
                var co, id = ed.id, tb;

                if (b) {
                    t.progressTimer = setTimeout(function() {
                        co = ed.getContainer();
                        co = co.insertBefore(DOM.create('DIV', {style : 'position:relative'}), co.firstChild);
                        tb = DOM.get(ed.id + '_tbl');

                        DOM.add(co, 'div', {id : id + '_blocker', 'class' : 'mceBlocker', style : {width : tb.clientWidth + 2, height : tb.clientHeight + 2}});
                        DOM.add(co, 'div', {id : id + '_progress', 'class' : 'mceProgress', style : {left : tb.clientWidth / 2, top : tb.clientHeight / 2}});
                    }, ti || 0);
                } else {
                    DOM.remove(id + '_blocker');
                    DOM.remove(id + '_progress');
                    clearTimeout(t.progressTimer);
                }
            });

            // eZ: Setup custom cleanup code to performe when html is saved from TinyMCE to textarea
            ed.onBeforeGetContent.add(function(ed, o)
            {
                if ( o.save === true && o.format === 'html' )
                { 
                    var body = ed.getBody();

                    // Remove the content of the embed tags that are just there for oe preview
                    // purpose, this is to avoid that the ez xml parsers in some cases 
                    // duplicates the embed tag
                    jQuery.each( body.getElementsByTagName('div'), function( i, node )
                    {
                        if ( node && node.className.indexOf('ezoeItemNonEditable') !== -1 )
                            node.innerHTML = '';
                    });
                    jQuery.each( body.getElementsByTagName('span'), function( i, node )
                    {
                        if ( node && node.className.indexOf('ezoeItemNonEditable') !== -1 )
                            node.innerHTML = '';
                        else if ( node && node.className.indexOf('ezoeItemTempSpan') !== -1 && node.innerHTML.indexOf('&nbsp;') === 0 )
                            node.firstChild.replaceData( 0, 1, ' ' );
                    });

                    // @todo: Might not be needed anymore now that we don't use save handler and overwrite html
                    // Fix link cleanup issues in IE 6 / 7 (it adds the current url before the anchor and invalid urls)
                    var currenthost = document.location.protocol + '//' + document.location.host;
                    jQuery.each( body.getElementsByTagName('a'), function( i, node )
                    {
                        if ( node.href.indexOf( currenthost ) === 0 && node.getAttribute('mce_href') != node.href )
                            node.href = node.getAttribute('mce_href');
                    });
                }
            });

            // eZ: Support several editor content css files using custom ez setting
            if ( s.theme_ez_editor_css )
            {
                var ui_css_arr = s.theme_ez_editor_css.split(',');
                for ( var ind2 = 0, len2 = ui_css_arr.length; ind2 < len2; ind2++ )
                    DOM.loadCSS( ui_css_arr[ind2] );
            }
            else
            {
                DOM.loadCSS(s.editor_css ? ed.documentBaseURI.toAbsolute(s.editor_css) : url + "/skins/" + ed.settings.skin + "/ui.css");

                if (s.skin_variant)
                    DOM.loadCSS(url + "/skins/" + ed.settings.skin + "/ui_" + s.skin_variant + ".css");
            }

            // eZ: Setup ctrl+s to execute a stroe draft action (the whole content object)
            ed.addShortcut('ctrl+s', ed.getLang('save.save_desc'), 'mceStoreDraft');

            // eZ: the pasted text from Office with Firefox and Chrome on Windows may
            // contain some line feeds that are badly interpreted
            // see http://issues.ez.no/18239
            ed.onPostProcess.add(function(pl, o) {
                o.content = o.content.replace(/(\n|\r)/g, "");
            });
        },

        createControl : function(n, cf) {
            var cd, c;

            if (c = cf.createControl(n))
                return c;

            switch (n) {
                case "styleselect":
                    return this._createStyleSelect();

                case "formatselect":
                    return this._createBlockFormats();

                case "fontselect":
                    return this._createFontSelect();

                case "fontsizeselect":
                    return this._createFontSizeSelect();

                case "forecolor":
                    return this._createForeColorMenu();

                case "backcolor":
                    return this._createBackColorMenu();
            }

            if ((cd = this.controls[n]))
                return cf.createButton(n, {title : "advanced." + cd[0], cmd : cd[1], ui : cd[2], value : cd[3]});
        },

        execCommand : function(cmd, ui, val) {
            var f = this['_' + cmd];

            if (f) {
                f.call(this, ui, val);
                return true;
            }

            return false;
        },

        _importClasses : function(e) {
            var ed = this.editor, ctrl = ed.controlManager.get('styleselect');

            if (ctrl.getLength() == 0) {
                each(ed.dom.getClasses(), function(o, idx) {
                    var name = 'style_' + idx;

                    ed.formatter.register(name, {
                        inline : 'span',
                        attributes : {'class' : o['class']},
                        selector : '*'
                    });

                    ctrl.add(o['class'], name);
                });
            }
        },

        _createStyleSelect : function(n) {
            var t = this, ed = t.editor, ctrlMan = ed.controlManager, ctrl;

            // Setup style select box
            ctrl = ctrlMan.createListBox('styleselect', {
                title : 'advanced.style_select',
                onselect : function(name) {
                    var matches, formatNames = [];

                    each(ctrl.items, function(item) {
                        formatNames.push(item.value);
                    });

                    ed.focus();
                    ed.undoManager.add();

                    // Toggle off the current format
                    matches = ed.formatter.matchAll(formatNames);
                    if (!name || matches[0] == name)
                        ed.formatter.remove(matches[0]);
                    else
                        ed.formatter.apply(name);

                    ed.undoManager.add();
                    ed.nodeChanged();

                    return false; // No auto select
                }
            });

            // Handle specified format
            ed.onInit.add(function() {
                var counter = 0, formats = ed.getParam('style_formats');

                if (formats) {
                    each(formats, function(fmt) {
                        var name, keys = 0;

                        each(fmt, function() {keys++;});

                        if (keys > 1) {
                            name = fmt.name = fmt.name || 'style_' + (counter++);
                            ed.formatter.register(name, fmt);
                            ctrl.add(fmt.title, name);
                        } else
                            ctrl.add(fmt.title);
                    });
                } else {
                    each(ed.getParam('theme_advanced_styles', '', 'hash'), function(val, key) {
                        var name;

                        if (val) {
                            name = 'style_' + (counter++);

                            ed.formatter.register(name, {
                                inline : 'span',
                                classes : val,
                                selector : '*'
                            });

                            ctrl.add(t.editor.translate(key), name);
                        }
                    });
                }
            });

            // Auto import classes if the ctrl box is empty
            if (ctrl.getLength() == 0) {
                ctrl.onPostRender.add(function(ed, n) {
                    if (!ctrl.NativeListBox) {
                        Event.add(n.id + '_text', 'focus', t._importClasses, t);
                        Event.add(n.id + '_text', 'mousedown', t._importClasses, t);
                        Event.add(n.id + '_open', 'focus', t._importClasses, t);
                        Event.add(n.id + '_open', 'mousedown', t._importClasses, t);
                    } else
                        Event.add(n.id, 'focus', t._importClasses, t);
                });
            }

            return ctrl;
        },

        _createFontSelect : function() {
            var c, t = this, ed = t.editor;

            c = ed.controlManager.createListBox('fontselect', {
                title : 'advanced.fontdefault',
                onselect : function(v) {
                    var cur = c.items[c.selectedIndex];

                    if (!v && cur) {
                        ed.execCommand('FontName', false, cur.value);
	                    return;
	                }

                    ed.execCommand('FontName', false, v);

                    // Fake selection, execCommand will fire a nodeChange and update the selection
                    c.select(function(sv) {
                        return v == sv;
                    });

                    return false; // No auto select
                }
            });

            if ( c ) {
                each(ed.getParam('theme_advanced_fonts', t.settings.theme_advanced_fonts, 'hash'), function(v, k) {
                    c.add(ed.translate(k), v, {style : v.indexOf('dings') == -1 ? 'font-family:' + v : ''});
                });
            }

            return c;
        },

        _createFontSizeSelect : function() {
            var t = this, ed = t.editor, c, i = 0, cl = [];

            c = ed.controlManager.createListBox('fontsizeselect', {title : 'advanced.font_size', onselect : function(v) {
                var cur = c.items[c.selectedIndex];

                if (!v && cur) {
                    cur = cur.value;

                    if (cur['class']) {
                        ed.formatter.toggle('fontsize_class', {value : cur['class']});
	                    ed.undoManager.add();
	                    ed.nodeChanged();
	                } else {
	                    ed.execCommand('FontSize', false, cur.fontSize);
	                }

                    return;
                }

                if (v['class']) {
                    ed.focus();
                    ed.undoManager.add();
                    ed.formatter.toggle('fontsize_class', {value : v['class']});
                    ed.undoManager.add();
                    ed.nodeChanged();
                } else
                    ed.execCommand('FontSize', false, v.fontSize);

                // Fake selection, execCommand will fire a nodeChange and update the selection
                c.select(function(sv) {
                    return v == sv;
                });

                return false; // No auto select
            }});

            if (c) {
                each(t.settings.theme_advanced_font_sizes, function(v, k) {
                    var fz = v.fontSize;

                    if (fz >= 1 && fz <= 7)
                        fz = t.sizes[parseInt(fz) - 1] + 'pt';

                    c.add(k, v, {'style' : 'font-size:' + fz, 'class' : 'mceFontSize' + (i++) + (' ' + (v['class'] || ''))});
                });
            }

            return c;
        },

        _createBlockFormats : function() {
            // eZ: Comment out formats not supported by parser
            var c, fmts = {
                p : 'advanced.paragraph',
                //address : 'advanced.address',
                pre : 'advanced.pre',
                h1 : 'advanced.h1',
                h2 : 'advanced.h2',
                h3 : 'advanced.h3',
                h4 : 'advanced.h4',
                h5 : 'advanced.h5',
                h6 : 'advanced.h6'/*,
                div : 'advanced.div',
                blockquote : 'advanced.blockquote',
                code : 'advanced.code',
                dt : 'advanced.dt',
                dd : 'advanced.dd',
                samp : 'advanced.samp'*/
            }, t = this;

            c = t.editor.controlManager.createListBox('formatselect', {title : 'advanced.block', cmd : 'FormatBlock'});
            if (c) {
                each(t.editor.getParam('theme_advanced_blockformats', t.settings.theme_advanced_blockformats, 'hash'), function(v, k) {
                    c.add(t.editor.translate(k != v ? k : fmts[v]), v, {'class' : 'mce_formatPreview mce_' + v});
                });
            }

            return c;
        },

        _createForeColorMenu : function() {
            var c, t = this, s = t.settings, o = {}, v;

            if (s.theme_advanced_more_colors) {
                o.more_colors_func = function() {
                    t._mceColorPicker(0, {
                        color : c.value,
                        func : function(co) {
                            c.setColor(co);
                        }
                    });
                };
            }

            if (v = s.theme_advanced_text_colors)
                o.colors = v;

            if (s.theme_advanced_default_foreground_color)
                o.default_color = s.theme_advanced_default_foreground_color;

            o.title = 'advanced.forecolor_desc';
            o.cmd = 'ForeColor';
            o.scope = this;

            c = t.editor.controlManager.createColorSplitButton('forecolor', o);

            return c;
        },

        _createBackColorMenu : function() {
            var c, t = this, s = t.settings, o = {}, v;

            if (s.theme_advanced_more_colors) {
                o.more_colors_func = function() {
                    t._mceColorPicker(0, {
                        color : c.value,
                        func : function(co) {
                            c.setColor(co);
                        }
                    });
                };
            }

            if (v = s.theme_advanced_background_colors)
                o.colors = v;

            if (s.theme_advanced_default_background_color)
                o.default_color = s.theme_advanced_default_background_color;

            o.title = 'advanced.backcolor_desc';
            o.cmd = 'HiliteColor';
            o.scope = this;

            c = t.editor.controlManager.createColorSplitButton('backcolor', o);

            return c;
        },

        renderUI : function(o) {
            var n, ic, tb, t = this, ed = t.editor, s = t.settings, sc, p, nl;

            n = p = DOM.create('span', {id : ed.id + '_parent', 'class' : 'mceEditor ' + ed.settings.skin + 'Skin' + (s.skin_variant ? ' ' + ed.settings.skin + 'Skin' + t._ufirst(s.skin_variant) : '')});

            if (!DOM.boxModel)
                n = DOM.add(n, 'div', {'class' : 'mceOldBoxModel'});

            n = sc = DOM.add(n, 'table', {id : ed.id + '_tbl', 'class' : 'mceLayout', cellSpacing : 0, cellPadding : 0});
            n = tb = DOM.add(n, 'tbody');

            switch ((s.theme_advanced_layout_manager || '').toLowerCase()) {
                case "rowlayout":
                    ic = t._rowLayout(s, tb, o);
                    break;

                case "customlayout":
                    ic = ed.execCallback("theme_advanced_custom_layout", s, tb, o, p);
                    break;

                default:
                    ic = t._simpleLayout(s, tb, o, p);
            }

            n = o.targetNode;

            // Add classes to first and last TRs
            nl = DOM.stdMode ? sc.getElementsByTagName('tr') : sc.rows; // Quick fix for IE 8
            DOM.addClass(nl[0], 'mceFirst');
            DOM.addClass(nl[nl.length - 1], 'mceLast');

            // Add classes to first and last TDs
            each(DOM.select('tr', tb), function(n) {
                DOM.addClass(n.firstChild, 'mceFirst');
                DOM.addClass(n.childNodes[n.childNodes.length - 1], 'mceLast');
            });

            if ( s.theme_advanced_toolbar_container && DOM.get(s.theme_advanced_toolbar_container) )
                DOM.get(s.theme_advanced_toolbar_container).appendChild(p);
            else
                DOM.insertAfter(p, n);

            Event.add(ed.id + '_path_row', 'click', function(e) {
                e = e.target;

                if (e.nodeName == 'A') {
                    t._sel(e.className.replace(/^.*mcePath_([0-9]+).*$/, '$1'));

                    return Event.cancel(e);
                }
            });
/*
            if (DOM.get(ed.id + '_path_row')) {
                Event.add(ed.id + '_tbl', 'mouseover', function(e) {
                    var re;
    
                    e = e.target;

                    if (e.nodeName == 'SPAN' && DOM.hasClass(e.parentNode, 'mceButton')) {
                        re = DOM.get(ed.id + '_path_row');
                        t.lastPath = re.innerHTML;
                        DOM.setHTML(re, e.parentNode.title);
                    }
                });

                Event.add(ed.id + '_tbl', 'mouseout', function(e) {
                    if (t.lastPath) {
                        DOM.setHTML(ed.id + '_path_row', t.lastPath);
                        t.lastPath = 0;
                    }
                });
            }
*/

            if (!ed.getParam('accessibility_focus'))
                Event.add(DOM.add(p, 'a', {href : '#'}, '<!-- IE -->'), 'focus', function() {tinyMCE.get(ed.id).focus();});

            if (s.theme_advanced_toolbar_location == 'external')
                o.deltaHeight = 0;

            t.deltaHeight = o.deltaHeight;
            o.targetNode = null;

            return {
                iframeContainer : ic,
                editorContainer : ed.id + '_parent',
                sizeContainer : sc,
                deltaHeight : o.deltaHeight
            };
        },

        getInfo : function() {
            return {
                longname : 'eZ theme based on TinyMCE Advance theme',
                author : 'Moxiecode Systems AB / eZ Systems AS',
                authorurl : 'http://tinymce.moxiecode.com',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            }
        },

        resizeBy : function(dw, dh) {
            var e = DOM.get(this.editor.id + '_tbl');

            this.resizeTo(e.clientWidth + dw, e.clientHeight + dh);
        },

        resizeTo : function(w, h, store) {
            var ed = this.editor, s = this.settings, e = DOM.get(ed.id + '_tbl'), ifr = DOM.get(ed.id + '_ifr');

            // Boundery fix box
            w = Math.max(s.theme_advanced_resizing_min_width || 100, w);
            h = Math.max(s.theme_advanced_resizing_min_height || 100, h);
            w = Math.min(s.theme_advanced_resizing_max_width || 0xFFFF, w);
            h = Math.min(s.theme_advanced_resizing_max_height || 0xFFFF, h);

            // Resize iframe and container
            DOM.setStyle(e, 'height', '');
            DOM.setStyle(ifr, 'height', h);

            if (s.theme_advanced_resize_horizontal) {
                DOM.setStyle(e, 'width', '');
                DOM.setStyle(ifr, 'width', w);

                // Make sure that the size is never smaller than the over all ui
                if (w < e.clientWidth) {
                    w = e.clientWidth;
                    DOM.setStyle(ifr, 'width', e.clientWidth);
                }
            }

            // Store away the size
            if (store && s.theme_advanced_resizing_use_cookie) {
                Cookie.setHash("TinyMCE_" + ed.id + "_size", {
                    cw : w,
                    ch : h
                });
            }
        },

        destroy : function() {
            var id = this.editor.id;

            Event.clear(id + '_resize');
            Event.clear(id + '_path_row');
            Event.clear(id + '_external_close');
        },

        // Internal functions

        _simpleLayout : function(s, tb, o, p) {
            var t = this, ed = t.editor, lo = s.theme_advanced_toolbar_location, sl = s.theme_advanced_statusbar_location, n, ic, etb, c;

            if (s.readonly) {
                n = DOM.add(tb, 'tr');
                n = ic = DOM.add(n, 'td', {'class' : 'mceIframeContainer'});
                return ic;
            }

            // Create toolbar container at top
            if (lo == 'top')
                t._addToolbars(tb, o);

            // Create external toolbar
            if (lo == 'external') {
                n = c = DOM.create('div', {style : 'position:relative'});
                n = DOM.add(n, 'div', {id : ed.id + '_external', 'class' : 'mceExternalToolbar'});
                DOM.add(n, 'a', {id : ed.id + '_external_close', href : 'javascript:;', 'class' : 'mceExternalClose'});
                n = DOM.add(n, 'table', {id : ed.id + '_tblext', cellSpacing : 0, cellPadding : 0});
                etb = DOM.add(n, 'tbody');

                if (p.firstChild.className == 'mceOldBoxModel')
                    p.firstChild.appendChild(c);
                else
                    p.insertBefore(c, p.firstChild);

                t._addToolbars(etb, o);

                ed.onMouseUp.add(function() {
                    var e = DOM.get(ed.id + '_external');
                    DOM.show(e);

                    DOM.hide(lastExtID);

                    var f = Event.add(ed.id + '_external_close', 'click', function() {
                        DOM.hide(ed.id + '_external');
                        Event.remove(ed.id + '_external_close', 'click', f);
                    });

                    DOM.show(e);
                    DOM.setStyle(e, 'top', 0 - DOM.getRect(ed.id + '_tblext').h - 1);

                    // Fixes IE rendering bug
                    DOM.hide(e);
                    DOM.show(e);
                    e.style.filter = '';

                    lastExtID = ed.id + '_external';

                    e = null;
                });
            }

            if (sl == 'top')
                t._addStatusBar(tb, o);

            // Create iframe container
            if (!s.theme_advanced_toolbar_container) {
                n = DOM.add(tb, 'tr');
                n = ic = DOM.add(n, 'td', {'class' : 'mceIframeContainer'});
            }

            // Create toolbar container at bottom
            if (lo == 'bottom')
                t._addToolbars(tb, o);

            if (sl == 'bottom')
                t._addStatusBar(tb, o);

            return ic;
        },

        _rowLayout : function(s, tb, o) {
            var t = this, ed = t.editor, dc, da, cf = ed.controlManager, n, ic, to, a;

            dc = s.theme_advanced_containers_default_class || '';
            da = s.theme_advanced_containers_default_align || 'center';

            each(explode(s.theme_advanced_containers || ''), function(c, i) {
                var v = s['theme_advanced_container_' + c] || '';

                switch (v.toLowerCase()) {
                    case 'mceeditor':
                        n = DOM.add(tb, 'tr');
                        n = ic = DOM.add(n, 'td', {'class' : 'mceIframeContainer'});
                        break;

                    case 'mceelementpath':
                        t._addStatusBar(tb, o);
                        break;

                    default:
                        a = (s['theme_advanced_container_' + c + '_align'] || da).toLowerCase();
                        a = 'mce' + t._ufirst(a);

                        n = DOM.add(DOM.add(tb, 'tr'), 'td', {
                            'class' : 'mceToolbar ' + (s['theme_advanced_container_' + c + '_class'] || dc) + ' ' + a || da
                        });

                        to = cf.createToolbar("toolbar" + i);
                        t._addControls(v, to);
                        DOM.setHTML(n, to.renderHTML());
                        o.deltaHeight -= s.theme_advanced_row_height;
                }
            });

            return ic;
        },

        _addControls : function(v, tb) {
            var t = this, s = t.settings, di, cf = t.editor.controlManager;

            if (s.theme_advanced_disable && !t._disabled) {
                di = {};

                each(explode(s.theme_advanced_disable), function(v) {
                    di[v] = 1;
                });

                t._disabled = di;
            } else
                di = t._disabled;

            each(explode(v), function(n) {
                var c;

                if (di && di[n])
                    return;

                // Compatiblity with 2.x
                if (n == 'tablecontrols') {
                    each(["table","|","row_props","cell_props","|","row_before","row_after","delete_row","|","col_before","col_after","delete_col","|","split_cells","merge_cells"], function(n) {
                        n = t.createControl(n, cf);

                        if (n)
                            tb.add(n);
                    });

                    return;
                }

                c = t.createControl(n, cf);

                if (c)
                    tb.add(c);
            });
        },

        _addToolbars : function(c, o) {
            var t = this, i, tb, ed = t.editor, s = t.settings, v, cf = ed.controlManager, di, n, h = [], a;

            a = s.theme_advanced_toolbar_align.toLowerCase();
            a = 'mce' + t._ufirst(a);

            n = DOM.add(DOM.add(c, 'tr'), 'td', {'class' : 'mceToolbar ' + a});

            if (!ed.getParam('accessibility_focus'))
                h.push(DOM.createHTML('a', {href : '#', onfocus : 'tinyMCE.get(\'' + ed.id + '\').focus();'}, '<!-- IE -->'));

            h.push(DOM.createHTML('a', {href : '#', accesskey : 'q', title : ed.getLang("advanced.toolbar_focus")}, '<!-- IE -->'));

            // Create toolbar and add the controls
            for (i=1; (v = s['theme_advanced_buttons' + i]); i++) {
                tb = cf.createToolbar("toolbar" + i, {'class' : 'mceToolbarRow' + i});

                if (s['theme_advanced_buttons' + i + '_add'])
                    v += ',' + s['theme_advanced_buttons' + i + '_add'];

                if (s['theme_advanced_buttons' + i + '_add_before'])
                    v = s['theme_advanced_buttons' + i + '_add_before'] + ',' + v;

                t._addControls(v, tb);

                //n.appendChild(n = tb.render());
                // eZ: Custom floating toobar style for ez theme
                if ( s.theme_advanced_toolbar_floating )
                    h.push( t._toolbarRenderFlowHTML.call( tb ) );
                else
                    h.push( tb.renderHTML() );

                o.deltaHeight -= s.theme_advanced_row_height;
            }

            h.push(DOM.createHTML('a', {href : '#', accesskey : 'z', title : ed.getLang("advanced.toolbar_focus"), onfocus : 'tinyMCE.getInstanceById(\'' + ed.id + '\').focus();'}, '<!-- IE -->'));
            DOM.setHTML(n, h.join(''));
        },
        
        // eZ: Custom floating toolbar renderer for ez theme
        // allows all buttons to be on one line and wrap to next line if there is not enough space
        _toolbarRenderFlowHTML : function()
        {
            var t = this, h = '<div class="mceToolbarGroupingElement">', c = 'mceToolbarElement mceToolbarEnd', co, s = t.settings, c2 = 'mceToolbar mceToolBarFlow';
            
            h += DOM.createHTML('span', {'class' : 'mceToolbarElement mceToolbarStart'}, DOM.createHTML('span', null, '<!-- IE -->'));

            each(t.controls, function(c)
            {
                // seperators create invalid html, so we create it here instead 
                if ( c.classPrefix === 'mceSeparator' )
                {
                    h += '<span class="mceToolbarElement">' + DOM.createHTML('span', {'class' : 'mceSeparator'}, '<!-- IE -->') + '</span>';
                    h += '</div><div class="mceToolbarGroupingElement">';
                }
                else h += '<span class="mceToolbarElement">' + c.renderHTML() + '</span>';
            });
            
            co = t.controls[t.controls.length - 1].constructor;

            if (co === tinymce.ui.Button)
                c += ' mceToolbarEndButton';
            else if (co === tinymce.ui.SplitButton)
                c += ' mceToolbarEndSplitButton';
            else if (co === tinymce.ui.ListBox)
                c += ' mceToolbarEndListBox';
    
            h += DOM.createHTML('span', {'class' : c}, DOM.createHTML('span', null, '<!-- IE -->')) + '</div>';

            if ( tinymce.isIE && !DOM.stdMode ) c2 += ' mceToolBarFlowIEbug';
            else if ( tinymce.isOpera && navigator.appVersion < 9.30 ) c2 += ' mceToolBarFlowIEbug';

            return DOM.createHTML('div', {id : t.id, 'class' : c2 + (s['class'] ? ' ' + s['class'] : ''), align : t.settings.align || ''},  h );
        },

        _addStatusBar : function(tb, o) {
            var n, t = this, ed = t.editor, s = t.settings, r, mf, me, td;

            n = DOM.add(tb, 'tr');
            n = td = DOM.add(n, 'td', {'class' : 'mceStatusbar'});
            n = DOM.add(n, 'div', {id : ed.id + '_path_row'}, s.theme_advanced_path ? ed.translate('advanced.path') + ': ' : '&#160;');
            DOM.add(n, 'a', {href : '#', accesskey : 'x'});

            if (s.theme_advanced_resizing) {
                DOM.add(td, 'a', {id : ed.id + '_resize', href : 'javascript:;', onclick : "return false;", 'class' : 'mceResize'});

                if (s.theme_advanced_resizing_use_cookie) {
                    ed.onPostRender.add(function() {
                        var o = Cookie.getHash("TinyMCE_" + ed.id + "_size"), c = DOM.get(ed.id + '_tbl');

                        if (!o)
                            return;

                        t.resizeTo(o.cw, o.ch);
                    });
                }

                ed.onPostRender.add(function() {
                    Event.add(ed.id + '_resize', 'click', function(e) {
                        e.preventDefault();
                    });

                    Event.add(ed.id + '_resize', 'mousedown', function(e) {
                        var mouseMoveHandler1, mouseMoveHandler2,
                            mouseUpHandler1, mouseUpHandler2,
                            startX, startY, startWidth, startHeight, width, height, ifrElm;

                        function resizeOnMove(e) {
                            e.preventDefault();

                            width = startWidth + (e.screenX - startX);
                            height = startHeight + (e.screenY - startY);

                            t.resizeTo(width, height);
                        };

                        function endResize(e) {
                            // Stop listening
                            Event.remove(DOM.doc, 'mousemove', mouseMoveHandler1);
                            Event.remove(ed.getDoc(), 'mousemove', mouseMoveHandler2);
                            Event.remove(DOM.doc, 'mouseup', mouseUpHandler1);
                            Event.remove(ed.getDoc(), 'mouseup', mouseUpHandler2);

                            width = startWidth + (e.screenX - startX);
                            height = startHeight + (e.screenY - startY);
                            t.resizeTo(width, height, true);
                        };

                        e.preventDefault();

                        // Get the current rect size
                        startX = e.screenX;
                        startY = e.screenY;
                        ifrElm = DOM.get(t.editor.id + '_ifr');
                        startWidth = width = ifrElm.clientWidth;
                        startHeight = height = ifrElm.clientHeight;

                        // Register envent handlers
                        mouseMoveHandler1 = Event.add(DOM.doc, 'mousemove', resizeOnMove);
                        mouseMoveHandler2 = Event.add(ed.getDoc(), 'mousemove', resizeOnMove);
                        mouseUpHandler1 = Event.add(DOM.doc, 'mouseup', endResize);
                        mouseUpHandler2 = Event.add(ed.getDoc(), 'mouseup', endResize);
                    });
                });
            }

            o.deltaHeight -= 21;
            n = tb = null;
        },

        _updateUndoStatus : function(ed) {
            var cm = ed.controlManager;
            cm.setDisabled('undo', !ed.undoManager.hasUndo() && !ed.typing);
            cm.setDisabled('redo', !ed.undoManager.hasRedo());
        },

        _nodeChanged : function(ed, cm, n, co, ob) {
            var t = this, p, de = 0, v, c, c2, s = t.settings, cl, fz, fn, fc, bc, formatNames, matches, ezoeItemNonEditable = false, div = false, header, type = '', jn;

            tinymce.each(t.stateControls, function(c) {
                cm.setActive(c, ed.queryCommandState(t.controls[c][1]));
            });
            
            function getParent(name) {
                var i, parents = ob.parents, func = name;

                if (typeof(name) == 'string') {
                    // eZ: Support list of tag names seperated by ,
                    if ( name.indexOf(',') === -1 ) {
                        func = function(node) {
                            return node.nodeName == name;
                        };
                    } else {
                        name = ',' + name + ',';
                        func = function(node) {
                            return name.indexOf( ',' + node.nodeName + ',' ) !== -1;
                        };
                    }
                    
                }

                for (i = 0, l = parents.length; i < l; i++) {
                    if (func(parents[i]))
                        return parents[i];
                }
            };

            // START eZ: Handle embed tags (not editable..)
            header = getParent('H1,H2,H3,H4,H5,H6');
            p = getParent('DIV,SPAN');
            c = cm.get('object');
            c2 = cm.get('file')
            if ( c || c2 )
            {
                if ( ( p && (p.nodeName === 'DIV' || p.nodeName === 'SPAN') && p.className.indexOf('ezoeItemNonEditable') !== -1 ) )
                {
                    ezoeItemNonEditable = true;
                }
                else if ( (p = t.__getParentByTag( n, 'div,span', 'ezoeItemNonEditable') ) )
                {
                    ezoeItemNonEditable = true;
                }

                if ( ezoeItemNonEditable  )
                {
                    ed.selection.select( p );
                    div = p.nodeName === 'DIV';
                    n = p;
                    type = p.className.indexOf('ezoeItemContentTypeFiles') !== -1 ? 'files' : 'objects';

                    // change parent array now that n has changed
                    ob.parents = [];
                    DOM.getParent(n, function(node) {
                        if (node.nodeName == 'BODY')
                            return true;

                        ob.parents.push(node);
                    });
                }

                if ( c )
                {
                    c.setActive( ezoeItemNonEditable && (!c2 || type === 'objects')  );
                    c.setDisabled( header || (ezoeItemNonEditable && (type !== 'objects' && c2)) );
                }
                if ( c2 )
                {
                    c2.setActive( ezoeItemNonEditable && (type === 'files')  );
                    c2.setDisabled( header || (ezoeItemNonEditable && type !== 'files') );
                }
            }

            t.__setDisabled( ezoeItemNonEditable );
            // END eZ: Handle embed tags

            cm.setActive('visualaid', ed.hasVisual);
			t._updateUndoStatus(ed);

            // ordered / unordered list button code
            if (header && (c = cm.get('bullist')))
                c.setDisabled( header );
            if (header && (c = cm.get('numlist')))
                c.setDisabled( header );

            // indent and outdent button code
            p = getParent('UL,OL');
            if (c = cm.get('outdent'))
            {
                c.setDisabled( !p && !ed.queryCommandState('Outdent') );
            }
            if (c = cm.get('indent'))
            {
                if ( p )
                {
                    for (var i = 0, l = p.childNodes.length, count = 0; i < l; i++)
                    {
                        if (p.childNodes[i].nodeType === 1 && p.childNodes[i].nodeName === 'LI') count++;
                        if ( count === 2 ) break;                        
                    }
                }
                c.setDisabled( !p || count < 2 );
            }

            // eZ: table button code
            p = getParent('TD,TH,CAPTION,TR');
            if (p && p.nodeName === 'CAPTION') p = null;
            cm.setDisabled('table', header );
            cm.setDisabled('tablemenu', header );
            cm.setDisabled('delete_table', !p);
            cm.setDisabled('delete_col', !p);
            cm.setDisabled('delete_table', !p);
            cm.setDisabled('delete_row', !p);
            cm.setDisabled('col_after', !p);
            cm.setDisabled('col_before', !p);
            cm.setDisabled('row_after', !p);
            cm.setDisabled('row_before', !p);
            cm.setDisabled('row_props', !p);
            cm.setDisabled('cell_props', !p);
            cm.setDisabled('split_cells', !p || (parseInt(DOM.getAttrib(p, 'colspan', '1')) < 2 && parseInt(DOM.getAttrib(p, 'rowspan', '1')) < 2));
            cm.setDisabled('merge_cells', !p);


            // eZ: Get status on alignment buttons, check parent tag if current tag is not supported
            p = this.__mceJustifyTags.test( n.nodeName );
            if ( p )
                jn = n;
            else if ( n.parentNode && (p = this.__mceJustifyTags.test( n.parentNode.nodeName )) )
                jn = n.parentNode;
            if ( c = cm.get('justifyleft') )
            {
                c.setDisabled( !p );
                c.setActive( p && jn.align === 'left' );
            }
            if ( c = cm.get('justifyright') )
            {
                c.setDisabled( !p );
                c.setActive( p && jn.align === 'right' );
            }
            if ( c = cm.get('justifycenter') )
            {
                c.setDisabled( !p );
                // use n, since na might not be set, and IMG is supported anyway so na is not parentNode
                if ( n.nodeName === 'IMG' )
                    c.setActive( p && jn.align === 'middle' );
                else
                    c.setActive( p && jn.align === 'center' );
            }
            //justifyfull is not supported on block tags, so recheck node
            if ( p && this.__mceJustifyBlockTags.test( n.nodeName ) )
                p = false;
            if ( c = cm.get('justifyfull') )
            {
                c.setDisabled( !p );
                c.setActive( p && jn.align === 'justify' );
            }

            // eZ: link and anchor(inside next block) code
            p = getParent('A');
            if ( c = cm.get('link') )
            {
                if ( !p || DOM.getAttrib(p, 'href') )
                {
                    c.setDisabled( div || !p && co );
                    c.setActive(!!p);
                }
            }
            if ( c = cm.get('unlink') )
            {
                c.setDisabled(!p || !DOM.getAttrib(p, 'href') );
                c.setActive(!!p && DOM.getAttrib(p, 'href') );
            }

            // eZ: buttons that are disabled when embed object tag is selectd
            if ( ezoeItemNonEditable === false )
            {
                if ( c = cm.get('anchor') )
                {
                    c.setActive( !!p && DOM.getAttrib(p, 'name') && !DOM.getAttrib(p, 'href') );
                }
                
                p = header ? header : getParent('DIV');
                if (p && (c = cm.get('pagebreak')))
                    c.setDisabled( !!p && DOM.hasClass(p, 'pagebreak') );

                p = getParent('IMG');
                if (c = cm.get('image'))
                {
                    c.setActive(!!p && p.className.indexOf('ezoeItem') === -1);
                    c.setDisabled( header );
                }

                p = getParent('PRE');
                if (c = cm.get('literal'))
                {
                    c.setActive(!!p );
                    c.setDisabled( header );
                }

                if (c = cm.get('formatselect'))
                {
                    p = getParent(DOM.isBlock);
                    if ( p && ( p.className === 'mceItemHidden' || p.nodeName === 'LI' || p.nodeName === 'UL' || p.nodeName === 'OL' ) )
                        c.setDisabled( true );
                    else if ( p )
                        c.select(p.nodeName.toLowerCase());
                }
            }

            if (s.theme_advanced_path && s.theme_advanced_statusbar_location) {
                p = DOM.get(ed.id + '_path') || DOM.add(ed.id + '_path_row', 'span', {id : ed.id + '_path'});
                DOM.setHTML(p, '&nbsp;');

                getParent(function(n) {
                    var na = n.nodeName.toLowerCase(), u, pi, ti = '', className = false;

                    /*if (n.getAttribute('_mce_bogus'))
                    return;
*/
                    // Ignore non element and hidden elements
                    if ( n.nodeType != 1 || n.nodeName === 'BR' || (DOM.hasClass(n, 'mceItemHidden') || DOM.hasClass(n, 'mceItemRemoved')) )
                        return;

                   // eZ: seems like hasClass has some issues in ie..
                   if ( DOM.getAttrib( n, 'class').indexOf('mceItemHidden') !== -1 )
                       return;

                   // eZ: Ignore hidden spellcheker nodes (both spellcheck and AtD plugin)
                   if ( DOM.hasClass(n, 'mceItemHiddenSpellWord') || DOM.hasClass(n, 'hiddenGrammarError') || DOM.hasClass(n, 'hiddenSpellError') || DOM.hasClass(n, 'hiddenSuggestion') )
                       return;

                    // eZ: node name to ez xml tag translation
                    if ( v = t.__tagsToXml( n ) )
                        na = v;

                    // eZ: xml tag name alias (frindly names)
                    var naa = na;
                    if ( s.theme_ez_xml_alias_list && s.theme_ez_xml_alias_list[na] !== undefined )
                        naa = s.theme_ez_xml_alias_list[na];

                    // Fake name
                    if (v = DOM.getAttrib(n, 'mce_name'))
                        na = v;

                    // Handle prefix
                    if (tinymce.isIE && n.scopeName !== 'HTML')
                        na = n.scopeName + ':' + na;

                    // Remove internal prefix
                    na = na.replace(/mce\:/g, '');

                    // Handle node name
                    switch (na) {
                        // eZ: Ignore table section tags (not supported)
                        case 'tbody':
                        case 'thead':
                        case 'tfoot':
                            return false;
                        case 'embed':
                        case 'embed-inline':
                            //if (v = DOM.getAttrib(n, 'src'))
                                //ti += 'src: ' + v + ' ';
                            if (v = DOM.getAttrib(n, 'title'))
                                ti += 'name: ' + v + ' ';

                            break;
                        case 'anchor':
                        case 'link':
                            if (v = DOM.getAttrib(n, 'href'))
                                ti += 'href: ' + v + ' ';
                            else if (v = DOM.getAttrib(n, 'name'))
                                naa += '#' + v;
                            break;
                        case 'custom':
                            if (v = DOM.getAttrib(n, 'style'))
                                ti += 'style: ' + v + ' ';
                            if ( n.nodeName === 'U' || n.style.textDecoration === 'underline' )
                                className = 'underline';
                            else if ( n.nodeName === 'SUB' )
                                className = 'sub';
                            else if ( n.nodeName === 'SUP' )
                                className = 'sup';
                            break;
                        case 'header':
                            if (v = n.nodeName)
                                naa += ' ' + v.charAt(1);

                            break;
                    }

                    if (v = DOM.getAttrib(n, 'id'))
                        ti += 'id: ' + v + ' ';

                    // eZ: Support custom className var and remove internal ezoeItem prefix
                    if (v = className ?  className : n.className)
                    {
                        v = v.replace(/\b\s*(webkit|mce|Apple-|ezoeItem)\w+\s*\b/g, '')

                        if (v) {
                            ti = ti + 'class: ' + v + ' ';
                            //if (na === 'embed' || na === 'custom' || DOM.isBlock(n))
                            naa = naa + '.' + v.replace(' ', '.');
                        }
                    }

                    naa = naa.replace(/(html:)/g, '');
                    na = {name : naa, node : n, title : ti};
                    t.onResolveName.dispatch(t, na);
                    ti = na.title;
                    na = na.name;

                    //u = "javascript:tinymce.EditorManager.get('" + ed.id + "').theme._sel('" + (de++) + "');";
                    if ( s.theme_ez_statusbar_open_dialog )
                    {
                        pi = DOM.create('a', {'href' : "javascript:;", onmousedown : "return false;", title : ti, 'class' : 'mcePath_' + (de++), 'onclick' : 'return false;'}, na);
                        Event.add( pi, 'click', function(e){
                            var x = t.__getTagCommand( n );
                            if (x) ed.execCommand( x.cmd, n || false, x.val );
                        });
                    }
                    else
                    {
                        pi = DOM.create('a', {'href' : "javascript:;", onmousedown : "return false;", title : ti, 'class' : 'mcePath_' + (de++)}, na);
                    }

                    if (p.getElementsByTagName('a').length) {
                        p.insertBefore(DOM.doc.createTextNode(' \u00bb '), p.firstChild);
                        p.insertBefore(pi, p.firstChild);
                    } else if ( p.firstChild ) // &nbsp;
                        p.insertBefore(pi, p.firstChild);
                    else
                        p.appendChild(pi);
                }, ed.getBody());
            }
        },

        // Commands gets called by execCommand
        // eZ: Use custom dialog function to load eZ specific dialogs

        _sel : function(v) {
            this.editor.execCommand('mceSelectNodeDepth', false, v);
        },

        _mceInsertAnchor : function(ui, v) {
            var ed = this.editor, n = ed.selection.getNode();
            if ( ui.nodeName !== 'A' && (n = this.__getParentByTag( n, 'a', '', '', true )) && !DOM.getAttrib(n, 'href') )
                ui = n;
            this._generalXmlTagPopup( false, 'anchor', 0, 0, ui );
        },

        _mceCharMap : function() {
            var ed = this.editor;

            ed.windowManager.open({
                url : this.url + '/charmap.htm',
                width : 550 + parseInt(ed.getLang('advanced.charmap_delta_width', 0)),
                height : 250 + parseInt(ed.getLang('advanced.charmap_delta_height', 0)),
                inline : true
            }, {
                theme_url : this.url
            });
        },

        _mceHelp : function() {
            this._generalXmlTagPopup( '/dialog/', 'help', 480, 380 );
        },

        _mceColorPicker : function(u, v) {
            var ed = this.editor;

            v = v || {};

            ed.windowManager.open({
                url : this.url + '/color_picker.htm',
                width : 375 + parseInt(ed.getLang('advanced.colorpicker_delta_width', 0)),
                height : 250 + parseInt(ed.getLang('advanced.colorpicker_delta_height', 0)),
                close_previous : false,
                inline : true
            }, {
                input_color : v.color,
                func : v.func,
                theme_url : this.url
            });
        },

        _mceCodeEditor : function(ui, val) {
            var ed = this.editor;

            ed.windowManager.open({
                url : this.url + '/source_editor.htm',
                width : parseInt(ed.getParam("theme_advanced_source_editor_width", 720)),
                height : parseInt(ed.getParam("theme_advanced_source_editor_height", 580)),
                inline : true,
                resizable : true,
                maximizable : true
            }, {
                theme_url : this.url
            });
        },

        _mceImage : function(ui, val) {
            var ed = this.editor, e = ed.selection.getNode(), eurl = 'images/', type = '/upload/', el;

            if ( ui.nodeName === 'IMG' )
                e = ui;

            if (e !== null && e.nodeName === 'IMG')
            {
                type = '/relations/';
                eurl = 'auto/'; // need to set to auto in case this is attachment icon
                el = e;
                eurl += e.getAttribute('id') + '/' + e.getAttribute('inline') + '/' + e.getAttribute('alt');
            }
            this._generalXmlTagPopup( type, eurl, 500, 480, el )
        },

        _mceObject : function(ui, val)
        {
            var ed = this.editor, e = ed.selection.getNode(), eurl = 'objects/', type = '/upload/', el;

            if ( (ui.nodeName === 'DIV' || ui.nodeName === 'SPAN') && ui.className.indexOf('ezoeItemNonEditable') !== -1 )
                e = ui;

            if ( e = this.__getParentByTag( e, 'div,span', 'ezoeItemNonEditable', '', true ) )
            {
                type = '/relations/';
                el = e;
                eurl += e.getAttribute('id') + '/' + e.getAttribute('inline') + '/' + e.getAttribute('alt');
            }
            this._generalXmlTagPopup( type, eurl, 500, 480, el );
        },

        _mceFile : function(ui, val)
        {
            var ed = this.editor, e = ed.selection.getNode(), eurl = 'files/', type = '/upload/', el;

            if ( (ui.nodeName === 'DIV' || ui.nodeName === 'SPAN') && ui.className.indexOf('ezoeItemNonEditable') !== -1 )
                e = ui;

            if ( e = this.__getParentByTag( e, 'div,span', 'ezoeItemNonEditable', '', true ) )
            {
                type = '/relations/';
                el = e;
                eurl += e.getAttribute('id') + '/' + e.getAttribute('inline') + '/' + e.getAttribute('alt');
            }
            this._generalXmlTagPopup( type, eurl, 500, 480, el );
        },

        __mceJustifyTags : /^(TABLE|TD|TH|P|IMG|DIV|SPAN|H1|H2|H3|H4|H5|H6)$/i,
        __mceJustifyBlockTags : /^(TABLE|IMG|DIV)$/i,

        _JustifyLeft : function( v ){
            return this.__mceJustify( 'left', v );
        },

        _JustifyCenter : function( v ){
            return this.__mceJustify( 'center', v );
        },

        _JustifyRight : function( v ){
            return this.__mceJustify( 'right', v );
        },

        _JustifyFull : function( v ){
            return this.__mceJustify( 'justify', v );
        },

        __mceJustify : function(c, v)
        {
            // override the tinymce justify code to use html alignment
            var ed = this.editor, se = ed.selection, n = se.getNode(), nn = n.nodeName, p = false;

            if ( c === 'center' && nn === 'IMG' )
                c = 'middle';

            // block tags do not support justify alignment
            if ( c !== 'justify' || !this.__mceJustifyBlockTags.test( nn ) )
            {
                p = this.__mceJustifyTags.test( nn );
    
                if ( !p )
                {
                    if ( p = this.__mceJustifyTags.test( n.parentNode.nodeName ) )
                        n = n.parentNode;
                }
            }

            if ( p )
            {
                if ( n.align === c )
                    ed.dom.setAttrib( n, 'align', '' );
                else
                    ed.dom.setAttrib( n, 'align', c );
            }
            return false;
        },

        _mcePageBreak : function( ui, val )
        {
            var ed = this.editor, n = ed.selection.getNode();
            if ( n.nodeName === 'P' && n.parentNode.nodeName === 'BODY' )
            {
                // extra paragraph required after the div to be able to write content after it
                ed.execCommand('mceInsertRawHTML', false, '</p><div type="custom" class="ezoeItemCustomTag pagebreak"><p>pagebreak</p></div><p>' + (tinymce.isIE ? '&nbsp;' : '<br _mce_bogus="1" />') + '</p><p>');
            }
            else if ( n.nodeName === 'BODY' )
                ed.execCommand ('mceInsertRawHTML', false, '<div type="custom" class="ezoeItemCustomTag pagebreak"><p>pagebreak</p></div>');
            else
                alert( 'Not a suported location for a pagebreak, place it in the root of your document!' );
        },

        _mceCustom : function(ui, v)
        {
            this._generalXmlTagPopup( false, 'custom/' + v, 0, 0, ui );
        },

        _mceLiteral : function(ui, v)
        {
            this._generalXmlTagPopup( false, 'literal', 0, 0, ui );
        },

        _mceLink : function(ui, val) {
            var ed = this.editor, n = ed.selection.getNode();
            if ( ui.nodeName !== 'A' && (n = this.__getParentByTag( n, 'a', '', '', true )) && DOM.getAttrib(n, 'href') )
                ui = n;
            this._generalXmlTagPopup( false, 'link', 0, 360, ui );
        },

        _mceNewDocument : function() {
            var ed = this.editor;

            ed.windowManager.confirm('advanced.newdocument', function(s) {
                if (s)
                    ed.execCommand('mceSetContent', false, '');
            });
        },

        _mceForeColor : function() {
            var t = this;

            this._mceColorPicker(0, {
                color: t.fgColor,
                func : function(co) {
                    t.fgColor = co;
                    t.editor.execCommand('ForeColor', false, co);
                }
            });
        },

        _mceBackColor : function() {
            var t = this;

            this._mceColorPicker(0, {
                color: t.bgColor,
                func : function(co) {
                    t.bgColor = co;
                    t.editor.execCommand('HiliteColor', false, co);
                }
            });
        },

        /**
         * eZ: Custom TinyMCE OE command for disabling editor
         */
        _mceDisableEditor : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'CustomActionButton[' + ed.settings.ez_attribute_id + '_disable_editor]' );
        },

        /**
         * eZ: Custom TinyMCE OE command for discarding draft
         */
        _mceDiscard : function()
        {
            this.__appendHiddenInputAndSubmit( 'DiscardButton' );
        },

        /**
         * eZ: Custom TinyMCE OE command for storing draft
         */
        _mceStoreDraft : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'StoreButton' );
        },

        /**
         * eZ: Custom TinyMCE OE command for publishing draft
         */
        _mcePublishDraft : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'PublishButton' );
        },

        /**
         * Reusable function for sending form with custom form data
         * 
         * @param string name Name of custom form element 
         * @param string value Value of custom form element 
         */
        __appendHiddenInputAndSubmit : function( name, value )
        {
            var ed = this.editor, inp, formObj = tinymce.DOM.get(ed.id).form || tinymce.DOM.getParent(ed.id, 'form');

            if ( formObj )
            {
                inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = name;
                inp.value = value || 'hidden value';
                formObj.appendChild( inp );

                if (formObj.onsubmit == null || formObj.onsubmit() != false)
                    formObj.submit();

                ed.nodeChanged();
            } else
                ed.windowManager.alert("Error: No form element found.");
        },
        
        /**
         * eZ: Custom function for getting parent element that matches parameters
         * 
         * @param node n
         * @param string tag
         * @param string className
         * @param string type (checks type attribute on node if set)
         * @param bool checkElement Check n as well as parents if true
         */
        __getParentByTag: function( n, tag, className, type, checkElement )
        {
            if ( className ) className = ' ' + className + ' ';
            tag = ',' + tag.toUpperCase() + ',';
            while ( n && n.nodeName !== undefined && n.nodeName !== 'BODY' )
            {
                if ( checkElement && tag.indexOf( ',' + n.nodeName + ',' ) !== -1
                && ( !className || (' ' + n.className + ' ').indexOf( className ) !== -1 ) 
                && ( !type || n.getAttribute('type') === type ) )
                {
                    return n;
                }
                n = n.parentNode;
                checkElement = true;
            }
            return false;
        },

        /**
         * eZ: Blocks most events when ezoeItemNonEditable element is selected
         * activated by {@link this.__setDisabled()}
         * 
         * @param object ed Editor object
         * @param object e Event object
         */
        __block : function(ed, e) {

            if ( this.__disabled === false )
                return true;

            //console.log( 'ezoeItemNonEditable __block()' );
            e = e || window.event;            
            var k = e.which || e.keyCode;

            // Don't block arrow keys, page up/down, and F1-F12
            if ((k > 32 && k < 41) || (k > 111 && k < 124))
                return true;

            if ( k === 8 || k === 46 )// Remove embed tag if user clicks del or backspace
            {
                var n = this.__getParentByTag( ed.selection.getNode(), 'DIV,SPAN', 'ezoeItemNonEditable', '', true );
                if ( n !== undefined && n.parentNode&& n.parentNode.removeChild !== undefined )
                {
                    // Avoid that several embed tags are removed at once if they are placed side by side
                    if ( !this.__recursion )
                    {
                        this.__recursion = true;
                        n.parentNode.removeChild( n );
                        setTimeout(BIND( function(){ this.__recursion = false; }, this ), 50);
                        ed.nodeChanged();
                    }
                }
                else return true;
            }
            else if ( k === 13 && !this.__recursion )// user clicks enter, create paragraph after embed block
            {
                var n = this.__getParentByTag( ed.selection.getNode(), 'DIV', 'ezoeItemNonEditable', '', true );
                if ( n !== undefined && n.parentNode )
                {
                    var newNode, pos;
                    this.__recursion = true;
                    if ( n.parentNode.nodeName.toLowerCase() === 'li' )
                    {
                        newNode = ed.dom.create('li', false, tinymce.isIE ? '&nbsp;' : '<br _mce_bogus="1" />' );
                        pos = n.parentNode;
                    }
                    else
                    {
                        newNode = ed.dom.create('p', false, tinymce.isIE ? '&nbsp;' : '<br />' );
                        pos = n;
                    }
                    newNode = ed.dom.insertAfter( newNode, pos );
                    setTimeout(BIND( function(){ this.__recursion = false; }, this ), 150);
                    ed.nodeChanged();
                    ed.selection.select( newNode, true );
                }
            }
            else if ( k === 32 && !this.__recursion )// user clicks space, create space after embed inline
            {
                var n = this.__getParentByTag( ed.selection.getNode(), 'SPAN', 'ezoeItemNonEditable', '', true );
                if ( n !== undefined && n.parentNode )
                {
                    this.__recursion = true;
                    /* This gets tricky, basically it doesn't work to select a text node, carret will end up being inside
                     * relation span. So we create a span that will not show up in path and will be stripped by parser.
                     * Furthermore we cleanup the &nbsp; during save steps and change it to whitespace.
                     */
                    var newNode = ed.dom.create('span', {'class': 'mceItemHidden ezoeItemTempSpan'}, '&nbsp;' );
                    ed.dom.insertAfter( newNode, n );
                    ed.selection.select( newNode );
                    ed.selection.collapse( false );
                    setTimeout(BIND( function(){ this.__recursion = false; }, this ), 150);
                    ed.nodeChanged();
                }
            }
            return Event.cancel(e);
        },

        /**
         * eZ: Disables/enables all buttons based on s parameter, where s is true if
         * ezoeItemNonEditable element (embed objects) is selected. 
         * 
         * @param bool s
         */
        __setDisabled : function( s )
        {
            var t = this, ed = t.editor;

            tinymce.each(ed.controlManager.controls, function(c){
                if ( !c.settings.ezPlugin // define this as true to be able to avoid this forced disable fn
                 &&( !c.settings.cmd || ',mceObject,mceFile,mceFullScreen,mceLink,unlink,JustifyLeft,JustifyCenter,JustifyRight,'.indexOf( ',' + c.settings.cmd + ',' ) === -1 ) )
                {
                    c.setDisabled( s );
                    if ( s ) c.setActive( false );
                }
            });

            if ( s !== t.__disabled )
            {
                if ( t.__disabled === undefined )
                {
                    ed.onKeyDown.addToTop( BIND( t.__block, t ) );
                    ed.onKeyPress.addToTop( BIND( t.__block, t ) );
                    ed.onKeyUp.addToTop( BIND( t.__block, t ) );
                    ed.onPaste.addToTop( BIND( t.__block, t ) );
                }
                t.__disabled = s;
            }
        },

        /**
         * eZ: Hash for simple tag name to ezxml name mappings
         */
        __simpleTagsToXmlHash:
        {
            'P' : 'paragraph',
            'I' : 'emphasize',
            'EM': 'emphasize',
            'B' : 'strong',
            'STRONG' : 'strong',
            'PRE': 'literal',
            'U': 'custom',
            'SUB': 'custom',
            'SUP': 'custom',
            'H1': 'header',
            'H2': 'header',
            'H3': 'header',
            'H4': 'header',
            'H5': 'header',
            'H6': 'header',
            'TABLE': 'table',
            'TH': 'th',
            'TD': 'td',
            'TR': 'tr',
            'UL': 'ul',
            'OL': 'ol',
            'LI': 'li'
        },

        /**
         * eZ: Maps tag name to ezxmltext tag name, uses simple mapping hash if tag name exists there.
         * 
         * @param node n
         */
        __tagsToXml : function( n )
        {
            if ( this.__simpleTagsToXmlHash[ n.nodeName ] )
                return this.__simpleTagsToXmlHash[ n.nodeName ];
            switch( n.nodeName )
            {
                case 'A':
                    return DOM.getAttrib(n, 'href') ? 'link' : 'anchor';
                case 'DIV':
                    if ( n.className.indexOf('ezoeItemNonEditable') !== -1 )
                        return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return 'custom';
                    break;
                case 'SPAN':
                    if ( n.className.indexOf('ezoeItemNonEditable') !== -1 )
                        return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return 'custom';
                    else if ( n.style.textDecoration === 'underline' )
                        return 'custom';
                    break;
                case 'IMG':
                    if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return 'custom';
                    else
                        return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
                    break;
            }
            return false;
        },

        /**
         * eZ: Get related TinyMCE (OE custom) command based on node
         * 
         * @param node n
         */
        __getTagCommand : function( n )
        {
            switch( n.nodeName )
            {
                case 'IMG':
                    if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return {'cmd':'mceCustom', 'val': n.className.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|ezoeItem\w+|mceVisualAid)/g, '').replace(/^\s+|\s+$/g,'') };
                    else
                        return {'cmd':'mceImage', 'val': ''};
                case 'PRE':
                    return {'cmd':'mceLiteral', 'val': ''};
                case 'U':
                    return {'cmd':'mceCustom', 'val': 'underline'};
                case 'SUB':
                    return {'cmd':'mceCustom', 'val': 'sub'};
                case 'SUP':
                    return {'cmd':'mceCustom', 'val': 'sup'};
                case 'DIV':
                case 'SPAN':
                    if ( n.className.indexOf('ezoeItemNonEditable') !== -1 )
                    {
                        if ( n.className.indexOf('ezoeItemContentTypeFiles') !== -1 )
                            return {'cmd':'mceFile', 'val': ''};
                        return {'cmd':'mceObject', 'val': ''};
                    }
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return {'cmd':'mceCustom', 'val': n.className.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|ezoeItem\w+|mceVisualAid)/g, '').replace(/^\s+|\s+$/g,'') };
                    else if ( n.style.textDecoration === 'underline' )
                        return {'cmd':'mceCustom', 'val': 'underline' };
                    break;
                case 'TABLE':
                    return {'cmd':'mceInsertTable', 'val': ''};
                case 'TR':
                    return {'cmd':'mceTableRowProps', 'val': ''};
                case 'TD':
                case 'TH':
                    return {'cmd':'mceTableCellProps', 'val': ''};
                case 'A':
                    if ( DOM.getAttrib(n, 'href') ) return {'cmd':'mceLink', 'val': ''};
                    else return {'cmd':'mceInsertAnchor', 'val': ''};
                default:
                    var tagName = this.__tagsToXml( n );
                    if ( tagName ) return {'cmd':'generalXmlTagPopup', 'val': tagName + '/' + n.nodeName};
            }
        },

        /**
         * eZ: General popup function for tag dialogs
         */
        _generalXmlTagPopup : function( view, eurl, width, height, node )
        {
            var ed = this.editor, s = ed.settings;
            if ( view && view.nodeName ) node = view;
            if ( !view || view.nodeName ) view = '/tags/';

            ed.windowManager.open({
                url : s.ez_extension_url + view  + s.ez_contentobject_id + '/' + s.ez_contentobject_version + '/' + eurl,
                width : width || 400,
                height : height || 320,
                scrollbars : true,
                resizable : true,
                inline : true
            }, {
                theme_url : this.url,
                selected_node : ( node && node.nodeName ? node : false )
            });
        },

        _ufirst : function(s) {
            return s.substring(0, 1).toUpperCase() + s.substring(1);
        }
    });

    tinymce.ThemeManager.add('ez', tinymce.themes.eZTheme);
}(tinymce));
