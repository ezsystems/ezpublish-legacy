<?php
/**
 * File containing the oauthadmin/list view definition
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$tpl = eZTemplate::factory();
$module = $Params['Module'];

$session = ezcPersistentSessionInstance::get();

$q = $session->createFindQuery( 'ezpRestClient' );
$q->where( $q->expr->eq( 'version', ezpRestClient::STATUS_PUBLISHED ) )
  ->orderBy( 'name', ezcQuerySelect::ASC );
$tpl->setVariable( 'applications', $session->find( $q, 'ezpRestClient' ) );

$tpl->setVariable( 'module', $module );

$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/oauthadmin', 'oAuth admin' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/oauthadmin', 'Registered REST applications' ) ) );

$Result['content'] = $tpl->fetch( 'design:oauthadmin/list.tpl' );

return $Result;
?>
