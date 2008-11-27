/**
 * $Id: editor_template_src.js 925 2008-09-11 11:25:26Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

/* 
 * ez theme is a fork of advance theme modified for eZ Online Editor MCE integration
*/

(function() {
    var DOM = tinymce.DOM, Event = tinymce.dom.Event, extend = tinymce.extend, each = tinymce.each, Cookie = tinymce.util.Cookie, lastExtID, explode = tinymce.explode;

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
            object : ['object_desc', 'mceObject'],
            file : ['file_desc', 'mceFile'],
            custom : ['custom_desc', 'mceCustom'],
            literal : ['literal_desc', 'mceLiteral'],
            pagebreak : ['pagebreak_desc', 'mcePageBreak'],
            disable : ['disable_desc', 'mceDisableEditor'],
            store : ['store_desc', 'mceStoreDraft'],
            publish : ['publish_desc', 'mcePublishDraft'],
            discard : ['discard_desc', 'mceDiscard'],
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
                theme_advanced_blockformats : "p,pre,h1,h2,h3,h4,h5,h6",
                theme_advanced_toolbar_align : "center",
                theme_advanced_fonts : "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
                theme_advanced_more_colors : 1,
                theme_advanced_row_height : 23,
                theme_advanced_resize_horizontal : 1,
                theme_advanced_resizing_use_cookie : 1,
                theme_advanced_font_sizes : "1,2,3,4,5,6,7",
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

                        if (ed.settings.convert_fonts_to_spans) {
                            cl = s.font_size_classes[v - 1];
                            v = s.font_size_style_values[v - 1] || (t.sizes[v - 1] + 'pt');
                        }
                    }

                    if (/\s*\./.test(v))
                        cl = v.replace(/\./g, '');

                    o[k] = cl ? {'class' : cl} : {fontSize : v};
                });

                s.theme_advanced_font_sizes = o;
            }

            if ((v = s.theme_advanced_path_location) && v !== 'none')
                s.theme_advanced_statusbar_location = s.theme_advanced_path_location;

            if (s.theme_advanced_statusbar_location === 'none')
                s.theme_advanced_statusbar_location = 0;

            // Init editor
            ed.onInit.add(function() {
                ed.onNodeChange.add(t._nodeChanged, t);
                ed.addShortcut('ctrl+8', '', ['FormatBlock', false, '<pre>']);
                ed.addShortcut('ctrl+9', '', ['FormatBlock', false, '<pre>']);
                if ( s.theme_ez_content_css )
                {
                    var css_arr = s.theme_ez_content_css.split(',');
                    for ( var ind = 0, len = css_arr.length; ind < len; ind++ )
                       ed.dom.loadCSS( css_arr[ind] );
                }
                else if (ed.settings.content_css !== false)
                    ed.dom.loadCSS(ed.baseURI.toAbsolute("themes/ez/skins/" + ed.settings.skin + "/content.css"));
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

            ed.addShortcut('ctrl+s', ed.getLang('save.save_desc'), 'mceStoreDraft');
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
            var ed = this.editor, c = ed.controlManager.get('styleselect');

            if (c.getLength() == 0) {
                each(ed.dom.getClasses(), function(o) {
                    c.add(o['class'], o['class']);
                });
            }
        },

        _createStyleSelect : function(n) {
            var t = this, ed = t.editor, cf = ed.controlManager, c = cf.createListBox('styleselect', {
                title : 'advanced.style_select',
                onselect : function(v) {
                    if (c.selectedValue === v) {
                        ed.execCommand('mceSetStyleInfo', 0, {command : 'removeformat'});
                        c.select();
                        return false;
                    } else
                        ed.execCommand('mceSetCSSClass', 0, v);
                }
            });

            if ( c ) {
                each(ed.getParam('theme_advanced_styles', '', 'hash'), function(v, k) {
                    if (v)
                        c.add(t.editor.translate(k), v);
                });
    
                c.onPostRender.add(function(ed, n) {
                    if (!c.NativeListBox) {
                        Event.add(n.id + '_text', 'focus', t._importClasses, t);
                        Event.add(n.id + '_text', 'mousedown', t._importClasses, t);
                        Event.add(n.id + '_open', 'focus', t._importClasses, t);
                        Event.add(n.id + '_open', 'mousedown', t._importClasses, t);
                    } else
                        Event.add(n.id, 'focus', t._importClasses, t);
                });
            }

            return c;
        },

        _createFontSelect : function() {
            var c, t = this, ed = t.editor;

            c = ed.controlManager.createListBox('fontselect', {title : 'advanced.fontdefault', cmd : 'FontName'});
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
                if (v.fontSize)
                    ed.execCommand('FontSize', false, v.fontSize);
                else {
                    each(t.settings.theme_advanced_font_sizes, function(v, k) {
                        if (v['class'])
                            cl.push(v['class']);
                    });

                    ed.editorCommands._applyInlineStyle('span', {'class' : v['class']}, {check_classes : cl});
                }
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
            if ( c ) {
                each(explode(t.settings.theme_advanced_blockformats), function(v) {
                    c.add(t.editor.translate(fmts[v]), v, {'class' : 'mce_formatPreview mce_' + v});
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
            nl = DOM.stdMode ? DOM.select('tr', tb) : sc.rows; // Quick fix for IE 8
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

            if (!ed.getParam('accessibility_focus') || ed.getParam('tab_focus'))
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

        resizeTo : function(w, h) {
            var ed = this.editor, s = ed.settings, e = DOM.get(ed.id + '_tbl'), ifr = DOM.get(ed.id + '_ifr'), dh;

            // Boundery fix box
            w = Math.max(s.theme_advanced_resizing_min_width || 100, w);
            h = Math.max(s.theme_advanced_resizing_min_height || 100, h);
            w = Math.min(s.theme_advanced_resizing_max_width || 0xFFFF, w);
            h = Math.min(s.theme_advanced_resizing_max_height || 0xFFFF, h);

            // Calc difference between iframe and container
            dh = e.clientHeight - ifr.clientHeight;

            // Resize iframe and container
            DOM.setStyle(ifr, 'height', h - dh);
            DOM.setStyles(e, {width : w, height : h});
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
            if (lo === 'top')
                t._addToolbars(tb, o);

            // Create external toolbar
            if (lo === 'external') {
                n = c = DOM.create('div', {style : 'position:relative'});
                n = DOM.add(n, 'div', {id : ed.id + '_external', 'class' : 'mceExternalToolbar'});
                DOM.add(n, 'a', {id : ed.id + '_external_close', href : 'javascript:;', 'class' : 'mceExternalClose'});
                n = DOM.add(n, 'table', {id : ed.id + '_tblext', cellSpacing : 0, cellPadding : 0});
                etb = DOM.add(n, 'tbody');

                if (p.firstChild.className === 'mceOldBoxModel')
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

            if (sl === 'top')
                t._addStatusBar(tb, o);

            // Create iframe container
            if (!s.theme_advanced_toolbar_container) {
                n = DOM.add(tb, 'tr');
                n = ic = DOM.add(n, 'td', {'class' : 'mceIframeContainer'});
            }

            // Create toolbar container at bottom
            if (lo === 'bottom')
                t._addToolbars(tb, o);

            if (sl === 'bottom')
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
                if (n === 'tablecontrols') {
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

            if (!ed.getParam('accessibility_focus') || ed.getParam('tab_focus'))
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
                if ( s.theme_advanced_toolbar_floating )
                    h.push( t._toolbarRenderFlowHTML.call( tb ) );
                else
                    h.push( tb.renderHTML() );

                o.deltaHeight -= s.theme_advanced_row_height;
            }

            h.push(DOM.createHTML('a', {href : '#', accesskey : 'z', title : ed.getLang("advanced.toolbar_focus"), onfocus : 'tinyMCE.getInstanceById(\'' + ed.id + '\').focus();'}, '<!-- IE -->'));
            DOM.setHTML(n, h.join(''));
        },
        
        // Custom toolbar renderer for ez theme
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
            else if ( tinymce.isOpera && ez.num( navigator.appVersion ) < 9.30 ) c2 += ' mceToolBarFlowIEbug';

            return DOM.createHTML('div', {id : t.id, 'class' : c2 + (s['class'] ? ' ' + s['class'] : ''), align : t.settings.align || ''},  h );
        },

        _addStatusBar : function(tb, o) {
            var n, t = this, ed = t.editor, s = t.settings, r, mf, me, td;

            n = DOM.add(tb, 'tr');
            n = td = DOM.add(n, 'td', {'class' : 'mceStatusbar'});
            n = DOM.add(n, 'div', {id : ed.id + '_path_row'}, s.theme_advanced_path ? ed.translate('advanced.path') + ': ' : '&nbsp;');
            DOM.add(n, 'a', {href : '#', accesskey : 'x'});

            if (s.theme_advanced_resizing && !tinymce.isOldWebKit) {
                DOM.add(td, 'a', {id : ed.id + '_resize', href : 'javascript:;', onclick : "return false;", 'class' : 'mceResize'});

                if (s.theme_advanced_resizing_use_cookie) {
                    ed.onPostRender.add(function() {
                        var o = Cookie.getHash("TinyMCE_" + ed.id + "_size"), c = DOM.get(ed.id + '_tbl');

                        if (!o)
                            return;

                        if (s.theme_advanced_resize_horizontal)
                            c.style.width = Math.max(10, o.cw) + 'px';

                        c.style.height = Math.max(10, o.ch) + 'px';
                        DOM.get(ed.id + '_ifr').style.height = Math.max(10, parseInt(o.ch) + t.deltaHeight) + 'px';
                    });
                }

                ed.onPostRender.add(function() {
                    Event.add(ed.id + '_resize', 'mousedown', function(e) {
                        var c, p, w, h, n, pa;

                        // Measure container
                        c = DOM.get(ed.id + '_tbl');
                        w = c.clientWidth;
                        h = c.clientHeight;

                        miw = s.theme_advanced_resizing_min_width || 100;
                        mih = s.theme_advanced_resizing_min_height || 100;
                        maw = s.theme_advanced_resizing_max_width || 0xFFFF;
                        mah = s.theme_advanced_resizing_max_height || 0xFFFF;

                        // Setup placeholder
                        p = DOM.add(DOM.get(ed.id + '_parent'), 'div', {'class' : 'mcePlaceHolder'});
                        DOM.setStyles(p, {width : w, height : h});

                        // Replace with placeholder
                        DOM.hide(c);
                        DOM.show(p);

                        // Create internal resize obj
                        r = {
                            x : e.screenX,
                            y : e.screenY,
                            w : w,
                            h : h,
                            dx : null,
                            dy : null
                        };

                        // Start listening
                        mf = Event.add(DOM.doc, 'mousemove', function(e) {
                            var w, h;

                            // Calc delta values
                            r.dx = e.screenX - r.x;
                            r.dy = e.screenY - r.y;

                            // Boundery fix box
                            w = Math.max(miw, r.w + r.dx);
                            h = Math.max(mih, r.h + r.dy);
                            w = Math.min(maw, w);
                            h = Math.min(mah, h);

                            // Resize placeholder
                            if (s.theme_advanced_resize_horizontal)
                                p.style.width = w + 'px';

                            p.style.height = h + 'px';

                            return Event.cancel(e);
                        });

                        me = Event.add(DOM.doc, 'mouseup', function(e) {
                            var ifr;

                            // Stop listening
                            Event.remove(DOM.doc, 'mousemove', mf);
                            Event.remove(DOM.doc, 'mouseup', me);

                            c.style.display = '';
                            DOM.remove(p);

                            if (r.dx === null)
                                return;

                            ifr = DOM.get(ed.id + '_ifr');

                            if (s.theme_advanced_resize_horizontal)
                                c.style.width = Math.max(10, r.w + r.dx) + 'px';

                            c.style.height = Math.max(10, r.h + r.dy) + 'px';
                            ifr.style.height = Math.max(10, ifr.clientHeight + r.dy) + 'px';

                            if (s.theme_advanced_resizing_use_cookie) {
                                Cookie.setHash("TinyMCE_" + ed.id + "_size", {
                                    cw : r.w + r.dx,
                                    ch : r.h + r.dy
                                });
                            }
                        });

                        return Event.cancel(e);
                    });
                });
            }

            o.deltaHeight -= 21;
            n = tb = null;
        },

        _nodeChanged : function(ed, cm, n, co) {
            var t = this, p, de = 0, v, c, c2, s = t.settings, mceNonEditable = false, div = false, header, type = '';

            if (s.readonly)
                return;

            tinymce.each(t.stateControls, function(c) {
                cm.setActive(c, ed.queryCommandState(t.controls[c][1]));
            });

            header = DOM.getParent(n, 'H1,H2,H3,H4,H5,H6');
            p = DOM.getParent(n, 'DIV,SPAN');
            c = cm.get('object');
            c2 = cm.get('file')
            if ( c || c2 )
            {
                if ( ( p && (p.nodeName === 'DIV' || p.nodeName === 'SPAN') && p.className.indexOf('mceNonEditable') !== -1 )
                   || (p = t.__getParentByTag( n, 'div,span', 'mceNonEditable') ) )
                {
                    ed.selection.select( p );
                    mceNonEditable = true;
                    div = p.nodeName === 'DIV';
                    n = p;
                    type = p.className.indexOf('mceItemContentTypeFiles') !== -1 ? 'files' : 'objects';
                }
                if ( c )
                {
                    c.setActive( mceNonEditable && (type === 'objects')  );
                    c.setDisabled( header || (mceNonEditable && type !== 'objects') );
                }
                if ( c2 )
                {
                    c2.setActive( mceNonEditable && (type === 'files')  );
                    c2.setDisabled( header || (mceNonEditable && type !== 'files') );
                }
            }

            t.__setDisabled( mceNonEditable );

            cm.setDisabled('undo', !ed.undoManager.hasUndo() && !ed.typing);
            cm.setDisabled('redo', !ed.undoManager.hasRedo());

            p = DOM.getParent(n, 'A');
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

            if ( mceNonEditable === false )
            {
                if ( c = cm.get('anchor') )
                {
                    c.setActive( !!p && DOM.getAttrib(p, 'id') && !DOM.getAttrib(p, 'href') );
    
                    if (tinymce.isWebKit)
                    {
                        p = DOM.getParent(n, 'IMG');
                        c.setActive(!!p && DOM.getAttrib(p, 'mce_name') === 'a');
                    }
                }
                
                p = header || DOM.getParent(n, 'DIV');
                if (p && (c = cm.get('pagebreak')))
                    c.setDisabled( !!p && DOM.hasClass(p, 'pagebreak') );

                if (header && (c = cm.get('custom')))
                    c.setDisabled( header );

                if (header && (c = cm.get('bullist')))
                    c.setDisabled( header );

                if (header && (c = cm.get('numlist')))
                    c.setDisabled( header );

                p = DOM.getParent(n, 'UL,OL');
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

                p = DOM.getParent(n, 'IMG');
                if (c = cm.get('image'))
                {
                    c.setActive(!!p && p.className.indexOf('mceItem') === -1);
                    c.setDisabled( header );
                }

                p = DOM.getParent(n, 'PRE');
                if (c = cm.get('literal'))
                {
                    c.setActive(!!p );
                    c.setDisabled( header );
                }

                if (c = cm.get('formatselect'))
                {
                    p = DOM.getParent(n, DOM.isBlock);
    
                    if ( !p || p.className === 'mceItemHidden' || p.nodeName === 'LI' || p.nodeName === 'UL' || p.nodeName === 'OL' )
                        c.setDisabled( true );
                    else if ( p )
                        c.select(p.nodeName.toLowerCase());
                }
            }

            if (s.theme_advanced_path && s.theme_advanced_statusbar_location) {
                p = DOM.get(ed.id + '_path') || DOM.add(ed.id + '_path_row', 'span', {id : ed.id + '_path'});
                DOM.setHTML(p, '');

                ed.dom.getParent(n, function(n) {
                    var na = n.nodeName.toLowerCase(), u, pi, ti = '', className = false;

                    // Ignore non element and hidden elements
                    if ( n.nodeType !== 1 || n.nodeName === 'BR' || DOM.hasClass(n, 'mceItemHidden') || DOM.hasClass(n, 'mceItemRemoved') )
                        return;
                   // seems like hasClass has some issues in ie..
                   if ( DOM.getAttrib( n, 'class').indexOf('mceItemHidden') !== -1 )
                       return;

                    if ( v = t.__tagsToXml( n ) )
                        na = v;

                    // Fake name
                    if (v = DOM.getAttrib(n, 'mce_name'))
                        na = v;

                    // Handle prefix
                    if (tinymce.isIE && n.scopeName !== 'HTML')
                        na = n.scopeName + ':' + na;

                    // Remove internal prefix
                    na = na.replace(/mce\:/g, '');

                    // Handle node name
                    switch (na)
                    {
                        case 'tbody':
                        case 'thead':
                            return false;
                        case 'embed':
                        case 'embed-inline':
                            //if (v = DOM.getAttrib(n, 'src'))
                                //ti += 'src: ' + v + ' ';

                            break;
                        case 'anchor':
                        case 'link':
                            if (v = DOM.getAttrib(n, 'href'))
                                ti += 'href: ' + v + ' ';
                            else if (v = DOM.getAttrib(n, 'id'))
                                na += '#' + v;
                            break;
                        case 'custom':
                            if (v = DOM.getAttrib(n, 'style'))
                                ti += 'style: ' + v + ' ';
                            if ( n.nodeName === 'U' && n.className === '' )
                                className = 'underline';

                            break;
                        case 'header':
                            if (v = n.nodeName)
                                na += ' ' + v.charAt(1);

                            break;
                    }

                    if (v = DOM.getAttrib(n, 'id'))
                        ti = ti + 'id: ' + v + ' ';

                    if (v = className || n.className)
                    {
                        v = v.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|mceVisualAid|mceNonEditable)/g, '');

                        if ( v = ez.string.trim( v ) )
                        {
                            ti = ti + 'class: ' + v + ' ';
                            //if (na === 'embed' || na === 'custom' || DOM.isBlock(n))
                            na = na + '.' + v.replace(' ', '.');
                        }
                    }

                    na = na.replace(/(html:)/g, '');
                    na = {name : na, node : n, title : ti};
                    t.onResolveName.dispatch(t, na);
                    ti = na.title;
                    na = na.name;

                    //u = "javascript:tinymce.EditorManager.get('" + ed.id + "').theme._sel('" + (de++) + "');";
                    if ( s.theme_ez_statusbar_open_dialog )
                    {
                        pi = DOM.create('a', {'href' : "javascript:;", 'onmousedown' : 'return false;', title : ti, 'class' : 'mcePath_' + (de++), 'onclick' : 'return false;'}, na);
                        Event.add( pi, 'click', function(e){
                            var x = t.__getTagCommand( n, v );
                            if (x) ed.execCommand( x.cmd, n || false, x.c );
                        });
                    }
                    else
                    {
                        pi = DOM.create('a', {'href' : "javascript:;", 'onmousedown' : 'return false;', title : ti, 'class' : 'mcePath_' + (de++)}, na);
                    }

                    if (p.hasChildNodes()) {
                        p.insertBefore(DOM.doc.createTextNode(' \u00bb '), p.firstChild);
                        p.insertBefore(pi, p.firstChild);
                    } else
                        p.appendChild(pi);
                }, ed.getBody());
            }
        },

        __getParentByTag: function( n, tag, className, type, checkElement )
        {
            if ( className ) className = ' ' + className + ' ';
            tag = ',' + tag.toUpperCase() + ',';
            while ( n !== undefined && n.nodeName !== undefined && n.nodeName !== 'BODY' )
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

        __block : function(ed, e) {

            if ( this.__disabled === false )
                return;
            
            e = e || window.event;            
            var k = e.which || e.keyCode;

            // Don't block arrow keys, pg up/down, and F1-F12
            if ((k > 32 && k < 41) || (k > 111 && k < 124))
                return;

            // Remove embed tag if user clicks del or backspace
            if ( k === 8 || k === 46 )
            {
                var n = this.__getParentByTag( ed.selection.getNode(), 'DIV,SPAN', 'mceNonEditable', '', true );
                if ( n !== undefined && n.parentNode && n.parentNode.removeNode !== undefined )
                {
                    // Avoid that several embed tags are removed at once if they are placed side by side
                    if ( !this.__recursion )
                    {
                        this.__recursion = true;
                        n.parentNode.removeChild( n );
                        setTimeout(ez.fn.bind( function(){ this.__recursion = false; }, this ), 50);
                    }
                }
                else return;
            }
            return Event.cancel(e);
        },
        
        __setDisabled : function( s )
        {
            var t = this, ed = t.editor;

            tinymce.each(ed.controlManager.controls, function(c){
                if ( !c.settings.cmd || ',mceObject,mceFile,mceFullScreen,mceLink,unlink'.indexOf( ',' + c.settings.cmd + ',' ) === -1 )
                {
                    c.setDisabled( s );
                    if ( s ) c.setActive( false );
                }
            });

            if ( s !== t.__disabled )
            {
                if ( t.__disabled === undefined )
                {
                    ed.onKeyDown.addToTop( ez.fn.bind( t.__block, t ) );
                    ed.onKeyPress.addToTop( ez.fn.bind( t.__block, t ) );
                    ed.onKeyUp.addToTop( ez.fn.bind( t.__block, t ) );
                    ed.onPaste.addToTop( ez.fn.bind( t.__block, t ) );
                }
                t.__disabled = s;
            }
        },

        __simpleTagsToXmlHash:
        {
            'P' : 'paragraph',
            'I' : 'emphasize',
            'EM': 'emphasize',
            'B' : 'strong',
            'PRE': 'literal',
            'U': 'custom',
            'H1': 'header',
            'H2': 'header',
            'H3': 'header',
            'H4': 'header',
            'H5': 'header',
            'H6': 'header',
            'TABLE': 'table',
            /* these are aliases, make sure they are not used in __pickTagCommand() */
            'TH': 'table header',
            'TD': 'table cell',
            'TR': 'table row',
            'UL': 'unordered list',
            'OL': 'ordered list',
            'LI': 'list item'
        },
        
        __tagsToXml : function( n )
        {
            if ( this.__simpleTagsToXmlHash[ n.nodeName ] )
                return this.__simpleTagsToXmlHash[ n.nodeName ];
            switch( n.nodeName )
            {
                case 'A':
                    return DOM.getAttrib(n, 'href') ? 'link' : 'anchor';
                case 'DIV':
                    if ( n.className.indexOf('mceNonEditable') !== -1 )
                        return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return 'custom';
                    break;
                case 'SPAN':
                    if ( n.className.indexOf('mceNonEditable') !== -1 )
                        return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return 'custom';
                    break;
                case 'IMG':
                    return 'embed' + (DOM.getAttrib(n, 'inline') === 'true' ? '-inline' : '');
            }
            return false;
        },
        
        __getTagCommand : function( n, v )
        {
            switch( n.nodeName )
            {
                case 'IMG':
                    return {'cmd':'mceImage', 'c': v};
                case 'PRE':
                    return {'cmd':'mceLiteral', 'c': v};
                case 'U':
                    return {'cmd':'mceCustom', 'c': 'underline'};
                case 'DIV':
                    if ( n.className.indexOf('mceNonEditable') !== -1 )
                    {
                        if ( n.className.indexOf('mceItemContentTypeFiles') !== -1 )
                            return {'cmd':'mceFile', 'c': v};
                        return {'cmd':'mceObject', 'c': v};
                    }
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return {'cmd':'mceCustom', 'c': v};
                case 'SPAN':
                    if ( n.className.indexOf('mceNonEditable') !== -1 )
                    {
                        if ( n.className.indexOf('mceItemContentTypeFiles') !== -1 )
                            return {'cmd':'mceFile', 'c': v};
                        return {'cmd':'mceObject', 'c': v};
                    }
                    else if ( DOM.getAttrib(n, 'type') === 'custom' )
                        return {'cmd':'mceCustom', 'c': v};
                case 'TABLE':
                    return {'cmd':'mceInsertTable', 'c': v};
                case 'TR':
                    return {'cmd':'mceTableRowProps', 'c': v};
                case 'TD':
                case 'TH':
                    return {'cmd':'mceTableCellProps', 'c': v};
                case 'A':
                    if ( DOM.getAttrib(n, 'href') ) return {'cmd':'mceLink', 'c': v};
                    else return {'cmd':'mceInsertAnchor', 'c': v};
                case 'LI':
                case 'OL':
                case 'UL':
                    return {'cmd':'generalXmlTagPopup', 'c': n.nodeName.toLowerCase() + '/' + n.nodeName};
                default:
                    var tagName = this.__tagsToXml( n );
                    if ( tagName ) return {'cmd':'generalXmlTagPopup', 'c': tagName + '/' + n.nodeName};
            }
        },
        
        _generalXmlTagPopup : function( view, eurl, width, height, node )
        {
            var ed = this.editor, s = ed.settings;
            if ( view && view.nodeName ) node = view;
            if ( !view || view.nodeName ) view = '/tags/';

            ed.windowManager.open({
                url : s.ez_extension_url + view  + s.ez_contentobject_id + '/' + s.ez_contentobject_version + '/' + eurl,
                width : width || 400,
                height : height || 300,
                scrollbars : true,
                resizable : true,
                inline : true
            }, {
                theme_url : this.url,
                selected_node : ( node && node.nodeName ? node : false )
            });
        },

        // These commands gets called by execCommand

        _sel : function(v) {
            this.editor.execCommand('mceSelectNodeDepth', false, v);
        },

        _mceCharMap : function() {
            var ed = this.editor;

            ed.windowManager.open({
                url : tinymce.baseURL + '/themes/ez/charmap.htm',
                width : 550,
                height : 250,
                inline : true
            }, {
                theme_url : this.url
            });
        },

        _mceHelp : function()
        {
            this._generalXmlTagPopup( '/dialog/', 'help', 480, 380 );
        },

        _mceColorPicker : function(ui, v) {
            var ed = this.editor;

            v = v || {};

            ed.windowManager.open({
                url : tinymce.baseURL + '/themes/ez/color_picker.htm',
                width : 375 + parseInt(ed.getLang('ez.colorpicker_delta_width', 0)),
                height : 250 + parseInt(ed.getLang('ez.colorpicker_delta_height', 0)),
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
                url : tinymce.baseURL + '/themes/ez/source_editor.htm',
                width : parseInt(ed.getParam("theme_advanced_source_editor_width", 720)),
                height : parseInt(ed.getParam("theme_advanced_source_editor_height", 580)),
                inline : true,
                resizable : true,
                maximizable : true
            }, {
                theme_url : this.url
            });
        },

        _mceImage : function(ui, val)
        {
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

            if ( (ui.nodeName === 'DIV' || ui.nodeName === 'SPAN') && ui.className.indexOf('mceNonEditable') !== -1 )
                e = ui;

            if ( e = this.__getParentByTag( e, 'div,span', 'mceNonEditable', '', true ) )
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

            if ( (ui.nodeName === 'DIV' || ui.nodeName === 'SPAN') && ui.className.indexOf('mceNonEditable') !== -1 )
                e = ui;

            if ( e = this.__getParentByTag( e, 'div,span', 'mceNonEditable', '', true ) )
            {
                type = '/relations/';
                el = e;
                eurl += e.getAttribute('id') + '/' + e.getAttribute('inline') + '/' + e.getAttribute('alt');
            }
            this._generalXmlTagPopup( type, eurl, 500, 480, el );
        },

        _mcePageBreak : function( ui, val )
        {
            var ed = this.editor, n = ed.selection.getNode();
            if ( n.nodeName === 'P' && n.parentNode.nodeName === 'BODY' )
                ed.execCommand('mceInsertRawHTML', false, '</p><div type="custom" class="mceItemCustomTag pagebreak"><p>&nbsp;</p></div><p>');
            else if ( n.nodeName === 'BODY' )
                ed.execCommand ('mceInsertRawHTML', false, '<div type="custom" class="mceItemCustomTag pagebreak"><p>&nbsp;</p></div>');
            else
                alert( 'Not a suported location for a pagebreak, place it in the root of your document!' );
        },

        _mceInsertAnchor : function(ui, v)
        {
            var ed = this.editor, n = ed.selection.getNode();
            if ( ui.nodeName !== 'A' && (n = this.__getParentByTag( n, 'a', '', '', true )) && !DOM.getAttrib(n, 'href') )
                ui = n;
            this._generalXmlTagPopup( false, 'anchor', 0, 0, ui );
        },

        _mceCustom : function(ui, v)
        {
            this._generalXmlTagPopup( false, 'custom/' + v, 0, 0, ui );
        },

        _mceLiteral : function(ui, v)
        {
            this._generalXmlTagPopup( false, 'literal', 0, 0, ui );
        },

        _mceLink : function(ui, v)
        {
            var ed = this.editor, n = ed.selection.getNode();
            if ( ui.nodeName !== 'A' && (n = this.__getParentByTag( n, 'a', '', '', true )) && DOM.getAttrib(n, 'href') )
                ui = n;
            this._generalXmlTagPopup( false, 'link', 0, 360, ui );
        },

        _mceNewDocument : function() {
            var ed = this.editor;

            ed.windowManager.confirm('ez.newdocument', function(s) {
                if (s) ed.execCommand('mceSetContent', false, '');
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

        _mceDisableEditor : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'CustomActionButton[' + ed.settings.ez_attribute_id + '_disable_editor]' );
        },

        _mceDiscard : function()
        {
            this.__appendHiddenInputAndSubmit( 'DiscardButton' );
        },

        _mceStoreDraft : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'StoreButton' );
        },

        _mcePublishDraft : function()
        {
            var ed = this.editor;
            tinyMCE.triggerSave();
            ed.isNotDirty = true;
            this.__appendHiddenInputAndSubmit( 'PublishButton' );
        },

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

        _ufirst : function(s) {
            return s.substring(0, 1).toUpperCase() + s.substring(1);
        }
    });

    tinymce.ThemeManager.add('ez', tinymce.themes.eZTheme);
}());