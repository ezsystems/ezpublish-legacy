#!/usr/bin/env php
<?php

##############################################################################
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
##############################################################################

$filename = '/home/fred/downloads/lios2001.JPG';
#$filename = '/etc/passwd';
#$filename = '/bin/bash';


// create table lob_test (mykey int, myclob bytea);
// CREATE TABLE lob_test2 (mykey serial, lob_id int);

$filedata = file_get_contents( $filename );
#$dbconn = pg_connect("host=localhost dbname=trunk");
$dbconn = pg_connect("dbname=trunk");

// Remove all existing LOBs and references to them from lob_test2.
$result = pg_query( 'SELECT lob_id FROM lob_test2' );
while( $row = pg_fetch_row( $result ) )
{
    $lo_oid = $row[0];
    echo "Removing LO $lo_oid\n";
    pg_query( 'BEGIN');
    pg_query( "DELETE FROM lob_test2 WHERE lob_id=$lo_oid" );
    pg_lo_unlink($dbconn, $lo_oid);
    pg_query( 'COMMIT');
}

// Create a new LOB.
pg_query($dbconn, 'BEGIN');
$oid = pg_lo_create($dbconn);
echo "Creating LO $oid\n";
$handle = pg_lo_open($dbconn, $oid, 'w');
pg_lo_write($handle, $filedata);
pg_lo_close($handle);
pg_query( "INSERT INTO lob_test2 (lob_id) VALUES($oid)" );
pg_query($dbconn, 'COMMIT');

// Fetch the LOB multiple times.
$query = "SELECT * FROM lob_test2";

$time_start = microtime_float();

for ( $i = 0; $i < 100; $i++ )
{
    pg_query( 'BEGIN' );
    unset( $handle );
    $result    = pg_query( $query );
    $row       = pg_fetch_row( $result );
    $lob_oid   = (int) $row[1];
    //echo "LOB oid: $lob_oid\n";
    $handle    = pg_lo_open( $dbconn, $lob_oid, 'r' );
    //pg_lo_read_all( $handle );
    $lob_value = '';
    while( $chunk = pg_lo_read( $handle, 32768 ) )
        $lob_value .= $chunk;
    pg_lo_close( $handle );
    pg_query( 'COMMIT' );
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
