#!/usr/bin/env php
<?
/*
trunk_clustering=# EXPLAIN SELECT name FROM file WHERE name_hash='893fb14ac083bd91b5204fe0c4236969';
                                   QUERY PLAN
---------------------------------------------------------------------------------
 Index Scan using file_name_hash_idx on file  (cost=0.00..6.01 rows=1 width=145)
   Index Cond: (name_hash = '893fb14ac083bd91b5204fe0c4236969'::bpchar)
(2 rows)

trunk_clustering=# EXPLAIN SELECT name FROM file WHERE name='var/shop_site/cache/template-block/7/5/7/000099999.cache';
                                           QUERY PLAN
-------------------------------------------------------------------------------------------------
 Index Scan using file_name_idx on file  (cost=0.00..6.01 rows=1 width=145)
   Index Cond: ((name)::text = 'var/shop_site/cache/template-block/7/5/7/000099999.cache'::text)
(2 rows)

*/

##############################################################################
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
##############################################################################


$dbconn = pg_connect("dbname=trunk_clustering") or die('Could not connect: ' . pg_last_error());

$filename = 'var/shop_site/cache/template-block/7/5/7/000099999.cache';


$start_time = microtime_float();
for( $i=0; $i<1000; $i++ )
{
    $query = "SELECT name FROM file WHERE name_hash='" . md5( $filename ). "'";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
#    $row = pg_fetch_row($result);
#    echo $row[0] . "\n";
}
$end_time = microtime_float();

$elapsed = $end_time - $start_time;
printf( "Elapsed: $elapsed for $i queries.\n" );

pg_free_result($result);
pg_close($dbconn);
?>