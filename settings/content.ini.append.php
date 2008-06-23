<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for content and ez xml tags
#

# Some custom tags add special features to the editor if enabled:
# underline: adds underline button in edtor (use this instead of custom tag to make the text appear underlined)
# pagebreak: adds a button to add pagebreaks
# NOTE: view template is not included for these tags, so you need to implement them yourself
#
#[CustomTagSettings]
#AvailableCustomTags[]=pagebreak
#AvailableCustomTags[]=underline
#IsInline[underline]=true


# Custom attribute settings, these can be used on all tags (also custom tags)
# 'CustomAttributes', defines the list of custom attributtes to use on current tag (defined by [block])
# 'CustomAttributesDefaults' setting defines custom attributes default values
#  that appear in the dialog window for a newly created element.
#  Deprecate: use 'Default' on the custom attribute settings block instead (explained bellow)
#
## Example for a custom tag:
#
#[factbox]
#CustomAttributes[]=align
#CustomAttributes[]=myattr
#CustomAttributesDefaults[align]=right
#CustomAttributesDefaults[myattr]=default
#
## CustomAttribute settings block for align attribute on factbox tag!
## You can also define global align cusom attribute setting with
## this pattern [CustomAttribute_align], but this is only used if
## there is no tag specific settings like this one:
#
#[CustomAttribute_factbox_align]
## Optional, lets you specify the label name on the html control
#Name=Align
## Optional, lets you specify the default value on the html control
#Default=right
## Optional, lets you specify what html form input element (type) to use, valid:
## text (default, supports validation)
## int (supports validation)
## number (supports validation)
## email (supports validation)
## select (drop down box with selectable values)
## hidden (hide the control)
## color ( with color picker and preview of color, needs rewrite rules to allow html files from design folders if in virtualhost mode )
## checkbox
#Type=select
## Optional, lets you disable the html control so users can't change the value
#Disabled=true
## Optional, forces user to fill out the html form element
#Required=true
## Optional, for validation when type is int or number (inclusive)
#Minimum=1
## Optional, for validation when type is int or number (inclusive)
#Maximum=99
## Selection is needed if type is set to select
#Selection[]
#Selection[left]=Left
#Selection[right]=Right
#Selection[center]=Center


# Complete custom attributes example for enabling and forcing users
# to fill out table summary for WAI conformance
#
#[table]
#CustomAttributes[]=summary
#
#[CustomAttribute_table_summary]
#Name=Summary (WAI)
#Required=true


[paragraph]
# Human-readable aliases for class names that will be displayed
# in the "Class" dropdowns of dialog windows.
#ClassDescription[pRed]=Red bar

[link]
AvailableViewModes[]=full
AvailableViewModes[]=line

[table]
Defaults[rows]=2
Defaults[cols]=2
Defaults[width]=100%
Defaults[border]=0
#Defaults[class]=myclass

[embed]
AvailableViewModes[]=embed
AvailableViewModes[]=embed-inline
AvailableViewModes[]=full
AvailableViewModes[]=line

[embed-inline]
AvailableViewModes[]=embed-inline

# If you want to limit the amount of AvailableClasses selections
# on relations per eZ Publish Content Class you can do the following:
#
## This examples demonstrates limiting AvailableClasses list in editor
## when editing embed tags with a relation to a object of type image.
## These classes also need to be defined in [embed] for eZ Publish.
#[embed_image]
#AvailableClasses[]
#AvailableClasses[]=blue_border
#AvailableClasses[]=dropp_down_shadow
#
## This example removes class list on embed-inline flash objects
#[embed-inline_flash]
#AvailableClasses[]
#

*/ ?>