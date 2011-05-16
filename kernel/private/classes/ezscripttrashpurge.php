<?php
/**
 * This class handles purging of trash items. It is used by both the script
 * and cronjob.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class eZScriptTrashPurge
{
    /**
     * eZCLI object used along running the operation.
     *
     * @var eZCLI
     */

    protected $cli;

    /**
     * Whether the operation should be quiet.
     *
     * @var bool
     */
    protected $quiet;

    /**
     * Whether memory monitoring is active.
     *
     * @var bool
     */
    protected $memoryMonitoring;

    /**
     * eZScript object used along running the operation.
     *
     * @var eZScript
     */
    protected $script;

    /**
     * Filename to use while logging memory monitoring.
     *
     * @var string
     */
    protected $logFile;

    /**
     * Constructor of eZScriptTrashPurge.
     *
     * @param eZCLI $cli The instance of eZCLI.
     * @param bool $quiet Whether the operation should be quiet or not.
     * @param bool $memoryMonitoring Set to true to turn on memory monitoring.
     * @param eZScript $script Optional eZScript object used while running.
     * @param string $logFile Log file to use for memory monitoring.
     */
    public function __construct( eZCLI $cli, $quiet = true, $memoryMonitoring = false, eZScript $script = null, $logFile = "trashpurge.log" )
    {
        $this->cli = $cli;
        $this->quiet = $quiet;
        $this->memoryMonitoring = $memoryMonitoring;
        $this->script = $script;
        $this->logFile = $logFile;
    }

    /**
     * Executes the purge operation
     *
     * @param int|null $iterationLimit Number of trashed objects to treat per iteration, use null to use a default value.
     * @param int|null $sleep Number of seconds to sleep between two iterations, use null to use a default value.
     *
     * @return bool True if the operation succeeded.
     */
    public function run( $iterationLimit = 100, $sleep = 1 )
    {
        if ( $iterationLimit === null )
        {
            $iterationLimit = 100;
        }

        if ( $sleep === null )
        {
            $sleep = 1;
        }

        if ( $this->memoryMonitoring )
        {
            eZLog::rotateLog( $this->logFile );
            $this->cli->output( "Logging memory usage to {$this->logFile}" );
        }

        $this->cli->output( "Purging trash items:" );

        $this->monitor( "start" );

        $db = eZDB::instance();

        // Get user's ID who can remove subtrees. (Admin by default with userID = 14)
        $userCreatorID = eZINI::instance()->variable( "UserSettings", "UserCreatorID" );
        $user = eZUser::fetch( $userCreatorID );
        if ( !$user )
        {
            $this->cli->error( "Cannot get user object with userID = '$userCreatorID'.\n(See site.ini[UserSettings].UserCreatorID)" );
            return false;
        }
        eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

        $trashCount = eZContentObjectTrashNode::trashListCount( false );
        if ( !$this->quiet )
        {
            $this->cli->output( "Found $trashCount object(s) in trash." );
        }
        if ( $trashCount == 0 )
        {
            return true;
        }
        if ( $this->script !== null )
        {
            $this->script->resetIteration( $trashCount );
        }

        while ( $trashCount > 0 )
        {
            $this->monitor( "iteration start" );
            $trashList = eZContentObjectTrashNode::trashList( array( 'Limit'  => $iterationLimit ), false );

            $db->begin();

            foreach ( $trashList as $trashNode )
            {
                $object = $trashNode->attribute( 'object' );
                $this->monitor( "purge" );
                $object->purge();
                if ( $this->script !== null )
                    $this->script->iterate( $this->cli, true );
            }

            if ( !$db->commit() )
            {
                $this->cli->output();
                $this->cli->error( 'Trash has not been emptied, impossible to commit the whole transaction' );
                return false;
            }

            $trashCount = eZContentObjectTrashNode::trashListCount( false );
            if ( $trashCount > 0 )
            {
                eZContentObject::clearCache();
                if ( $sleep > 0 )
                {
                    sleep( $sleep );
                }
            }
            $this->monitor( "iteration end" );
        }

        if ( !$this->quiet )
        {
            $this->cli->output( 'Trash successfully emptied' );
        }

        $this->monitor( "end" );

        return true;
    }

    /**
     * Log memory usage.
     *
     * @param string $text Text to use while logging.
     */
    private function monitor( $text )
    {
        if ( $this->memoryMonitoring )
        {
            eZLog::write( "mem [$text]: " . memory_get_usage(), $this->logFile );
        }
    }
}

?>
