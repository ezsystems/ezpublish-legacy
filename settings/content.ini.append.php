<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for content and ez xml tags
#

# Some custom tags add special features to the editor if enabled:
# underline: adds underline button in edtor (use this instead of custom tag to make the text appear underlined)
# pagebreak: adds a button to add pagebreaks
# NOTE: view template is not included for pagebreak tag, so you need to implement it to see it
#
#[CustomTagSettings]
#AvailableCustomTags[]=pagebreak
#AvailableCustomTags[]=underline
#IsInline[underline]=true




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

# Extra ezoe settings for embed and embed-inline.
# If you want to limit the amount of AvailableClasses and/or CustomAttributes
# on relations per class identifier or content type* you can do the following:
# NB: These settings also needs to be defined in [embed] or [embed-inline] for eZ Publish.
#
# Pattern for content type:
#[<tag>-type_<content-type>]
#
## This examples demonstrates limiting AvailableClasses list in editor
## when editing embed tags with a relation to a object of content type image.
#[embed-type_images]
#AvailableClasses[]
#AvailableClasses[]=blue_border
#AvailableClasses[]=dropp_down_shadow
#
# Pattern for class identifier:
#[<tag>_<class_identifier>]
#
## This example removes class list on embed-inline flash objects
#[embed-inline_flash]
#AvailableClasses[]
#
# *content type as defined by content.ini [RelationGroupSettings]

*/ ?>