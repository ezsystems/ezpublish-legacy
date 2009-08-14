<?php
//
// Created on: <20-Feb-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//


/* For loading json data of a given object by object id */

include_once( 'extension/ezoe/classes/ezoeajaxcontent.php' );

$embedId         = 0;
$http            = eZHTTPTool::instance();

if ( isset( $Params['EmbedID'] ) && $Params['EmbedID'])
{
    $embedType = 'ezobject';
    if (  is_numeric( $Params['EmbedID'] ) )
        $embedId = $Params['EmbedID'];
    else
        list($embedType, $embedId) = explode('_', $Params['EmbedID']);

    if ( strcasecmp( $embedType  , 'eznode'  ) === 0 )
        $embedObject = eZContentObject::fetchByNodeID( $embedId );
    else
        $embedObject = eZContentObject::fetch( $embedId );
}

if ( !$embedObject )
{
   echo 'false';
   eZExecution::cleanExit();
}

// Params for node to json encoder
$params    = array('loadImages' => true);
$params['imagePreGenerateSizes'] = array('small', 'original');

// look for datamap parameter ( what datamap attribute we should load )
if ( isset( $Params['DataMap'] )  && $Params['DataMap'])
    $params['dataMap'] = array($Params['DataMap']);

// what image sizes we want returned with full data ( url++ )
if ( $http->hasPostVariable( 'imagePreGenerateSizes' ) )
    $params['imagePreGenerateSizes'][] = $http->postVariable( 'imagePreGenerateSizes' );
else if ( isset( $Params['ImagePreGenerateSizes'] )  && $Params['ImagePreGenerateSizes'])
    $params['imagePreGenerateSizes'][] = $Params['ImagePreGenerateSizes'];

// encode embed object as a json response
$json = eZOEAjaxContent::encode( $embedObject, $params );

// display debug as a js comment
echo "/*\r\n";
eZDebug::printReport( false, false );
echo "*/\r\n" . $json;

eZDB::checkTransactionCounter();
eZExecution::cleanExit();

?>