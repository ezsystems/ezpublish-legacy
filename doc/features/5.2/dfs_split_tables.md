# DFS cluster split tables

By default, the MySQLi DFS cluster will use distinct tables to store storage and cache files.

This makes maintenance and deployment much easier, by allowing to TRUNCATE the cache table and instantly
remove all caches. There is also a performances benefit since less contention will happen on each table.

In a multi-site environment, it will also allow you to use different cache tables for different sites by
setting a different cache table for each site.

## Settings

The feature is enabled by default.

### CLUSTER_METADATA_TABLE_CACHE constant in config.[cluster.]php

Default: "ezdfsfile_cache"

Defines the name of the table where cache files metadata is stored.
Set it to an existing table to use this table for cache storage.

### `eZDFSClusteringSettings.MetaDataTableNameCache` in file.ini

Default: "ezdfsfile_cache"

Defines the name of the table where cache files metadata is stored.
Set it to an existing table to use this table for cache storage.

Note: CLUSTER_METADATA_TABLE_CACHE is recommended over the INI setting, since it will affect both
the FileHandler API, from within requests handled by index.php, and the cluster index used to deliver
binary files over HTTP. We recommend that you use the constant and not the INI setting.

### CLUSTER_METADATA_CACHE_PATH constant in config.[cluster.]php

Default: "/cache/"

Path part for storage files, used to distinguish cache files from storage files.
Must *only be modified if* you have changed `FileSettings.StorageDir` in site.ini.

### CLUSTER_METADATA_STORAGE_PATH constant in config.[cluster.]php

Default: "/storage/"

Path part for storage files, used to distinguish storage files from cache files.
Must *only be modified if* you have changed `FileSettings.StorageDir` in site.ini.

## Upgrading from eZ Publish < 5.2

If you migrate from an earlier version, it is recommended that you remove cache entries from the storage table.

To do so, the safest way is to:
- unclusterize data (`bin/php/clusterize.php -u`)
- run TRUNCATE TABLE ezdfsfile
- clusterize data again (`bin/php/clusterize.php`)

## Backwards compatibility

This change doesn't introduce any serious BC break. If you don't run the upgrade operation described above,
the only consequence is that the unused cache entries in ezdfsfile will remain there.

You can also disable the feature completely by setting `CLUSTER_METADATA_TABLE_CACHE` to `ezdfsfile` in config.cluster.php.