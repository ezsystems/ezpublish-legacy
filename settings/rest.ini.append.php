<?php /*

[System]
PrefixFilterClass=ezpRestDefaultRegexpPrefixFilter
ApiPrefix=/api

[DebugSettings]
Debug=enabled

[CacheSettings]
# Global switch to enable/disable REST application cache
# If enabled, result of each service call will be cached
# You can refine this with setting specific to your controller/action
# The system will look for a [<controllerClass>_<action>_CacheSettings] block to check
# if cache can be used, and if so, which TTL to use
# If this block cannot be found, the system will search at the controller level,
# and so look for a [<controllerClass>_CacheSettings] block.
# See example block below for more information
ApplicationCache=enabled
# Set default TTL to 10min, in seconds
DefaultCacheTTL=600

# Example for action "viewContent", in "ezpRestContentController" controller class
#[ezpRestContentController_viewContent_CacheSettings]
#ApplicationCache=enabled
#CacheTTL=3600

# Below an example for every actions contained in "ezpRestContentController" controller class
#[ezpRestContentController_CacheSettings]
#ApplicationCache=enabled
#CacheTTL=1200


*/ ?>
