<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for design

[ExtensionSettings]
DesignExtensions[]=ezoe

[StylesheetSettings]
# A list of stylesheets to use for the editor content.
# Enable the code button in ezoe.ini to be able to see the
# generated html content or use a debug tool like firebug!
EditorCSSFileList[]
# You can use a variable <skin> anywhere in the path
# this [optional]variable is taken from ezoe.ini
EditorCSSFileList[]=skins/<skin>/content.css


## here is an example for appending your own css to the editor
## content you needc to plase it in the stylesheet folder in
## one of your active  eZ Publish designs.
#[StylesheetSettings]
#EditorCSSFileList[]=my_custom_editor_styles.css


*/ ?>