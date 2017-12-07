<?php

/*
 * Example how to run the script:
 *
 *   php update/run.php -u root -h localhost -d ezp
 *
 */

$options = MugoDbUpdater::readCommandLineOptions();

$dbUpdater = MugoDbUpdater::factory( $options );

$success = $dbUpdater->initConnection( $options );

if( $success )
{
    $schemaVersion = $dbUpdater->getDbSchemaVersion();

    if( $schemaVersion )
    {
        $sqlFiles = $dbUpdater->getSqlFiles( $schemaVersion );

        if( !empty( $sqlFiles ) )
        {
            foreach( $sqlFiles as $versionId => $sqlQueries )
            {
                $dbUpdater->log( 'Found DB updates for version: ' . $versionId );

                if( $versionId == $schemaVersion )
                {
                    if( !empty( $sqlQueries ) )
                    {
                        $dbUpdater->log( ' - Executing DB schema updates' );
                        $schemaVersion = $dbUpdater->executeSqlQueries( $sqlQueries, $schemaVersion );
                    }
                    else
                    {
                        $dbUpdater->log( ' - Could not find any queries in update file.' );
                    }
                }
                else
                {
                    $dbUpdater->log( ' - Updates do not match current DB schema version. Current DB schema version: ' . $schemaVersion );
                }
            }
        }
        else
        {
            $dbUpdater->log( 'No SQL un-applied files found' );
        }
    }

    $dbUpdater->closeConnection();
}
else
{
    echo 'Script parameters:' . "\n";
    echo '-h <db host>' . "\n";
    echo '-u <db user>' . "\n";
    echo '-p <db password>' . "\n";
    echo '-d <db name>' . "\n";
    echo '-t <db type: mysql | postgresql>' . "\n";
}

class MugoDbUpdater
{
    protected $connection;

    public function initConnection( $dbOptions )
    {
        if( $dbOptions[ 'dbname' ] )
        {
            $mysqli = new mysqli(
                $dbOptions[ 'host' ],
                $dbOptions[ 'user' ],
                $dbOptions[ 'pass' ],
                $dbOptions[ 'dbname' ]
            );

            if( !$mysqli->connect_error )
            {
                $this->connection = $mysqli;
                return true;
            }
            else
            {
                echo 'Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . "\n\n";
            }
        }
        else
        {
            echo 'Missing DB name parameter "d".' . "\n";
            echo "\n";
        }

        return false;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }

    public function getDbSchemaVersion()
    {
        $return = 0;

        $result = $this->connection->query( 'SELECT value FROM ezsite_data WHERE name="db-schema-version"' );

        if( !$result->num_rows )
        {
            $supportedVersions = array(
                '5.90.0alpha1',
                '5.4.0alpha1',
                '5.4.0',
                '6.12.0',
            );

            $result = $this->connection->query( 'SELECT value FROM ezsite_data WHERE name="ezpublish-version"' );
            $row = $result->fetch_row();
            $ezpVersion = $row[ 0 ];

            if( in_array( $ezpVersion, $supportedVersions ) )
            {
                $this->connection->query( 'INSERT INTO ezsite_data SET value = "1", name="db-schema-version"' );
                $return = 1;
            }
            else
            {
                echo 'This script is not working for your current ezp version.' . "\n";
                echo 'Your version: ' . $ezpVersion . "\n";
                echo 'Supported versions: ' . implode( ', ', $supportedVersions ) . "\n";
            }
        }
        else
        {
            $row = $result->fetch_row();
            $return = $row[ 0 ];
        }

        return $return;
    }

    public function getSqlFiles( $schemaVersion )
    {
        $sqlFiles = array();

        $directory = 'update/database/mysql/lovestack/';
        $files = scandir( $directory );

        foreach( $files as $file )
        {
            $fullPath = $directory . $file;

            if( is_file( $fullPath ) )
            {
                $fileInfo = pathinfo( $file );

                if(
                    $fileInfo[ 'extension' ] == 'sql' &&
                    (int) $fileInfo[ 'filename' ] >= $schemaVersion
                )
                {
                    $content = file_get_contents( $fullPath );
                    $this->remove_comments( $content );
                    $content = $this->remove_remarks( $content );

                    $sqlFiles[ $fileInfo[ 'filename' ] ] = $this->split_sql_file( $content, ';' );
                }
            }
        }

        ksort( $sqlFiles );

        return $sqlFiles;
    }

    public function log( $message )
    {
        echo $message . "\n";
    }

    /**
     * @param $sqlQueries
     * @param $schemaVersion
     * @return integer
     */
    public function executeSqlQueries( $sqlQueries, $schemaVersion )
    {
        $allQueriesOK = true;

        // In most cases the transaction will not fully rollback
        // due to implicit commits by mysql:
        // https://dev.mysql.com/doc/refman/5.7/en/implicit-commit.html
        $this->connection->autocommit( false );
        {
            foreach( $sqlQueries as $query )
            {
                $query = trim( $query );

                $result = $this->connection->query( $query );

                if( !$result )
                {
                    $this->log( 'Failed to execute query "' . trim( $query ) . '". Error:' );
                    $this->log( $this->connection->error );

                    $allQueriesOK = false;
                    break;
                }
            }
        }

        if( $allQueriesOK )
        {
            $this->connection->commit();
            $this->connection->autocommit( true );

            // increase DB schema version
            $schemaVersion = $schemaVersion + 1;
            $this->connection->query( 'UPDATE ezsite_data SET value="'. (int) $schemaVersion .'" WHERE name="db-schema-version"' );
            $this->log( 'New DB schema version is: ' . (int) $schemaVersion );
        }
        else
        {
            $this->connection->rollback();
        }

        return $schemaVersion;
    }

