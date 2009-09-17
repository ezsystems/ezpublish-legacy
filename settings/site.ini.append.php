<?php /* #?ini charset="utf-8"?


[TemplateSettings]
ExtensionAutoloadPath[]=ezjscore


[SSLZoneSettings] 
ModuleViewAccessMode[ezjscore/*]=keep

[RoleSettings]
PolicyOmitList[]=ezjscore/hello
PolicyOmitList[]=ezjscore/call

[SiteAccessSettings]
AnonymousAccessList[]=ezjscore/hello
AnonymousAccessList[]=ezjscore/call


[eZJSCore]
# Disables/enables js / css packer (for debugging apache rewrite rules)
# Normally controlled by [TemplateSettings]DevelopmentMode for convenience,
# but can also be controlled by this setting if set.
# Force packer level by setting integer from 0 to 3 instead of [dis|en]abled
#Packer=disabled



*/ ?>