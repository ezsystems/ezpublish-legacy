<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
