#!/usr/bin/env php
<?php

// create table lob_test (mykey int, myclob longblob);

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
$dbconn = mysql_connect('', 'fred', '') or die('Could not connect: ' . mysql_error());
mysql_select_db( 'trunk' ) or die('Could not select database');

mysql_query( 'DELETE FROM lob_test' );

$filedata = file_get_contents( $filename );
$len = strlen( $filedata );
echo "data len: $len\n";
$escaped_filedata = mysql_real_escape_string ( $filedata );

$query = "INSERT INTO lob_test (mykey,myclob) VALUES(1, '$escaped_filedata')";
mysql_query( $query );

$query = 'SELECT * FROM lob_test';

$time_start = microtime_float();
for ( $i = 0; $i < 100; $i++ )
{
    $result    = mysql_query( $query );
    $row       = mysql_fetch_array( $result, MYSQL_ASSOC );
    //$lob_value = pg_unescape_bytea( $row['myclob'] );
    $lob_value = $row['myclob'];
}
$time_end = microtime_float();

// Closing connection
mysql_close($dbconn);
mysql_free_result($result);


$elapsed = ( $time_end - $time_start );
$avg = $elapsed / $i;
$rate = 1/$avg;
echo sprintf( "Elapsed: %.3f sec (%d queries, %f sec average), rate is %.1f hits/sec.\n", $elapsed, $i, $avg, $rate );

?>
