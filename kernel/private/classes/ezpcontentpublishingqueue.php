<?php
/**
 * File containing the ezpContentPublishingQueue class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
        return self::getNextItem();
    }

    /**
     * Class signals collection
     * @var ezcSignalCollection
     */
    protected static $signals = null;

    /**
     * Returns the next queued item, excluding those for which another version of the same content is being published.
     * @return ezpContentPublishingProcess|false
     */
    private static function getNextItem()
    {
        $pendingStatusValue = ezpContentPublishingProcess::STATUS_PENDING;
        $workingStatusValue = ezpContentPublishingProcess::STATUS_WORKING;

        $sql = <<< SQL
SELECT *
FROM
  ezpublishingqueueprocesses p,
  ezcontentobject_version v
WHERE p.status = $pendingStatusValue
  AND p.ezcontentobject_version_id = v.id
  AND v.contentobject_id NOT IN (
    SELECT v.contentobject_id
    FROM ezpublishingqueueprocesses p,
      ezcontentobject_version v
    WHERE
      p.ezcontentobject_version_id = v.id
      AND p.status = $workingStatusValue
  )
ORDER BY p.created, p.ezcontentobject_version_id ASC
SQL;
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => 1 ) );
        if ( count( $rows ) == 0 )
            return false;

        /** @var ezpContentPublishingProcess[] $persistentObjects */
        $persistentObjects = eZPersistentObject::handleRows( $rows, 'ezpContentPublishingProcess', true );
        return $persistentObjects[0];
    }

}
?>
