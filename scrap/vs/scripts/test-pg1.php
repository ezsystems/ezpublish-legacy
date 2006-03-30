#!/usr/bin/env php
<?php

// create table lob_test (mykey int, myclob bytea);

##############################################################################
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
##############################################################################
function write_lob( $lob_value )
{
    $fh = fopen( tempnam( '.', 'FOO' ), 'w' );
    fwrite( $fh, $lob_value );
    fclose( $fh );
}
##############################################################################

$filename = '/home/fred/downloads/lios2001.JPG';
#$filename = '/etc/passwd';

// Connecting, selecting database
#$dbconn = pg_connect("host=localhost dbname=trunk user=fred")
$dbconn = pg_connect("dbname=trunk")
    or die('Could not connect: ' . pg_last_error());


pg_query( 'DELETE FROM lob_test' );

$filedata = file_get_contents( $filename );
$escaped_filedata = pg_escape_bytea( $filedata );

$query = "INSERT INTO lob_test (mykey,myclob) VALUES(1, '$escaped_filedata')";
pg_query( $query );

$query = 'SELECT * FROM lob_test';

$time_start = microtime_float();
for ( $i = 0; $i < 100; $i++ )
{
    $result    = pg_query( $query );
    $row       = pg_fetch_row( $result );
    $lob_value = pg_unescape_bytea( $row[1] );
    //write_lob( $lob_value );
}
$time_end = microtime_float();

// Closing connection
pg_close($dbconn);
pg_free_result($result);


$elapsed = ( $time_end - $time_start );
$avg = $elapsed / $i;
$rate = 1/$avg;
echo sprintf( "Elapsed: %.3f sec (%d queries, %f sec average), rate is %.1f hits/sec.\n", $elapsed, $i, $avg, $rate );

?>
