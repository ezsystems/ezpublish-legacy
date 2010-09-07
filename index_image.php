<?php
/*
This file should be included from a file containing
clustering database settings.

Here are sample contents of such file:
-------------------------------------------
<?php
define( 'STORAGE_BACKEND',     'mysql'           );
define( 'STORAGE_HOST',        'db'              );
define( 'STORAGE_PORT',        3306              );
define( 'STORAGE_SOCKET',      '/tmp/mysql.sock' );
define( 'STORAGE_USER',        'fred'            );
define( 'STORAGE_PASS',        'secret'          );
define( 'STORAGE_DB',          'cluster'         );
define( 'STORAGE_CHUNK_SIZE',  65535             );

// If you use the DFS cluster, you also need to set the path for the shared directory:
define( 'MOUNT_POINT_PATH',    'var/nfsmount'    );

// If you use Oracle you might want to set these (see README.cluster in the ezoracle extension):
//define( 'USE_ETAG', true );
//define( 'EXPIRY_TIMEOUT', 60 * 60 * 24 * 30 );
//define ( 'STORAGE_PERSISTENT_CONNECTION', true );

// If you're not using UTF-8 (which we STRONGLY recommend),
// you MUST define the character set according to your setup:
// site.ini/[DatabaseSettings]/Charset
// Not doing so will use a default UTF-8 connection
// NOTE: Use here a character set as understood by the server!
// MySQL uses for instance 'utf8', 'latin1', 'cp1250',... rather than
// 'utf-8', 'iso-8859-1', 'windows-1250',...
define( 'STORAGE_CHARSET',     'utf8'            );

//include_once( 'index_image.php' );
?>
-------------------------------------------
*/

if ( !defined( 'STORAGE_BACKEND' ) )
    die( "No storage backend chosen.\n" );
ini_set( 'display_errors', 0 );
include_once( 'index_image_' . STORAGE_BACKEND . '.php' );
?>
