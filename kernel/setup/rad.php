<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/rad.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/setup', 'Rapid Application Development' ) ) );


?>
