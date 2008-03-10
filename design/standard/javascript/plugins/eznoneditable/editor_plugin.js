/**
 * $Id: editor_plugin_src.js 372 2007-11-11 18:38:50Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */
 
 /* 
 'noneditable' plugin forked for eZ TinyMCE Editor integration
*/

(function() {
	var Event = tinymce.dom.Event;

	tinymce.create('tinymce.plugins.ezNonEditablePlugin', {
		init : function(ed, url) {
			var t = this, editClass, nonEditClass, returnFalse;

			t.editor            = ed;
			editClass           = ed.getParam("noneditable_editable_class", "mceEditable");
			nonEditClass        = ed.getParam("noneditable_noneditable_class", "mceNonEditable");
			nonEditActiveButton = ed.getParam("noneditable_active_button", "");
			t._edit_cmd         = ',' + ed.getParam("noneditable_editable_cmd", "") + ',';

			ed.onNodeChange.addToTop(function(ed, cm, n) {
				var sc, ec, en, e = n;

				// Block if start or end is inside a non editable element
				sc = ed.dom.getParent(ed.selection.getStart(), function(n) {
					return ed.dom.hasClass(n, nonEditClass);
				});

				ec = ed.dom.getParent(ed.selection.getEnd(), function(n) {
					return ed.dom.hasClass(n, nonEditClass);
				});
				
				en = ed.dom.getParent(ed.selection.getNode(), function(n) {
                    return ed.dom.hasClass(n, nonEditClass);
                });

				// Block or unblock
				if (  sc || ec  )
				{
                    while (  e !== null && e.nodeName !== undefined && e.nodeName !== 'BODY' )
                    {
                        if ( e.nodeName === 'DIV' && e.className.indexOf('mceNonEditable') !== -1 )
                        {
                            ed.selection.select( e );
                            break;
                        }
                        e = e.parentNode;
                    }
                    t._setDisabled(1);
                    return false;
				}
				else if ( en )
				{
					t._setDisabled(1);
					if ( nonEditActiveButton ) cm.setActive(nonEditActiveButton, true );
				}
				else
				{
					t._setDisabled(0);
			    }
			});
		},

		getInfo : function() {
			return {
				longname : 'Non editable elements',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/noneditable',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		},

		_block : function(ed, e) {
			return Event.cancel(e);
		},

		_setDisabled : function(s) {
			var t = this, ed = t.editor;

			tinymce.each(ed.controlManager.controls, function(c){
			    if ( !c.settings.cmd || t._edit_cmd.indexOf( ',' + c.settings.cmd + ',' ) === -1 )
				    c.setDisabled(s);
			});

			if (s !== t.disabled) {
				if (s) {
					ed.onKeyDown.addToTop(t._block);
					ed.onKeyPress.addToTop(t._block);
					ed.onKeyUp.addToTop(t._block);
					ed.onPaste.addToTop(t._block);
				} else {
					ed.onKeyDown.remove(t._block);
					ed.onKeyPress.remove(t._block);
					ed.onKeyUp.remove(t._block);
					ed.onPaste.remove(t._block);
				}
				t.disabled = s;
			}
		}
	});

	// Register plugin
	tinymce.PluginManager.add('eznoneditable', tinymce.plugins.ezNonEditablePlugin);
})();