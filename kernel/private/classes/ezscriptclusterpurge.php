<?php
/**
 * This class handles purging of cluster items. It is used by both the script
 * and cronjob.
 *
 * Performance note: this procedure should be quite nice to the server memory
 * wise. It has been monitored as reaching about 5MB memory usage on a thousand
 * items, and ended up with an almost constant usage. No particular setting
 * should therefore be required to run it.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 * @property bool optDryRun
 * @property int optIterationLimit
 * @property int optIterationSleep
 * @property bool optMemoryMonitoring
 * @property array(string) optScopes
 * @property int optExpiry
 */
class eZScriptClusterPurge
{
    public function __construct()
    {
        $this->options = array(
            'dry-run' => false,
            'iteration-sleep' => 1,
            'iteration-limit' => 100,
            'memory-monitoring' => false,
            'scopes' => false,
            'expiry' => 2592000 // 60*60*24*30 = 30 days
        );
    }

    /**
     * Performs preliminary checks in order to ensure the process can be
     * started:
     * - does the active cluster handler require purging of binary files
     *
     * @return bool
     */
    public static function isRequired()
    {
        $clusterHandler = eZClusterFileHandler::instance();
        $result = $clusterHandler->requiresPurge();

        return $result;
    }

    /**
     * Executes the purge operation
     *
     * @todo Endless loop on fetch list. The expired items are returned over and over again
     */
    public function run()
    {
        $cli = eZCLI::instance();

        if ( $this->optMemoryMonitoring == true )
        {
            eZLog::rotateLog( self::LOG_FILE );
            $cli->output( "Logging memory usage to " . self::LOG_FILE );
        }

        if ( $this->optIterationSleep > 0 )
            $sleep = ( $this->optIterationSleep * 1000000 );
        else
            $sleep = false;

        $limit = array( 0, $this->optIterationLimit );

        $cli->output( "Purging expired items:" );

        self::monitor( "start" );

        // Fetch a limited list of purge items from the handler itself
        $clusterHandler = eZClusterFileHandler::instance();
        while ( $filesList = $clusterHandler->fetchExpiredItems( $this->optScopes, $limit, $this->optExpiry ) )
        {
            self::monitor( "iteration start" );
            foreach( $filesList as $file )
            {
                $cli->output( "- $file" );
                if ( $this->optDryRun == false )
                {
                    self::monitor( "purge" );
                    $fh = eZClusterFileHandler::instance( $file );
                    $fh->purge( false, false );
                    unset( $fh );
                }
            }
            if ( $sleep !== false )
                usleep( $sleep );

            // the offset only has to be increased in dry run mode
            // since each batch is not deleted
            if ( $this->optDryRun == true )
            {
                $limit[0] += $limit[1];
            }
            self::monitor( "iteration end" );
        }

        self::monitor( "end" );
    }

    public function __get( $propertyName )
    {
        switch( $propertyName )
        {
            case 'optDryRun':
            {
                return $this->options['dry-run'];
            } break;

            // no sleep in dry-run, it's not nap time !
            case 'optIterationSleep':
            {
                if ( $this->optDryRun == true )
                    return 0;
                else
                    return $this->options['iteration-sleep'];
            } break;

            case 'optIterationLimit':
            {
                return $this->options['iteration-limit'];
            } break;

            case 'optMemoryMonitoring':
            {
                return $this->options['memory-monitoring'];
            } break;

            case 'optScopes':
            {
                return $this->options['scopes'];
            } break;

            case 'optExpiry':
            {
                return $this->options['expiry'];
            } break;
        }
    }

    /**
     * @todo Add type & value check
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch( $propertyName )
        {
            case 'optDryRun':
            {
                $this->options['dry-run'] = $propertyValue;
            } break;

            case 'optIterationSleep':
            {
                return $this->options['iteration-sleep'] = $propertyValue;
            } break;

            case 'optIterationLimit':
            {
                $this->options['iteration-limit'] = $propertyValue;
            } break;

            case 'optMemoryMonitoring':
            {
                $this->options['memory-monitoring'] = $propertyValue;
            } break;

            case 'optScopes':
            {
                $this->options['scopes'] = $propertyValue;
            } break;

            case 'optExpiry':
            {
                $this->options['expiry'] = $propertyValue;
            } break;
        }
    }

    public function monitor( $text )
    {
        if ( $this->opt == true )
        {
            eZLog::write( "mem [$text]: " . memory_get_usage(), self::LOG_FILE );
        }
    }

    private $options = array();

    const LOG_FILE = 'clusterpurge.log';
}
?>
