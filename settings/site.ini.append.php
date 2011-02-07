<?php /* #?ini charset="utf-8"?


# Cache item entry (for eZ Publish 4.3 and up)
[Cache]
CacheItems[]=rest
CacheItems[]=restRoutes

[Cache_rest]
name=REST Application cache
id=rest
tags[]=content
tags[]=rest
path=rest
isClustered=true

[Cache_restRoutes]
name=REST Routes memory cache
id=rest-routes
tags[]=rest
class=ezpRestRoutesCacheClear
purgeClass=ezpRestRoutesCacheClear



*/ ?>
