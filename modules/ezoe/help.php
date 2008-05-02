<?php
//
// Created on: <2-May-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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

include_once( 'kernel/common/template.php' );
include_once( 'extension/ezoe/ezinfo.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$objectID      = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;

if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}

$object = eZContentObject::fetch( $objectID );

if ( !$object )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


$ezoeInfo = ezoeInfo::info();

$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'ezoe_name', $ezoeInfo['Name'] );
$tpl->setVariable( 'ezoe_version', $ezoeInfo['Version'] );
$tpl->setVariable( 'ezoe_copyright', $ezoeInfo['Copyright'] );
$tpl->setVariable( 'ezoe_license', $ezoeInfo['License'] );

// use persistent_variable like content/view does, sending parameters
// to pagelayout as a hash.
$tpl->setVariable( 'persistent_variable', array() );




// run template and return result
$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/help.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );
return $Result;


?>