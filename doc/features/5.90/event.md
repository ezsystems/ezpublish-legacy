Event system
============

Changed events
--------------

#### 2017.12.2

In 2017.12.2 `content/cache` was changed to also pass in object id list to
make cache clearing logic between legacy and platform more solid for wider
range of use cases.

    content/cache ( array $NodeIdist, array $objectIdList = null )

The new second argument is optional, however highly recommended
to fill in if you have custom code emitting this event. Like before
you are expected to return list of node ids _(same value or with
appended node ids based on cusotm logic)_.
