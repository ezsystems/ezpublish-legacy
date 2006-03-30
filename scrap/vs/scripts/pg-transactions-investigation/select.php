#!/usr/bin/env php
<?
##############################################################################
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
##############################################################################


$dbconn = pg_connect("dbname=trunk_clustering") or die('Could not connect: ' . pg_last_error());


#$query = "explain select true where exists( select * from ezdbfile where name='var/storage/foo.txt1' );";
#$query = "select distinct 1 from ezdbfile where name='var/storage/foo.txt1';";
$query = "select 1 from ezdbfile where name='var/storage/foo.txt1';";
$n = 1000;

$start_time = microtime_float();
for( $i=0; $i<$n; $i++ )
    pg_query($query) or die('Query failed: ' . pg_last_error());
$end_time = microtime_float();

$elapsed = $end_time - $start_time;
printf( "Elapsed: $elapsed for $i queries.\n" );

pg_close($dbconn);
?>