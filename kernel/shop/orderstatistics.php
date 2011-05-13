<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];
$year = $Params['Year'];
$month = $Params['Month'];

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "Year" ) )
{
    $year = $http->postVariable( "Year" );
}

if ( $http->hasPostVariable( "Month" ) )
{
    $month = $http->postVariable( "Month" );
}

if ( $http->hasPostVariable( "View" ) )
{
    $module->redirectTo( "/shop/statistics/" . $year . '/' . $month );
}

$statisticArray = eZOrder::orderStatistics( $year, $month );
$yearList = array();
$currentDate = new eZDate();
$currentYear = $currentDate->attribute( 'year' );
for ( $index = 0; $index < 10; $index++ )
{
    $yearList[] = $currentYear - $index;
}

$locale = eZLocale::instance();
$monthList = array();
for ( $monthIndex = 1; $monthIndex <= 12; $monthIndex++ )
{
    $monthList[] = array( 'value' => $monthIndex, 'name' => $locale->longMonthName( $monthIndex ) );
}

$tpl = eZTemplate::factory();
$tpl->setVariable( "year", $year );
$tpl->setVariable( "month", $month );
$tpl->setVariable( "year_list", $yearList );
$tpl->setVariable( "month_list", $monthList );
$tpl->setVariable( "statistic_result", $statisticArray );

$path = array();
$path[] = array( 'text' => ezpI18n::tr( 'kernel/shop', 'Statistics' ),
                 'url' => false );

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Statistics' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( "design:shop/orderstatistics.tpl" );

?>
