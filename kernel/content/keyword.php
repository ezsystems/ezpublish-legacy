<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
