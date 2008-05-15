<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for site wide settings


[TemplateSettings]
ExtensionAutoloadPath[]=ezoe


[MediaClassSettings]
# Deprecated, use ImageClassIdentifiers instead
#ImageClassID[]=5
ImageClassIdentifiers[]=image


[ImageDataTypeSettings]
AvailableImageDataTypes[]=ezimage

# Looking for AvailableViewModes[] settings?
# they are moved to content.ini as part of the tag settings.

[RegionalSettings]
TranslationExtensions[]=ezoe

[SSLZoneSettings]
ModuleViewAccessMode[ezoe/*]=keep


*/ ?>