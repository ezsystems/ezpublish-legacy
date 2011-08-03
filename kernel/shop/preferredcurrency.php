<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$tpl = eZTemplate::factory();

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Preferred currency' ),
                                'url' => false ) );
$Result['content'] = $tpl->fetch( "design:shop/preferredcurrency.tpl" );


?>
