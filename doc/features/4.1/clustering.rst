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

.. Note:: If you plan to update an eZ Publish cluster prior to 3.10
          read this documentation first http://pubsvn.ez.no/nextgen/trunk/doc/features/3.10/cluster_enhancement.txt

.. Note:: **This guide assumes the code base is already update to the 4.1 version, do not upgrade
          the cluster before upgrading the code base, this could lead to a data loss.**

.. contents:: Table of contents


Migrating from 3.10 cluster to 4.0
==================================

Using the database backend
--------------------------

If you are using eZ Publish cluster with MySQL or Oracle you have to unclusterize your data first.
The unclusterization process copies all the data in the ezdbfile and ezdbfile_data table on the local file system.

Updating the configuration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The configuration file has changed slightly, before 4.1 ``settings/file.ini`` looked like this : 

::
    [ClusteringSettings]
    FileHandler=ezdb
    DBBackend=mysql
    [...]

Since there is a new handler system in eZ Publish 4.1, you have to update your configuration file ``file.ini.append.php``.

All you have to do is this apply the following configuration

For MySQL :

::

    [ClusteringSettings]
    FileHandler=eZDBFileHandler
    DBBackend=eZDBFileHandlerMysqlBackend
    [...]

For Oracle :

:: 

    [ClusteringSettings]
    FileHandler=eZDBFileHandler
    DBBackend=eZDBFileHandlerOracleBackend
    [...]

Other configuration directives remain unchanged.

Unclusterize
~~~~~~~~~~~~

Copy all files from the database to the local filesystem.
This is performed using the ``bin/php/clusterize.php`` script.
The *unclusterize* option must be enabled to copy the files from database and to the local filesystem. e.g.

::

    php ./bin/php/clusterize.php -u

.. Note:: Make sure you have enough diskspace on the filesystem before starting this operation.

If you have a lot of data (> 40, 50Gb) the unclusterize process will be running for 3 or 4 hours, this is a normal behaviour.

Upgrading the tables
~~~~~~~~~~~~~~~~~~~~~

Before upgrading the tables make sure the unclusterization process is successfully done.

You can remove the actual ezdbfile and ezdbfile_data tables :

::

    DROP TABLE ezdbfile_data, ezdbfile;

Now you can apply the following SQL query to recreate the new tables.

For MySQL :

::

    CREATE TABLE ezdbfile (
        datatype      VARCHAR(60)   NOT NULL DEFAULT 'application/octet-stream',
        name          TEXT          NOT NULL,
        name_trunk    TEXT          NOT NULL,
        name_hash     VARCHAR(34)   NOT NULL DEFAULT '',
        scope         VARCHAR(20)   NOT NULL DEFAULT '',
        size          BIGINT(20)    UNSIGNED NOT NULL,
        mtime         INT(11)       NOT NULL DEFAULT '0',
        expired       BOOL          NOT NULL DEFAULT '0',
        PRIMARY KEY (name_hash),
        INDEX ezdbfile_name (name(250)),
        INDEX ezdbfile_name_trunk (name_trunk(250)),
        INDEX ezdbfile_mtime (mtime),
        INDEX ezdbfile_expired_name (expired, name(250))
    ) ENGINE=InnoDB;

    CREATE TABLE ezdbfile_data (
        name_hash VARCHAR(34)   NOT NULL DEFAULT '',
        offset    INT(11) UNSIGNED NOT NULL,
        filedata  BLOB          NOT NULL,
        PRIMARY KEY (name_hash, offset),
        CONSTRAINT ezdbfile_fk1 FOREIGN KEY (name_hash) REFERENCES ezdbfile (name_hash) ON DELETE CASCADE
    ) ENGINE=InnoDB;

For Oracle :

::

    CREATE TABLE ezdbfile (
        name      VARCHAR2(4000) NOT NULL,
        name_hash VARCHAR2(34)  PRIMARY KEY,
        datatype  VARCHAR2(60)  DEFAULT 'application/octet-stream' NOT NULL,
        scope     VARCHAR2(20)  DEFAULT 'UNKNOWN' NOT NULL,
        filesize  INT           NOT NULL,
        mtime     INT           DEFAULT 0 NOT NULL,
        lob       BLOB,
        expired   CHAR(1)       DEFAULT '0' NOT NULL
    );

    CREATE INDEX ezdbfile_name ON ezdbfile ( name );
    CREATE INDEX ezdbfile_mtime ON ezdbfile ( mtime );
    --CREATE UNIQUE INDEX ezdbfile_expired_name ON ezdbfile ( expired, name );

    CREATE OR REPLACE PROCEDURE EZEXCLUSIVELOCK ( P_NAME IN VARCHAR2, P_NAME_HASH  IN VARCHAR2 ) AS
    -- Get exclusive lock on a table row (or die waiting!)
    --
    -- @todo use oracle MERGE statement instead of this poor man's version
    V_HASH EZDBFILE.NAME_HASH%TYPE;
    BEGIN
    SELECT NAME_HASH
    INTO V_HASH
    FROM EZDBFILE
    WHERE NAME_HASH = P_NAME_HASH
    FOR UPDATE;
    EXCEPTION
    WHEN NO_DATA_FOUND THEN
        BEGIN
        INSERT INTO EZDBFILE ( NAME, NAME_HASH, FILESIZE, MTIME ) VALUES ( P_NAME, P_NAME_HASH, -1, -1);
        EXCEPTION
        WHEN DUP_VAL_ON_INDEX THEN
            NULL;
        END;
        SELECT NAME_HASH
        INTO V_HASH
        FROM EZDBFILE
        WHERE NAME_HASH = P_NAME_HASH
        FOR UPDATE;
    END;

Clusterizing
~~~~~~~~~~~~~

This is similar to the first step, this time however we will reverse the process
and copy the files from the local filesystem to the database cluster.

Again it is performed using the ``bin/php/clusterize.php`` script.

.. Note:: Make sure the *unclusterize* option is **not** enabled.

You can perform the copy operation with :

::

  php ./bin/php/clusterize.php

The clusterize process will transfer all binary files from the local filesystem
to the database but will **never** transfer any cache file.

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

