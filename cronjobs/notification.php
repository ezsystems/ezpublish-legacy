<?php
/**
 * File containing the notification.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$event = eZNotificationEvent::create( 'ezcurrenttime', array() );

$event->store();
$cli->output( "Starting notification event processing" );
eZNotificationEventFilter::process();

$cli->output( "Done" );

?>
