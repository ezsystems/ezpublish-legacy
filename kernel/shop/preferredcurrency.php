<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */


$tpl = eZTemplate::factory();

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Preferred currency' ),
                                'url' => false ) );
$Result['content'] = $tpl->fetch( "design:shop/preferredcurrency.tpl" );


?>