    /*
     * parse sql file
     */
    public function remove_comments( &$output )
    {
        $lines = explode("\n", $output);
        $output = "";

        // try to keep mem. use down
        $linecount = count($lines);

        $in_comment = false;
        for($i = 0; $i < $linecount; $i++)
        {
            if( preg_match("/^\/\*/", preg_quote($lines[$i])) )
            {
                $in_comment = true;
            }

            if( !$in_comment )
            {
                $output .= $lines[$i] . "\n";
            }

            if( preg_match("/\*\/$/", preg_quote($lines[$i])) )
            {
                $in_comment = false;
            }
        }

        unset($lines);
        return $output;
    }

    /**
     * remove_remarks will strip the sql comment lines out of an uploaded sql file
     *
     * @param $sql
     * @return string
     */
    public function remove_remarks( $sql )
    {
        $lines = explode("\n", $sql);

        // try to keep mem. use down
        $sql = "";

        $linecount = count($lines);
        $output = "";

        for ($i = 0; $i < $linecount; $i++)
        {
            if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
            {
                if (isset($lines[$i][0]) && $lines[$i][0] != "#")
                {
                    $output .= $lines[$i] . "\n";
                }
                else
                {
                    $output .= "\n";
                }
                // Trading a bit of speed for lower mem. use here.
                $lines[$i] = "";
            }
        }

        return $output;

    }

    /**
     * split_sql_file will split an uploaded sql file into single sql statements.
     * Note: expects trim() to have already been run on $sql.
     *
     * @param $sql
     * @param $delimiter
     * @return array
     */
    public function split_sql_file($sql, $delimiter)
    {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);

        // try to save mem.
        $sql = "";
        $output = array();

        // we don't actually care about the matches preg gives us.
        $matches = array();

        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++)
        {
            // Don't wanna add an empty string as the last thing in the array.
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
            {
                // This is the total number of single quotes in the token.
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                // Counts single quotes that are preceded by an odd number of backslashes,
                // which means they're escaped quotes.
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

                $unescaped_quotes = $total_quotes - $escaped_quotes;

                // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                if (($unescaped_quotes % 2) == 0)
                {
                    // It's a complete sql statement.
                    $output[] = $tokens[$i];
                    // save memory.
                    $tokens[$i] = "";
                }
                else
                {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i] . $delimiter;
                    // save memory..
                    $tokens[$i] = "";

                    // Do we have a complete statement yet?
                    $complete_stmt = false;

                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
                    {
                        // This is the total number of single quotes in the token.
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        // Counts single quotes that are preceded by an odd number of backslashes,
                        // which means they're escaped quotes.
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                        $unescaped_quotes = $total_quotes - $escaped_quotes;

                        if (($unescaped_quotes % 2) == 1)
                        {
                            // odd number of unescaped quotes. In combination with the previous incomplete
                            // statement(s), we now have a complete statement. (2 odds always make an even)
                            $output[] = $temp . $tokens[$j];

                            // save memory.
                            $tokens[$j] = "";
                            $temp = "";

                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        }
                        else
                        {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j] . $delimiter;
                            // save memory.
                            $tokens[$j] = "";
                        }

                    } // for..
                } // else
            }
        }

        return $output;
    }

    public static function readCommandLineOptions()
    {
        $dbOptions = array();

        $options = getopt( 'h:u:p:d:' );
        $dbOptions[ 'host' ] = isset( $options[ 'h' ] ) ? $options[ 'h' ] : 'localhost';
        $dbOptions[ 'pass' ] = isset( $options[ 'p' ] ) ? $options[ 'p' ] : '';
        $dbOptions[ 'dbname' ] = isset( $options[ 'd' ] ) ? $options[ 'd' ] : '';
        $dbOptions[ 'dbtype' ] = isset( $options[ 't' ] ) ? $options[ 't' ] : 'mysql';

        $dbOptions[ 'user' ] = $options[ 'u' ];
        if( !$dbOptions[ 'user' ] )
        {
            $processUser = posix_getpwuid( posix_geteuid() );
            $dbOptions[ 'user' ] = $processUser[ 'name' ];
        }


        return $dbOptions;
    }

    public static function factory( $parameters )
    {
        if( $parameters[ 'dbtype' ] == 'postgresql' )
        {
            return new MugoDbUpdaterPostgresql();
        }
        else
        {
            return new MugoDbUpdater();
        }
    }
}

/**
 * This is only a stub class, you would need to implement a few functions
 * in order to support PostgreSQL support
 *
 * Class MugoDbUpdaterPostgresql
 */
class MugoDbUpdaterPostgresql extends MugoDbUpdater
{
    public function initConnection( $dbOptions )
    {
        if( $dbOptions[ 'dbname' ] )
        {
            $this->connection = pg_connect( 'host='. $dbOptions[ 'host' ] .' port=5432 dbname='. $dbOptions[ 'dbname' ] .' user='. $dbOptions[ 'user' ] .' password=' . $dbOptions[ 'pass' ] );
        }
        else
        {
            echo 'Missing DB name parameter "d".' . "\n";
            echo "\n";
        }

        return false;
    }

    // ...
}