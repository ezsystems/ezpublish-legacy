<?php /* #?ini charset="utf-8"?


[TemplateSettings]
ExtensionAutoloadPath[]=ezjscore


[SSLZoneSettings] 
ModuleViewAccessMode[ezjscore/*]=keep


# Permissions to the specific ajax calls are handled inside the ezjscore/call
# view, so only comment this out if you want to disable it!
[RoleSettings]
PolicyOmitList[]=ezjscore/hello
PolicyOmitList[]=ezjscore/call

[SiteAccessSettings]
AnonymousAccessList[]=ezjscore/hello
AnonymousAccessList[]=ezjscore/call

# Cache item entry (for eZ Publish 4.3 and up)
[Cache]
CacheItems[]=ezjscore

[Cache_ezjscore]
name=eZJSCore Public Packer cache
id=ezjscore-packer
tags[]=content
tags[]=template
path=public



*/ ?>