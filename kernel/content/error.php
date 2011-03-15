<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$NodeID = $Params['NodeID'];
$Module = $Params['Module'];


$tpl = eZTemplate::factory();

$Module->setTitle( "Error 404 object " . $NodeID . " not found" );

$tpl->setVariable( "object", $NodeID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:content/error.tpl" );


?>
