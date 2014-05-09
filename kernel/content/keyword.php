<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$Alphabet = rawurldecode( $Params['Alphabet'] );

$Offset = $Params['Offset'];
$ClassID = $Params['ClassID'];
$viewParameters = array( 'offset' => $Offset, 'classid' => $ClassID );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'alphabet', $Alphabet );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/keyword.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Keywords' ),
                                'url' => false ) );

?>
