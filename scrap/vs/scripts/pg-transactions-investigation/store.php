#!/usr/bin/env php
<?

$port = 5432;

function say( $interactive, $msg )
{
    if ( !$interactive )
        return;

    $stdin = fopen('php://stdin', 'r');
    echo "$msg... ";
    fgets($stdin,1024);
    fclose($stdin);
}


function store( $dbconn, $interactive = false )
{
    $name      = 'var/storage/foo.txt';
    $name_hash = md5( $name );
    $scope     = 'misc';
    $size      = 1024;
    $mtime     = time();

    // Try storing file until the transaction succeeds.

    $i = 0;
    do
    {
        $success = true;
        say( $interactive, "About to BEGIN" );

        pg_query( 'BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE' );

        $resSelect = pg_query( "select * from ezdbfile where name_hash='$name_hash'" );

        if ( pg_num_rows( $resSelect ) ) // file exists
        {
            //echo "File exists.\n";

            $row = pg_fetch_array( $resSelect, null, PGSQL_ASSOC );
            $lob_id = $row['lob_id'];
            $file_id = $row['id'];

            // delete the dbfile table entry
            say( $interactive, "About to delete entry in dbfile" );
            $resDelete = @pg_query( "DELETE FROM ezdbfile WHERE id=$file_id" );
            if ( !$resDelete )
            {
                /*
                $errmsg = pg_last_error();
                pg_query( 'ROLLBACK' );
                if ( strstr( $errmsg, 'could not serialize access due to concurrent update' ) )
                {
                    printf( "NOTICE: REPEATING transaction, try #%d.\n", ++$i );
                    $success = false;
                    continue;
                }
                else
                {
                    printf( "ERROR: Failed to remove file #$file_id: '%s'\n", pg_last_error() );
                    return;
                }
                */


                $errmsg = pg_last_error();
                pg_query( 'ROLLBACK' );
                if ( strstr( $errmsg, 'could not serialize access due to concurrent update' ) )
                    printf( "NOTICE: Race condition.\n" );
                else
                    printf( "ERROR: Failed to remove file #$file_id: '%s'\n", pg_last_error() );
                $i++;
                return $i;

            }
            else
            {
                if ( $interactive ) printf( "%d rows deleted\n", pg_affected_rows( $resDelete ) );
                pg_free_result( $resDelete );
            }


            // delete current LOB
            say( $interactive, "About to delete LOB #$lob_id" );
            if ( !($resUnlink = pg_lo_unlink( $lob_id ) ) )
            {
                printf( "WARNING: Failed to remove LOB #$lob_id: %s\n", pg_last_error() );
                $status = pg_result_status($result,  PGSQL_STATUS_STRING );
                echo "status:\n";
                var_dump( $status );
                pg_query( 'ROLLBACK' );
                return;
            }

            // create new LOB
            say( $interactive, "LOB deleted. About to create new LOB" );
            $lob_id = pg_lo_create( $dbconn );
            $handle = pg_lo_open( $dbconn, $lob_id, 'w' );
            pg_lo_write( $handle, file_get_contents( '/etc/passwd' ) );
            pg_lo_close( $handle );
        }
        else // file does not exist
        {
            echo "File does not exist.\n";
        }

        // create new LOB
        say( $interactive, "About to create LOB" );
        $lob_id = pg_lo_create( $dbconn );
        $handle = pg_lo_open( $dbconn, $lob_id, 'w' );
        pg_lo_write( $handle, file_get_contents( '/etc/passwd' ) );
        pg_lo_close( $handle );

        // create dbfile table entry
        say( $interactive, "LOB #$lob_id created. About to insert an entry for it to dbfile." );
        $resInsert = pg_query( "INSERT INTO ezdbfile (name,name_hash,scope,size,mtime,lob_id) " .
                               "VALUES('$name','$name_hash','$scope',$size,$mtime,$lob_id)" );
        if ( !$resInsert )
        {
            printf( "WARNING: Failed to insert new ezdbfile entry: %s\n", pg_last_error() );
            pg_query( 'ROLLBACK' );
            return;
        }
        else
            pg_free_result( $resInsert );


        say( $interactive, "About to COMMIT" );
        pg_query( 'COMMIT' );
    } while( !$success );

    return $i;
}

$dbconn = pg_connect("host=heaven port=$port dbname=trunk_clustering") or die('Could not connect: ' . pg_last_error());
pg_query( 'SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL SERIALIZABLE' );

for( $i=0; $i<1000; $i++ )
{
#    echo "store $i\n";
    $rc = store( $dbconn, false );
    $stats[$rc]++;
    //usleep( 5000 );
}

krsort( $stats );
echo "stats:\n";
var_dump( $stats);

pg_close($dbconn);



?>
