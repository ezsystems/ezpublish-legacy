<?php
/**
 * File containing the notification.php cronjob
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$event = eZNotificationEvent::create( 'ezcurrenttime', array() );

$event->store();
$cli->output( "Starting notification event processing" );
eZNotificationEventFilter::process();

$cli->output( "Done" );

?>
