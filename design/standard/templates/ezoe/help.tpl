{set scope=global persistent_variable=hash('title', 'Help'|i18n('design/standard/ezoe'),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js',
                                                            'themes/ez/js/about.js'),
                                           'css', array('stylesheets/skins/o2k7/ui.css')
                                           )}
<script type="text/javascript">
<!--
    
{literal} 

tinyMCEPopup.onInit.add( function(){
    var slides = ez.$$('div.panel'), navigation = ez.$$('#tabs li.tab');
    slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
});


-->
</script>
{/literal}

<div class="help-view">
    <div id="tabs" class="tabs">
        <ul>
            <li class="tab"><span><a href="JavaScript:void(0);">{'Help'|i18n('design/standard/ezoe')}</a></span></li>
            <li class="tab"><span><a href="JavaScript:void(0);">{'Plugins'|i18n('design/standard/ezoe')}</a></span></li>
            <li class="tab"><span><a href="JavaScript:void(0);">{'About'|i18n('design/standard/ezoe')}</a></span></li>
        </ul>
    </div>

    <div class="panel_wrapper" style="min-height: 350px;">

            <div id="help_panel" class="panel" style="overflow: auto">
                <div id="iframecontainer">
                    <h3>{'Using the toolbar'|i18n("design/standard/ezoe/help")}</h3>
                    <dl>                        
                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_bold"></span></dt>
                        <dd>{'Make the selected text <b>bold</b>. If the selected text is <b>bold</b> already, this button will remove the formating.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_italic"></span></dt>
                        <dd>{'Make the selected text <i>italic</i>. If the selected text is <i>italic</i> already, this button will remove the formating.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_underline"></span></dt>
                        <dd>{'Make the selected text <u>underline</u>. This button is only enabled if you have a custom tag named underline, template code to handle underline custom tags is not included in Online Editor.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_bullist"></span></dt>
                        <dd>{'Create a bullet list. To create a new list item, press "Enter". To end a list, press "Enter" key on an empty list item. If you click this button when the cursor is on a list item, the formatting will be removed.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_numlist"></span></dt>
                        <dd>{'Create a numbered list. To create a new list item, press "Enter". To end a list, press "Enter" key on an empty list item. If you click this button when the cursor is on a list item, the formatting will be removed.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_indent"></span></dt>
                        <dd>{'Increase list indent. Use this button to change the level of a list item in a nested list.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_outdent"></span></dt>
                        <dd>{'Decrease list indent. Use this button to change the level of a list item in a nested list.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_undo"></span></dt>
                        <dd>{'Undo the last operation in the editor. To undo more than one operation, keep clicking the button.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_redo"></span></dt>
                        <dd>{'Reverse the "Undo" command.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_link"></span></dt>
                        <dd>{'Create a hyperlink. You can select text first and then click this button to make the text a link. If the checkbox "Open in new window" is checked, the link will be displayed in a new browser window.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_unlink"></span></dt>
                        <dd>{'Removes a hyperlink. Select a link first and then click this button to remove the link (but not the content of the link).'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_anchor"></span></dt>
                        <dd>{'Create a named anchor. An anchor-like icon will appear in the editor.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_image"></span></dt>
                        <dd>{'Insert an image from the related images list, upload a new image, search for an existing images or browse for it. To upload a local image choose the local file, specify the name of the new object, choose placement from list, optionally write some caption text (You can use simple html formating here) and then click "Upload" button.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_object"></span></dt>
                        <dd>{'Insert an object from the related objects list, upload a new object, search for an existing object or browse for it. To upload a local file, click "Upload new" button choose the local file, specify the name of the new object, choose placement from list and then click "Upload" button. Note that embedded object will begin on a new line when displayed in the resulting XHTML.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_custom"></span></dt>
                        <dd>{'Create a custom tag. Optionally select the text you want to transform to a custom tag and click the button to open the insert custom tag window. Select the name of the custom tag you want to insert from the list, edit the attributes and click OK to insert it.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_literal"></span></dt>
                        <dd>{'Insert literal text. Text written in this field will be rendered literally in the final output.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_charmap"></span></dt>
                        <dd>{'Insert a special character. Click the button to open the special character window. Click on a character to insert it.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_pagebreak"></span></dt>
                        <dd>{'Insert a pagebreak. This button is only enabled if you have a custom tag named pagebreak, template code to handle pagebreaks is not included in Online Editor.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_pasteword"></span></dt>
                        <dd>{'Dialog to paste text from word, the dialog will handle cleaning the content from word.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_table"></span></dt>
                        <dd>{'Insert a table at the selected position. Tables with their border set to 0 are displayed with a grey border color in the editor.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_delete_table"></span></dt>
                        <dd>{'Delete the currently selected table.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_delete_col"></span></dt>
                        <dd>{'Delete the current column.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_col_after"></span></dt>
                        <dd>{'Insert a column to the left of the current cell.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_delete_row"></span></dt>
                        <dd>{'Delete the current row.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_row_after"></span></dt>
                        <dd>{'Insert a row bellow the current row.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_split_cells"></span></dt>
                        <dd>{'Split the current table cell into two cells.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_merge_cells"></span></dt>
                        <dd>{'Merge the selected table cells into one cell. (Select several cells with shift+click or Ctrl+click)'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_code"></span></dt>
                        <dd>{'Open Online Editor xhtml source code editor. This button is not enabled by default, and is only intended for experienced users.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_fullscreen"></span></dt>
                        <dd>{'Edit the current content attribute in the whole browser window("Fullscreen"). Click second time to go back to normal editing.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_help"></span></dt>
                        <dd>{'Open this help window.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_disable"></span></dt>
                        <dd>{'Disable editor'|i18n("design/standard/content/datatype")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_publish"></span></dt>
                        <dd>{'Send for publishing'|i18n("design/standard/content/edit")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_store"></span></dt>
                        <dd>{'Store draft'|i18n("design/standard/content/edit")}</dd>

                        <dt class="o2k7Skin defaultSkin"><span class="mceIcon mce_discard"></span></dt>
                        <dd>{'Discard'|i18n("design/standard/content/edit")}</dd>
                    </dl>
                    
                    <h3>{'Icons in dialog windows'|i18n("design/standard/ezoe/help")}</h3>
                    <dl>
                        <dt><img width="16" height="16" border="0" src={"tango/folder.png"|ezimage} /></dt>
                        <dd>{'Browse for a node / object.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt><img width="16" height="16" border="0" src={"tango/system-search.png"|ezimage} /></dt>
                        <dd>{'Search for a node / object.'|i18n("design/standard/ezoe/help")}</dd>

                        <dt><img width="16" height="16" border="0" src={"tango/bookmark-new.png"|ezimage} /></dt>
                        <dd>{'Browse for a node / object in your bookmarks.'|i18n("design/standard/ezoe/help")}</dd>
                    </dl>
                    
                    <h3>{'Tips &amp; Tricks'|i18n("design/standard/ezoe/help")}</h3>
                    <ul>
                        <li>{'You can adjust the height of the editor by draging the bottom right corner of the editor.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'You can create a new line by holding the Shift key down and pressing Enter key.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'You can create a new paragraph by pressing the Enter key.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'The status bar will show the current tag name and all its parent tags. You can view more information about the tags by hovering over them.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'You can make an image-link by selecting the image first and clicking the link button in the toolbar.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'You can edit wordmatch.ini to make text copied from MS Word directly assigned to a desired class.'|i18n("design/standard/ezoe/help")}</li>
                        <li>{'You can find more documentation in the doc folder of this extension and online on %link.'|i18n("design/standard/ezoe/help", '', hash( '%link', '<a href="http://ez.no/doc/extensions/online_editor/5_x" target="_blank">ez.no/doc</a>' ))}</li>
                    </ul>
                </div>
            </div>

            <div id="plugins_panel" class="panel">
                <div id="pluginscontainer">
                    <h3>{ldelim}#advanced_dlg.about_loaded{rdelim}</h3>

                    <div id="plugintablecontainer">
                    </div>

                    <p>&nbsp;</p>
                </div>
            </div>

            <div id="general_panel" class="panel">
                <h3>{'About'|i18n('design/standard/ezoe')} {$ezoe_name|wash}</h3>
                <p>{'Version'|i18n('design/standard/ezoe')}: {$ezoe_version|wash}<br />
                   {'License'|i18n('design/standard/ezoe')}: {$ezoe_license|wash}<br />
                   {$ezoe_copyright|wash}</p>
                <p>For more information about this software visit the <a href="http://ez.no" target="_blank">eZ Systems</a> website.</p>

                <h3>{'About'|i18n('design/standard/ezoe')} TinyMCE</h3>
                <p>{'Version'|i18n('design/standard/ezoe')}: <span id="version"></span> (<span id="date"></span>)</p>
                <p>TinyMCE is a platform independent web based Javascript HTML WYSIWYG editor control released as Open Source under <a href="http://www.opensource.org/licenses/lgpl-2.1.php" target="_blank">LGPL</a>

                by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances.</p>
                <p>Copyright &copy; 2003-2009, <a href="http://www.moxiecode.com" target="_blank">Moxiecode Systems AB</a>, All rights reserved.</p>
                <p>For more information about this software visit the <a href="http://tinymce.moxiecode.com" target="_blank">TinyMCE website</a>.</p>

            </div>
    </div>
</div>
