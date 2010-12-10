<?php
/**
 * File containing the ezpContentPublishingQueueProcessor class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

/**
 * This class manages the publishing queue through ezpContentPublishingProcess persistent objects
 * @package kernel
 * @subpackage content
 */
class ezpContentPublishingQueueProcessor
{
    public function __construct()
    {
        $this->contentINI = eZINI::instance( 'content.ini' );
        $this->allowedPublishingSlots = $this->contentINI->variable( 'PublishingSettings', 'PublishingProcessSlots' );
    }

    /**
     * Singleton class loader
     * @return ezpContentPublishingQueueProcessor
     */
    public static function instance()
    {
        if ( !self::$instance instanceof ezpContentPublishingQueueProcessor )
        {
            self::$instance = new ezpContentPublishingQueueProcessor();
        }

        return self::$instance;
    }

    /**
     * Checks if a publishing slot is available
     * @return ezpContentPublishingQueueProcessor
     */
    private function isSlotAvailable()
    {
        return ( ezpContentPublishingProcess::currentWorkingProcessCount() < $this->allowedPublishingSlots );
    }

    /**
     * Main method: will execute as many publishing processes as allowed
     * @return int How many processes were actually launched
     */
    public function run()
    {
        $launched = 0;

        while ( 1 )
        {
            $publishingItem = ezpContentPublishingQueue::next();
            if ( $publishingItem !== false )
            {
                if ( !$this->isSlotAvailable() )
                {
                    echo "No slot is available\n";
                    sleep ( 1 );
                    continue;
                }
                else
                {
                    echo "Processing item #" . $publishingItem->attribute( 'id' ) . "\n";
                    ezpContentPublishingProcess::publish( $publishingItem );
                }
            }
            else
            {
                echo "Nothing to do, sleeping\n";
                sleep( 1 );
            }
        }
    }

    /**
     * @var eZINI
     */
    private $contentINI;

    /**
     * Allowed slots
     * @var int
     */
    private $allowedPublishingSlots = null;

    /**
     * Singleton instance of ezpContentPublishingQueueProcessor
     * @var ezpContentPublishingQueueProcessor
     */
    private static $instance = null;
}
?>