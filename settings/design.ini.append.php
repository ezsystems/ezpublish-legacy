<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for design

[ExtensionSettings]
DesignExtensions[]=ezoe

[StylesheetSettings]
# A list of stylesheets to use for the editor content.
# Enable the code button in ezoe.ini to be able to see the
# generated html content or use a debug tool like firebug!
# You can use a variable <skin> anywhere in the path / filename
# this [optional]variable is taken from ezoe.ini
EditorCSSFileList[]
EditorCSSFileList[]=skins/<skin>/content.css


## Here is an example for appending your own css to the editor
## content you need to place it in the stylesheet folder in
## one of your active eZ Publish designs.
#[StylesheetSettings]
#EditorCSSFileList[]=my_custom_editor_styles.css


*/ ?>