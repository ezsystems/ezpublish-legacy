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

//include_once( 'index_image.php' );
?>
-------------------------------------------
*/

if ( !defined( 'STORAGE_BACKEND' ) )
    die( "No storage backend chosen.\n" );
include_once( 'index_image_' . STORAGE_BACKEND . '.php' );
?>
