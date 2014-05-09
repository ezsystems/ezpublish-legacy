<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Redirect to visual module which is the correct place for this functionality
$module = $Params['Module'];

$visualModule = eZModule::exists( 'visual' );
if( $visualModule )
{
    return $module->forward( $visualModule, 'toolbar' );
}

?>
