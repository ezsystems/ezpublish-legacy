# Cluster events #

Cluster events consist in a new feature allowing to interact with eZ Publish cluster system.

Depending on the action made by eZ Publish cluster, an event is trigerred via `ezpEvent` class.
It allows a dedicated listener object to store metadata in cache engines for example (like **APC** or **Memcached**)
and thus save database queries (and increase performance and scalability).
These events would eventually also allow to push media files directly to a CDN (Akamai, Amazon S3...).



## Requirements ##

At the moment, cluster events are only available for a **DFS cluster** environment using **MySQLi backend**.


## Cluster event listener ##

In order to properly respond to cluster events, a dedicated event listener must be developped as a PHP class.
This class must implement `eZClusterEventListener` interface (see comments in the interface for more information).

To work then, cluster events must be activated and the listener declared in `settings/override/file.ini.append.php`:

```ini
[ClusterEventsSettings]
ClusterEvents=enabled
Listener=MyClusterEventListener
```

### Cluster gateway for index_cluster.php ###
As of 4.7, cluster index (the one which serves binary files) has been refactored (see `doc/features/4.7/cluster_index.txt`).
Depending on your cluster backend, a gateway class is used (`ezpDfsMySQLiClusterGateway` in case of DFS MySQLi).

For performance reasons, cluster events are not triggered from cluster gateways, so the listener needs to be notified directly.
A best practice for this is to extend the gateway and at least override `connect()` and `fetchFileMetadata()` methods
to use your listener:

```php
  <?php
  require_once 'kernel/clustering/dfsmysqli.php';

  class MyClusterGatewayMySQLi extends ezpDfsMySQLiClusterGateway
  {
      private $eventListener;

      public function connect()
      {
          // Inject eZClusterEventLoggerPhp to allow logging through error_log() function instead of eZDebug.
          $this->eventListener = new MyClusterEventListener( new eZClusterEventLoggerPhp );
          $this->eventListener->initialize();
          parent::connect();
      }

      public function fetchFileMetadata( $filepath )
      {
          $cachedMetadata = $this->eventListener->loadMetadata( $filepath );

          if ( $cachedMetadata !== false )
              return $cachedMetadata;

          $clusterMetadata = parent::fetchFileMetadata( $filepath );

          if ( $clusterMetadata !== false )
              $this->eventListener->storeMetadata( $clusterMetadata );

          return $clusterMetadata;
      }

  }
```

Then following configuration should be added to `config.cluster.php`

```php
  <?php
  // Adapt settings to your needs
  define( 'CLUSTER_STORAGE_BACKEND', 'dfsmysqli'  );
  define( 'CLUSTER_STORAGE_HOST', 'localhost' );
  define( 'CLUSTER_STORAGE_PORT', 3306 );
  define( 'CLUSTER_STORAGE_USER', 'root' );
  define( 'CLUSTER_STORAGE_PASS', 'root' );
  define( 'CLUSTER_STORAGE_DB', 'cluster' );
  define( 'CLUSTER_STORAGE_CHARSET', 'utf8' );
  define( 'CLUSTER_MOUNT_POINT_PATH', '/mnt/ezdfs' );

  require_once 'extension/myclustereventsextension/classes/MyClusterGatewayMySQLi.php';
  require_once 'kernel/clustering/gateway.php';
  ezpClusterGateway::setGatewayClass( 'MyClusterGatewayMySQLi' );
```

If you need configuration, do NOT try to load eZINI as it has a lot of dependencies that are not suited
to be loaded in `index_cluster.php`.
Try to use constants instead or configuration objects passed to your custom gateway.


## Supported events ##

- `cluster/storeMetadata` - **Notification**.
  The metadata array is passed as argument.

- `cluster/loadMetadata` - **Filter**.
  File path we need metadata from is passed as argument.
  Must return an the metadata array or false.

- `cluster/fileExists` - **Filter**.
  File path is passed as argument.
  Must return an array (numeric indexes) containing name (first index) and mtime (second index) if file exists, false if not.

- `cluster/deleteFile` - **Notification**.
  File path is passed as argument.

- `cluster/deleteByLike` - **Notification**.
  The like param is passed as argument.

- `cluster/deleteByDirList` - **Notification**.
  Are passed: *dirList* (array), *commonPath* and *commonSuffix*

- `cluster/deleteByNametrunk` - **Notification**.
  Nametrunk is passed as argument.


## Known limitations ##

Cluster system is built *very early* in the request life cycle in eZ Publish and thus doesn't allow to have 
settings in extensions or siteaccesses.

So if one wants to develop a cluster event extension, **configuration file(s) MUST be placed in `settings/override/`**.


