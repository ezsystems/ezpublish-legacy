#!/usr/bin/env php
<?php

#################################################################################################
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
#################################################################################################
function write_lob( $lob_value )
{
    $fh = fopen( tempnam( '.', 'FOO' ), 'w' );
    fwrite( $fh, $lob_value );
    fclose( $fh );
}
#################################################################################################
function do_query( &$conn, $query )
{
    $rc = false;
    $statement = OCIParse ($conn, $query);
    if ( !$statement )
    {
        echo "OCIParse failed.\n";
        var_dump( ocierror($statement) );
    }
    else if ( !($rc=OCIExecute ($statement)) )
    {
        echo "OCIExecute failed.\n";
        var_dump( ocierror($statement) );
    }
#    echo "rc: "; var_dump( $rc );
#    echo "ocierror: "; var_dump( ocierror($statement) );

#    while( OCIFetchInto( $statement, $row, OCI_ASSOC + OCI_RETURN_LOBS + OCI_RETURN_NULLS )  )
#    {
#        foreach ( $row as $colname => $colval)
#        {
#            echo "$colname: [$colval]\n";
#        }
#        echo "--------------\n";
#    }
    OCIFreeStatement ($statement);
}
#################################################################################################

$filename = '/home/fred/downloads/lios2001.JPG';
#$filename = '/etc/passwd';

// create table lob_test (mykey int, myclob BLOB);

// Before running, create the table:
//     CREATE TABLE MYTABLE (mykey NUMBER, myclob CLOB);

$conn = ocilogon('scott', 'tiger', 'orcl');


do_query( $conn, 'DELETE FROM lob_test' );

$mykey = 12343;  // arbitrary key for this example;

$sql = "INSERT INTO lob_test (mykey, myclob)
        VALUES (:mykey, EMPTY_BLOB())
        RETURNING myclob INTO :myclob";

$stid = ociparse($conn, $sql);
$clob = ocinewdescriptor($conn, OCI_D_LOB);
ocibindbyname($stid, ":mykey", $mykey, 5);
ocibindbyname($stid, ":myclob", $clob, -1, OCI_B_BLOB);
ociexecute($stid, OCI_DEFAULT);

$clob->saveFile( $filename );

ocicommit($conn);

$query = 'SELECT myclob FROM lob_test WHERE mykey = :mykey';
$stid = ociparse ($conn, $query);


$time_start = microtime_float();
for ( $i = 0; $i < 100; $i++ )
{

    // Fetching CLOB data
    ocibindbyname($stid, ":mykey", $mykey, 5);
    ociexecute($stid, OCI_DEFAULT);

    ocifetchinto($stid, $row, OCI_ASSOC);
    $lob_value = $row['MYCLOB']->load();
    //write_lob( $lob_value );
}
$time_end = microtime_float();

ocifreedesc( $clob );
ocifreestatement( $stid );

$elapsed = ( $time_end - $time_start );
$avg = $elapsed / $i;
$rate = 1/$avg;
echo sprintf( "Elapsed: %.3f sec (%d queries, %f sec average), rate is %.1f hits/sec.\n", $elapsed, $i, $avg, $rate );


?>
