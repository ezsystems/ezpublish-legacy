<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for content and ez xml tags
#

# Some custom tags ads special features to the editor if enabled:
# underline: ads underline button in edtor (use this instead of custom tag to make the text appear underlined)
# pagebreak: ads a button to add pagebreaks
# NOTE: view template is not included for these tags, so you need to implement them yourself
#
#[CustomTagSettings]
#AvailableCustomTags[]=pagebreak
#AvailableCustomTags[]=underline
#IsInline[underline]=true



# 'CustomAttributesDefaults' setting defines custom attributes default values
# that appear in the dialog window for a newly created element.
#
# 'CustomAttributeMyattrSelections' lets you define drop down boxes instead of
#  simple text inputs with value / Name pairs for the selction like this:
# 'CustomAttributeMyattrSelections[value]=Name' where value must correspond
#  to default value in 'CustomAttributesDefaults' if set.
#
# 'CustomAttributesNames' lets you specify the human readable name of the attribute
#
# Example for a custom tag:
#
#[factbox]
#CustomAttributesDefaults[align]=right
#CustomAttributesDefaults[myattr]=default
#CustomAttributesNames[align]=Alignment
#CustomAttributeAlignSelections[left]=Left
#CustomAttributeAlignSelections[right]=Right
#CustomAttributeAlignSelections[center]=Center


[paragraph]
# Human-readable aliases for a class names that will be displayed
# in the "Class" dropdowns of dialog windows.
#ClassDescription[pRed]=Red bar
ClassDescription[]

[header]
ClassDescription[]

[strong]
ClassDescription[]

[emphasize]
ClassDescription[]

[link]
ClassDescription[]

[literal]
ClassDescription[] 

[table]
ClassDescription[]
Defaults[]
Defaults[rows]=2
Defaults[cols]=2
Defaults[width]=100%
Defaults[border]=0
#Defaults[class]=myclass

[tr]
ClassDescription[]

[th]
ClassDescription[]

[td]
ClassDescription[]

[ol]
ClassDescription[]

[ul]
ClassDescription[]

[embed]
ClassDescription[]

[embed-inline]
ClassDescription[]


*/ ?>