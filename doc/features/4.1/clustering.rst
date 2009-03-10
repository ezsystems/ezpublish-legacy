.. -*- coding: utf-8 -*-

==========================
eZ Cluster migration guide
==========================

:Author: Jérôme Renard
:Date: 2009/03/05
:Version: 1.0

This document describes the upgrade process for
end-users who wants to update their eZ Publish cluster
from 3.10 version to 4.* versions.

.. Note:: **If you plan to update an eZ Publish cluster prior to 3.10
          read this documentation first** http://pubsvn.ez.no/nextgen/trunk/doc/features/3.10/cluster_enhancement.txt

.. Note:: **This guide assumes the code base is already update to the 4.1 version, do not upgrade
          the cluster before upgrading the code base, this could lead to a data loss.**

.. contents:: Table of contents


Migrating from 3.10 cluster to 4.1
==================================

Using the database backend
--------------------------

Updating the configuration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The configuration file has changed slightly, before 4.1 ``settings/file.ini`` looked like this : 

::

    [ClusteringSettings]
    FileHandler=ezdb
    DBBackend=mysql
    [...]

Since there is a new handler system in eZ Publish 4.1, you have to update your configuration file ``file.ini.append.php``.

All you have to do is this apply the following configuration

::

    [ClusteringSettings]
    FileHandler=eZDBFileHandler
    DBBackend=eZDBFileHandlerMysqlBackend
    [...]

Other configuration directives remain unchanged.

.. Note:: If you plan to upgrade from eZ Publish 4.0.2 or later you do not have
          to purge the cache and upgrade the cluster's table, the only thing you
          have to do is to update the configuration file as explained above.

Purging all caches
~~~~~~~~~~~~~~~~~~

In order to upgrade your eZ Publish cluster to the 4.1 version, no unclusterization
process is required. All you have to do is to purge all caches first.

::

    php ./bin/php/ezcache.php --clear-all --purge

Upgrading the tables
~~~~~~~~~~~~~~~~~~~~~

Once all caches are cleared you can update the tables with the following SQL queries.

For MySQL :

::

    ALTER TABLE ezdbfile ADD name_trunk TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER name ;
    ALTER TABLE ezdbfile ADD INDEX ezdbfile_name_trunk ( name_trunk ( 250 ) ) 


Using the new filesystem backend
--------------------------------

Since eZ Publish 4.1, a new FileSystem backend is available : eZFS2.

This new backend is more effecient with network shared partitions (like NFS)
and handles gracefully cache purges and updates for content caches and
cache-blocks.

If you plan to use (or already use it) a shared partition using eZFS2 is higly recommended.

.. Note:: Please note that eZFS2 requires Linux or Windows + PHP 5.3 >= support.

Updating the configuration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In order to use eZFS2 all you have to do is to update your ``file.ini.append.php`` file.
This can even be a hot change while your site running on production.

Before 4.1 you had the following configuration : 

::

    [ClusteringSettings]
    FileHandler=ezfs
    [...]

Change the configuration to this one :

::

    [ClusteringSettings]
    FileHandler=eZFS2FileHandler
    [...]

Extra configuration for eZFS2
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

eZFS2FileHandler comes with a few configuration directives, it is safe to use the default values.
In case you want something more specific to your project you can update the following configuration directives

file.ini : NonExistantStaleCacheHandling[]
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Defines what happens when a requested cache file is already being generated
and no expired cache file exists (for instance if the content is new)
Two possible values :

- wait: places the process in a wait loop for a limited time until the file is done generating.
        This is the default value
- generate: let the requesting process generate its own data without storing the result

The key of this array defined the type of cache impacted by the setting.
Three cache types are allowed here : 

- viewcache
- cacheblock
- misc (any cache that is not viewcache nor cacheblock).

Default configuration in ``settings/site.ini``, ``[ClusteringSettings]`` section :

::

    NonExistantStaleCacheHandling[]
    NonExistantStaleCacheHandling[viewcache]=wait
    NonExistantStaleCacheHandling[cacheblock]=wait
    NonExistantStaleCacheHandling[misc]=wait

site.ini : CacheGenerationTimeout
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This is the maximum cache generation time. If a file stays in generation mode for more than
this value in seconds, it is considered timed out and generation is taken over by the requesting process

Default configuration ``settings/site.ini``, ``[ContentSettings]`` section :

::

    CacheGenerationTimeout=60

