<?php
/**
 * File containing the ezpContentPublishingQueue class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package
 */

/**
 * This class manages the content publishing queue. It accepts new items for input, and provides information about the
 * current queue
 *
 * @package kernel
 * @since 4.5
 */
class ezpContentPublishingQueue implements ezpContentPublishingQueueReaderInterface
{
    /**
     * Returns the class signals handler
     *
     * @return ezcSignalCollection
     */
    public static function signals()
    {
        if ( self::$signals == null )
        {
            self::$signals = new ezcSignalCollection();
        }
        return self::$signals;
    }

    /**
     * Initializes queue hooks from INI settings
     */
    protected final static function initHooks()
    {
        if ( isset( $init ) )
            return;

        static $init = true;

        $ini = eZINI::instance( 'content.ini' );

        self::attachHooks( 'preQueue', $ini->variable( 'PublishingSettings', 'AsynchronousPublishingPreQueueHooks' ) );
        self::attachHooks( 'postHandling', $ini->variable( 'PublishingSettings', 'AsynchronousPublishingPostHandlingHooks' ) );
    }

    public static function init()
    {
        self::initHooks();
    }

    /**
     * Attaches hooks to a signal slot
     * @param string $connector slot name (preQueue/postQueue)
     * @param array $hooks list of hooks (callbacks) to attach
     */

    protected final static function attachHooks( $connector, $hooksList )
    {
        if ( is_array( $hooksList ) && count( $hooksList ) )
        {
            foreach( $hooksList as $hook )
            {
                self::signals()->connect( $connector, $hook );
            }
        }
    }

    /**
     * Adds a draft to the publishing queue
     *
     * @param int $objectId
     * @param int $version
     *
     * @return ezpContentPublishingProcess
     */
    public static function add( $objectId, $version )
    {
        self::init();
        self::signals()->emit( 'preQueue', $version, $objectId );
        $processObject = ezpContentPublishingProcess::queue( eZContentObjectVersion::fetchVersion( $version, $objectId ) );

        return $processObject;
    }

    /**
     * Checks if an object exists in the queue, whatever the status is
     * @param int $objectId
     * @param int $version
     * @return bool
     */
    public static function isQueued( $objectId, $version )
    {
        $process = ezpContentPublishingProcess::fetchByContentObjectVersion( $objectId, $version );
        return ( $process instanceof ezpContentPublishingProcess );
    }

    /**
     * Returns the next processable item
     * @return ezpContentPublishingProcess The next object to process, or false if none is available
     */
    public static function next()
    {
        $queuedProcess = eZPersistentObject::fetchObjectList( ezpContentPublishingProcess::definition(),
            null,
            array( 'status' => ezpContentPublishingProcess::STATUS_PENDING ),
            array( 'created' => 'desc' ),
            array( 'offset' => 0, 'length' => 1 )
        );

        if ( count( $queuedProcess ) == 0 )
            return false;
        else
            return $queuedProcess[0];
    }

    /**
     * Class signals collection
     * @var ezcSignalCollection
     */
    protected static $signals = null;
}
?>