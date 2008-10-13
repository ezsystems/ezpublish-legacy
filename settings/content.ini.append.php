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
## These classes also needs to be defined in [embed] for eZ Publish.
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