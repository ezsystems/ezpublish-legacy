<?php
//
// Created on: <28-Mar-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*
 * Generates all i18n strings for the TinyMCE editor
 * and transforms them to the TinyMCE format for
 * translations.
 */

include_once( 'extension/ezoe/classes/ezajaxcontent.php' );

class eZOEPackerFunctions
{
    public static function i18n( $args, $fileExtension )
    {
        if ( $fileExtension !== '.js' ) return '';

        $lang = '-en';
        if ( $args && $args[0] )
            $lang = $args[0];

        $i18nArray =  array( $lang => array(
            'common' => array(
                'edit_confirm' => ezi18n( 'design/standard/ezoe', "Do you want to use the WYSIWYG mode for this textarea?"),
                'apply' => ezi18n( 'design/standard/ezoe', "Apply"),
                'insert' => ezi18n( 'design/standard/ezoe', "Insert"),
                'update' => ezi18n( 'design/standard/ezoe', "Update"),
                'cancel' => ezi18n( 'design/standard/ezoe', "Cancel"),
                'close' => ezi18n( 'design/standard/ezoe', "Close"),
                'browse' => ezi18n( 'design/standard/ezoe', "Browse"),
                'class_name' => ezi18n( 'design/standard/ezoe', "Class"),
                'not_set' => ezi18n( 'design/standard/ezoe', "-- Not set --"),
                'clipboard_msg' => ezi18n( 'design/standard/ezoe', "Copy/Cut/Paste is not available in Mozilla and Firefox.\r\nDo you want more information about this issue?"),
                'clipboard_no_support' => ezi18n( 'design/standard/ezoe', "Currently not supported by your browser, use keyboard shortcuts instead."),
                'popup_blocked' => ezi18n( 'design/standard/ezoe', "Sorry, but we have noticed that your popup-blocker has disabled a window that provides application functionality. You will need to disable popup blocking on this site in order to fully utilize this tool."),
                'invalid_data' => ezi18n( 'design/standard/ezoe', "Error: Invalid values entered, these are marked in red."),
                'more_colors' => ezi18n( 'design/standard/ezoe', "More colors")
            ),
            'contextmenu' => array(
                'align' => ezi18n( 'design/standard/ezoe', "Alignment"),
                'left' => ezi18n( 'design/standard/ezoe', "Left"),
                'center' => ezi18n( 'design/standard/ezoe', "Center"),
                'right' => ezi18n( 'design/standard/ezoe', "Right"),
                'full' => ezi18n( 'design/standard/ezoe', "Full")
            ),
            'insertdatetime' => array(
                'date_fmt' => ezi18n( 'design/standard/ezoe', "%Y-%m-%d"),
                'time_fmt' => ezi18n( 'design/standard/ezoe', "%H:%M:%S"),
                'insertdate_desc' => ezi18n( 'design/standard/ezoe', "Insert date"),
                'inserttime_desc' => ezi18n( 'design/standard/ezoe', "Insert time"),
                'months_long' => ezi18n( 'design/standard/ezoe', "January,February,March,April,May,June,July,August,September,October,November,December"),
                'months_short' => ezi18n( 'design/standard/ezoe', "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec"),
                'day_long' => ezi18n( 'design/standard/ezoe', "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday"),
                'day_short' => ezi18n( 'design/standard/ezoe', "Sun,Mon,Tue,Wed,Thu,Fri,Sat,Sun")
            ),
            'print' => array(
                'print_desc' => ezi18n( 'design/standard/ezoe', "Print")
            ),
            'preview' => array(
                'preview_desc' => ezi18n( 'design/standard/ezoe', "Preview")
            ),
            'directionality' => array(
                'ltr_desc' => ezi18n( 'design/standard/ezoe', "Direction left to right"),
                'rtl_desc' => ezi18n( 'design/standard/ezoe', "Direction right to left")
            ),
            /*'layer' => array(
                'insertlayer_desc' => ezi18n( 'design/standard/ezoe', "Insert new layer"),
                'forward_desc' => ezi18n( 'design/standard/ezoe', "Move forward"),
                'backward_desc' => ezi18n( 'design/standard/ezoe', "Move backward"),
                'absolute_desc' => ezi18n( 'design/standard/ezoe', "Toggle absolute positioning"),
                'content' => ezi18n( 'design/standard/ezoe', "New layer...")
            ),*/
            'save' => array(
                'save_desc' => ezi18n( 'design/standard/ezoe', "Save"),
                'cancel_desc' => ezi18n( 'design/standard/ezoe', "Cancel all changes")
            ),
            'nonbreaking' => array(
                'nonbreaking_desc' => ezi18n( 'design/standard/ezoe', "Insert non-breaking space character")
            ),
            /*'iespell' => array(
                'iespell_desc' => ezi18n( 'design/standard/ezoe', "Run spell checking"),
                'download' => ezi18n( 'design/standard/ezoe', "ieSpell not detected. Do you want to install it now?")
            ),
            'advhr' => array(
                'advhr_desc' => ezi18n( 'design/standard/ezoe', "Horizontale rule")
            ),
            'emotions' => array(
                'emotions_desc' => ezi18n( 'design/standard/ezoe', "Emotions")
            ),*/
            'searchreplace' => array(
                'search_desc' => ezi18n( 'design/standard/ezoe', "Find"),
                'replace_desc' => ezi18n( 'design/standard/ezoe', "Find/Replace")
            ),
            /*'advimage' => array(
                'image_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit image")
            ),
            'advlink' => array(
                'link_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit link")
            ),
            'xhtmlxtras' => array(
                'cite_desc' => ezi18n( 'design/standard/ezoe', "Citation"),
                'abbr_desc' => ezi18n( 'design/standard/ezoe', "Abbreviation"),
                'acronym_desc' => ezi18n( 'design/standard/ezoe', "Acronym"),
                'del_desc' => ezi18n( 'design/standard/ezoe', "Deletion"),
                'ins_desc' => ezi18n( 'design/standard/ezoe', "Insertion"),
                'attribs_desc' => ezi18n( 'design/standard/ezoe', "Insert/Edit Attributes")
            ),
            'style' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Edit CSS Style")
            ),*/
            'paste' => array(
                'paste_text_desc' => ezi18n( 'design/standard/ezoe', "Paste as Plain Text"),
                'paste_word_desc' => ezi18n( 'design/standard/ezoe', "Paste from Word"),
                'selectall_desc' => ezi18n( 'design/standard/ezoe', "Select All")
            ),
            'paste_dlg' => array(
                'text_title' => ezi18n( 'design/standard/ezoe', "Use CTRL+V on your keyboard to paste the text into the window."),
                'text_linebreaks' => ezi18n( 'design/standard/ezoe', "Keep linebreaks"),
                'word_title' => ezi18n( 'design/standard/ezoe', "Use CTRL+V on your keyboard to paste the text into the window.")
            ),
            'table' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Inserts a new table"),
                'row_before_desc' => ezi18n( 'design/standard/ezoe', "Insert row before"),
                'row_after_desc' => ezi18n( 'design/standard/ezoe', "Insert row after"),
                'delete_row_desc' => ezi18n( 'design/standard/ezoe', "Delete row"),
                'col_before_desc' => ezi18n( 'design/standard/ezoe', "Insert column before"),
                'col_after_desc' => ezi18n( 'design/standard/ezoe', "Insert column after"),
                'delete_col_desc' => ezi18n( 'design/standard/ezoe', "Remove column"),
                'split_cells_desc' => ezi18n( 'design/standard/ezoe', "Split merged table cells"),
                'merge_cells_desc' => ezi18n( 'design/standard/ezoe', "Merge table cells"),
                'row_desc' => ezi18n( 'design/standard/ezoe', "Table row properties"),
                'cell_desc' => ezi18n( 'design/standard/ezoe', "Table cell properties"),
                'props_desc' => ezi18n( 'design/standard/ezoe', "Table properties"),
                'paste_row_before_desc' => ezi18n( 'design/standard/ezoe', "Paste table row before"),
                'paste_row_after_desc' => ezi18n( 'design/standard/ezoe', "Paste table row after"),
                'cut_row_desc' => ezi18n( 'design/standard/ezoe', "Cut table row"),
                'copy_row_desc' => ezi18n( 'design/standard/ezoe', "Copy table row"),
                'del' => ezi18n( 'design/standard/ezoe', "Delete table"),
                'row' => ezi18n( 'design/standard/ezoe', "Row"),
                'col' => ezi18n( 'design/standard/ezoe', "Column"),
                'cell' => ezi18n( 'design/standard/ezoe', "Cell")
            ),
            'autosave' => array(
                'unload_msg' => ezi18n( 'design/standard/ezoe', "The changes you made will be lost if you navigate away from this page.")
            ),
            'fullscreen' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Toggle fullscreen mode")
            ),
            'media' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Insert / edit embedded media"),
                'edit' => ezi18n( 'design/standard/ezoe', "Edit embedded media")
            ),
            'fullpage' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Document properties")
            ),
            'template' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Insert predefined template content")
            ),
            'visualchars' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Visual control characters on/off.")
            ),
            /*'spellchecker' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Toggle spellchecker"),
                'menu' => ezi18n( 'design/standard/ezoe', "Spellchecker settings"),
                'ignore_word' => ezi18n( 'design/standard/ezoe', "Ignore word"),
                'ignore_words' => ezi18n( 'design/standard/ezoe', "Ignore all"),
                'langs' => ezi18n( 'design/standard/ezoe', "Languages"),
                'wait' => ezi18n( 'design/standard/ezoe', "Please wait..."),
                'sug' => ezi18n( 'design/standard/ezoe', "Suggestions"),
                'no_sug' => ezi18n( 'design/standard/ezoe', "No suggestions"),
                'no_mpell' => ezi18n( 'design/standard/ezoe', "No misspellings found.")
            ),*/
            'pagebreak' => array(
                'desc' => ezi18n( 'design/standard/ezoe', "Insert page break.")
            ),
            'advanced' => array(
                'style_select' => ezi18n( 'design/standard/ezoe', "Styles"),
                //'font_size' => ezi18n( 'design/standard/ezoe', "Font size"),
                //'fontdefault' => ezi18n( 'design/standard/ezoe', "Font family"),
                'block' => ezi18n( 'design/standard/ezoe', "Format"),
                'paragraph' => ezi18n( 'design/standard/ezoe', "Paragraph"),
                'div' => ezi18n( 'design/standard/ezoe', "Div"),
                //'address' => ezi18n( 'design/standard/ezoe', "Address"),
                'pre' => ezi18n( 'design/standard/ezoe', "Literal"),
                'h1' => ezi18n( 'design/standard/ezoe', "Heading 1"),
                'h2' => ezi18n( 'design/standard/ezoe', "Heading 2"),
                'h3' => ezi18n( 'design/standard/ezoe', "Heading 3"),
                'h4' => ezi18n( 'design/standard/ezoe', "Heading 4"),
                'h5' => ezi18n( 'design/standard/ezoe', "Heading 5"),
                'h6' => ezi18n( 'design/standard/ezoe', "Heading 6"),
                //'blockquote' => ezi18n( 'design/standard/ezoe', "Blockquote"),
                'code' => ezi18n( 'design/standard/ezoe', "Code"),
                'samp' => ezi18n( 'design/standard/ezoe', "Code sample"),
                'dt' => ezi18n( 'design/standard/ezoe', "Definition term "),
                'dd' => ezi18n( 'design/standard/ezoe', "Definition description"),
                'bold_desc' => ezi18n( 'design/standard/ezoe', "Bold (Ctrl+B)"),
                'italic_desc' => ezi18n( 'design/standard/ezoe', "Italic (Ctrl+I)"),
                'underline_desc' => ezi18n( 'design/standard/ezoe', "Underline (Ctrl+U)"),
                'striketrough_desc' => ezi18n( 'design/standard/ezoe', "Strikethrough"),
                'justifyleft_desc' => ezi18n( 'design/standard/ezoe', "Align left"),
                'justifycenter_desc' => ezi18n( 'design/standard/ezoe', "Align center"),
                'justifyright_desc' => ezi18n( 'design/standard/ezoe', "Align right"),
                'justifyfull_desc' => ezi18n( 'design/standard/ezoe', "Align full"),
                'bullist_desc' => ezi18n( 'design/standard/ezoe', "Unordered list"),
                'numlist_desc' => ezi18n( 'design/standard/ezoe', "Ordered list"),
                'outdent_desc' => ezi18n( 'design/standard/ezoe', "Outdent"),
                'indent_desc' => ezi18n( 'design/standard/ezoe', "Indent"),
                'undo_desc' => ezi18n( 'design/standard/ezoe', "Undo (Ctrl+Z)"),
                'redo_desc' => ezi18n( 'design/standard/ezoe', "Redo (Ctrl+Y)"),
                'link_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit link"),
                'unlink_desc' => ezi18n( 'design/standard/ezoe', "Unlink"),
                'image_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit image"),
                'object_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit object"),
                'custom_desc' => ezi18n( 'design/standard/ezoe', "Insert custom tag"),
                'literal_desc' => ezi18n( 'design/standard/ezoe', "Insert literal text"),
                'pagebreak_desc' => ezi18n( 'design/standard/ezoe', "Insert pagebreak"),
                'cleanup_desc' => ezi18n( 'design/standard/ezoe', "Cleanup messy code"),
                'code_desc' => ezi18n( 'design/standard/ezoe', "Edit HTML Source"),
                'sub_desc' => ezi18n( 'design/standard/ezoe', "Subscript"),
                'sup_desc' => ezi18n( 'design/standard/ezoe', "Superscript"),
                //'hr_desc' => ezi18n( 'design/standard/ezoe', "Insert horizontal ruler"),
                'removeformat_desc' => ezi18n( 'design/standard/ezoe', "Remove formatting"),
                'custom1_desc' => ezi18n( 'design/standard/ezoe', "Your custom description here"),
                //'forecolor_desc' => ezi18n( 'design/standard/ezoe', "Select text color"),
                //'backcolor_desc' => ezi18n( 'design/standard/ezoe', "Select background color"),
                'charmap_desc' => ezi18n( 'design/standard/ezoe', "Insert custom character"),
                'visualaid_desc' => ezi18n( 'design/standard/ezoe', "Toggle guidelines/invisible elements"),
                'anchor_desc' => ezi18n( 'design/standard/ezoe', "Insert/edit anchor"),
                'cut_desc' => ezi18n( 'design/standard/ezoe', "Cut"),
                'copy_desc' => ezi18n( 'design/standard/ezoe', "Copy"),
                'paste_desc' => ezi18n( 'design/standard/ezoe', "Paste"),
                'image_props_desc' => ezi18n( 'design/standard/ezoe', "Image properties"),
                'newdocument_desc' => ezi18n( 'design/standard/ezoe', "New document"),
                'help_desc' => ezi18n( 'design/standard/ezoe', "Help"),
                //'blockquote_desc' => ezi18n( 'design/standard/ezoe', "Blockquote"),
                'clipboard_msg' => ezi18n( 'design/standard/ezoe', "Copy/Cut/Paste is not available in Mozilla and Firefox.\r\nDo you want more information about this issue?"),
                'path' => ezi18n( 'design/standard/ezoe', "Path"),
                'newdocument' => ezi18n( 'design/standard/ezoe', "Are you sure you want clear all contents?"),
                'toolbar_focus' => ezi18n( 'design/standard/ezoe', "Jump to tool buttons - Alt+Q, Jump to editor - Alt-Z, Jump to element path - Alt-X"),
                //'more_colors' => ezi18n( 'design/standard/ezoe', "More colors"),
                'next' => ezi18n( 'design/standard/ezoe', "Next"),
                'previous' => ezi18n( 'design/standard/ezoe', "Previous"),
                'select' => ezi18n( 'design/standard/ezoe', "Select"),
                'type' => ezi18n( 'design/standard/ezoe', "Type")
            ),
            'advanced_dlg' => array(
                'about_title' => ezi18n( 'design/standard/ezoe', "About TinyMCE"),
                'about_general' => ezi18n( 'design/standard/ezoe', "About"),
                'about_help' => ezi18n( 'design/standard/ezoe', "Help"),
                'about_license' => ezi18n( 'design/standard/ezoe', "License"),
                'about_plugins' => ezi18n( 'design/standard/ezoe', "Plugins"),
                'about_plugin' => ezi18n( 'design/standard/ezoe', "Plugin"),
                'about_author' => ezi18n( 'design/standard/ezoe', "Author"),
                'about_version' => ezi18n( 'design/standard/ezoe', "Version"),
                'about_loaded' => ezi18n( 'design/standard/ezoe', "Loaded plugins"),
                /*'anchor_title' => ezi18n( 'design/standard/ezoe', "Insert/edit anchor"),
                'anchor_name' => ezi18n( 'design/standard/ezoe', "Anchor name"),*/
                'code_title' => ezi18n( 'design/standard/ezoe', "HTML Source Editor"),
                'code_wordwrap' => ezi18n( 'design/standard/ezoe', "Word wrap"),
                /*'colorpicker_title' => ezi18n( 'design/standard/ezoe', "Select a color"),
                'colorpicker_picker_tab' => ezi18n( 'design/standard/ezoe', "Picker"),
                'colorpicker_picker_title' => ezi18n( 'design/standard/ezoe', "Color picker"),
                'colorpicker_palette_tab' => ezi18n( 'design/standard/ezoe', "Palette"),
                'colorpicker_palette_title' => ezi18n( 'design/standard/ezoe', "Palette colors"),
                'colorpicker_named_tab' => ezi18n( 'design/standard/ezoe', "Named"),
                'colorpicker_named_title' => ezi18n( 'design/standard/ezoe', "Named colors"),
                'colorpicker_color' => ezi18n( 'design/standard/ezoe', "Color' => ezi18n( 'design/standard/ezoe', "),
                'colorpicker_name' => ezi18n( 'design/standard/ezoe', "Name' => ezi18n( 'design/standard/ezoe', "),*/
                'charmap_title' => ezi18n( 'design/standard/ezoe', "Select custom character")/*,
                'image_title' => ezi18n( 'design/standard/ezoe', "Insert/edit image"),
                'image_src' => ezi18n( 'design/standard/ezoe', "Image URL"),
                'image_alt' => ezi18n( 'design/standard/ezoe', "Image description"),
                'image_list' => ezi18n( 'design/standard/ezoe', "Image list"),
                'image_border' => ezi18n( 'design/standard/ezoe', "Border"),
                'image_dimensions' => ezi18n( 'design/standard/ezoe', "Dimensions"),
                'image_vspace' => ezi18n( 'design/standard/ezoe', "Vertical space"),
                'image_hspace' => ezi18n( 'design/standard/ezoe', "Horizontal space"),
                'image_align' => ezi18n( 'design/standard/ezoe', "Alignment"),
                'image_align_baseline' => ezi18n( 'design/standard/ezoe', "Baseline"),
                'image_align_top' => ezi18n( 'design/standard/ezoe', "Top"),
                'image_align_middle' => ezi18n( 'design/standard/ezoe', "Middle"),
                'image_align_bottom' => ezi18n( 'design/standard/ezoe', "Bottom"),
                'image_align_texttop' => ezi18n( 'design/standard/ezoe', "Text top"),
                'image_align_textbottom' => ezi18n( 'design/standard/ezoe', "Text bottom"),
                'image_align_left' => ezi18n( 'design/standard/ezoe', "Left"),
                'image_align_right' => ezi18n( 'design/standard/ezoe', "Right"),
                'link_title' => ezi18n( 'design/standard/ezoe', "Insert/edit link"),
                'link_url' => ezi18n( 'design/standard/ezoe', "Link URL"),
                'link_target' => ezi18n( 'design/standard/ezoe', "Target"),
                'link_target_same' => ezi18n( 'design/standard/ezoe', "Open link in the same window"),
                'link_target_blank' => ezi18n( 'design/standard/ezoe', "Open link in a new window")/*,
                'link_titlefield' => ezi18n( 'design/standard/ezoe', "Title"),
                'link_is_email' => ezi18n( 'design/standard/ezoe', "The URL you entered seems to be an email address, do you want to add the required mailto: prefix?"),
                'link_is_external' => ezi18n( 'design/standard/ezoe', "The URL you entered seems to external link, do you want to add the required http:// prefix?"),
                'link_list' => ezi18n( 'design/standard/ezoe', "Link list")*/
            ),
        ));
        $i18nString = eZAjaxContent::jsonEncode( $i18nArray );

        return 'tinyMCE.addI18n( ' . $i18nString . ' );';
    }

    public static function getCacheTime( $functionName )
    {
        // this translation data only expires when this timestamp is increased
        return 1207643268;
    }
}

?>