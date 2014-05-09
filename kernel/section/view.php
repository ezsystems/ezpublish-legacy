<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$SectionID = $Params["SectionID"];
$Module = $Params['Module'];
$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$section = eZSection::fetch( $SectionID );

if ( !$section )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$tpl = eZTemplate::factory();

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "section", $section );

$Result = array();
$Result['content'] = $tpl->fetch( "design:section/view.tpl" );
$Result['path'] = array( array( 'url' => 'section/list',
                                'text' => ezpI18n::tr( 'kernel/section', 'Sections' ) ),
                         array( 'url' => false,
                                'text' => $section->attribute('name') ) );

?>
