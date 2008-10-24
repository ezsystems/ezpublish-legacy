<?php /* #?ini charset="utf-8"?
# eZ publish configuration file for site wide settings


[TemplateSettings]
ExtensionAutoloadPath[]=ezoe


[MediaClassSettings]
# Deprecated, use [RelationGroupSettings]ImagesClassList[] in content.ini
#ImageClassID[]=5


[ImageDataTypeSettings]
AvailableImageDataTypes[]=ezimage

# Looking for AvailableViewModes[] settings?
# they are moved to content.ini as part of the tag settings.

[RegionalSettings]
TranslationExtensions[]=ezoe

[SSLZoneSettings]
ModuleViewAccessMode[ezoe/*]=keep


*/ ?>