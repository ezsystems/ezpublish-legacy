<?php
/** @var eZModule $module */
$module = $Params[ 'Module' ];
$limit = array( 'limit' => 100 );
$filter = isset( $_REQUEST[ 'filter' ] ) ? trim( $_REQUEST[ 'filter' ] ) : '';
$conditions = null;

if( $filter )
{
    $dbFilter = str_replace( '*', '%', $filter );
    $conditions = array( 'name' => array( 'like', $dbFilter ) );
}

$entries = eZSiteData::fetchObjectList(
    eZSiteData::definition(),
    null,
    $conditions,
    null,
    $limit
);

$count = eZSiteData::count(
    eZSiteData::definition(),
    $conditions
);

$tpl = eZTemplate::factory();
$tpl->setVariable( 'entries', $entries );
$tpl->setVariable( 'count', $count );
$tpl->setVariable( 'limit', $limit[ 'limit' ] );
$tpl->setVariable( 'filter', $filter );

$Result[ 'content' ] = $tpl->fetch( 'design:sitedata/list.tpl' );
$Result[ 'path' ] = array( array(
    'url' => false,
    'text' => ezpI18n::tr( 'kernel/sitedata', 'List' ),
) );

return $Result;
