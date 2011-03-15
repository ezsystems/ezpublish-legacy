<?php
/**
 * File containing the subtreeexpirycleanup.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

eZSubtreeCache::removeAllExpiryCacheFromDisk();

?>
