<?php
/**
 * Trash purge cronjob
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$purgeHandler = new eZScriptTrashPurge( eZCLI::instance() );
$purgeHandler->run();

?>
