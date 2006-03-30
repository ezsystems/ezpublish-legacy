#!/usr/bin/env php
<?
$port = 5432;

$dbconn = pg_connect("port=$port dbname=trunk_clustering") or die('Could not connect: ' . pg_last_error());

pg_query( 'BEGIN' );
pg_query( 'TRUNCATE TABLE ezdbfile' );
pg_query( 'DELETE FROM pg_largeobject' );

// create LOB
$oid = pg_lo_create($dbconn);
$handle = pg_lo_open($dbconn, $oid, 'w');
pg_lo_write( $handle, file_get_contents( '/etc/passwd' ) );
pg_lo_close($handle);

// update dbfile
$name      = 'var/storage/foo.txt';
$name_hash = md5( $name );
$scope     = 'misc';
$size      = 1024;
$mtime     = time();
$lob_id    = $oid;
pg_query( "INSERT INTO ezdbfile (name,name_hash,scope,size,mtime,lob_id) " .
           "VALUES('$name','$name_hash','$scope',$size,$mtime,$lob_id)" );
pg_query( 'COMMIT' );

pg_close($dbconn);
?>