<?php
/**
 * File containing the content_publishing_queue.php cronjob script.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This cronjobs checks the content publishing queue for awaiting items, and spawns a child process if a slot is available
 * @package kernel
 */

$ini = eZINI::instance( 'content.ini' );

?>