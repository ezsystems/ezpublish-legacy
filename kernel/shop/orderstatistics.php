<?php
//
//
// Created on: <01-Mar-2004 15:35:18 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file orderstatistics.php
*/


include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezorder.php" );
include_once( 'lib/ezlocale/classes/ezdate.php' );

$module =& $Params["Module"];
$year = $Params['Year'];
$month = $Params['Month'];

$http =& eZHttpTool::instance();
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

$statisticArray =& eZOrder::orderStatistics( $year, $month );
$yearList = array();
$currentDate = new eZDate();
$currentYear = $currentDate->attribute( 'year' );
for ( $index = 0; $index < 10; $index++ )
{
    $yearList[] = $currentYear - $index;
}

$monthList = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );

$tpl =& templateInit();
$tpl->setVariable( "year", $year );
$tpl->setVariable( "month", $month );
$tpl->setVariable( "year_list", $yearList );
$tpl->setVariable( "month_list", $monthList );
$tpl->setVariable( "statistic_result", $statisticArray );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Statistics' ),
                 'url' => false );

$Result = array();
$Result['path'] =& $path;

$Result['content'] =& $tpl->fetch( "design:shop/orderstatistics.tpl" );

?>
