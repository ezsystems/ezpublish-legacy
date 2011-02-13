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

// define the Expires HTTP header timeout in seconds.
// It's set to 6000 seconds (100 minutes) if EXPIRY_TIMEOUT is not defined.
// to work around an IE bug, it's recommended to not set it to a value lower
// than 600 seconds (10 minutes).
// Image urls contain the version number, it can be set to a very far future
// without any risk (one year below)
define( 'EXPIRY_TIMEOUT', 60 * 60 * 24 * 365 );

// If you use the DFS cluster, you also need to set the path for the shared directory:
define( 'MOUNT_POINT_PATH',    'var/nfsmount'    );

// If you use Oracle you might want to set these (see README.cluster in the ezoracle extension):
//define( 'USE_ETAG', true );
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
