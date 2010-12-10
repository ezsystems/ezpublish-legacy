<?php
/**
 * File containing the content/queued view.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

$tpl = eZTemplate::factory();

$module = $Params['Module'];

$pContentObjectId = $Params['ContentObjectID'];
$pVersion = $Params['version'];

$tpl->setVariable( 'contentObjectId', $pContentObjectId );
$tpl->setVariable( 'version', $pVersion );

$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/content', 'Content queued for publishing' ) ) );
$Result['content'] = $tpl->fetch( 'design:content/queued.tpl' );
return $Result;
?>