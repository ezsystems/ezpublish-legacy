<?php
//
// Created on: <2-May-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2013 eZ Systems AS
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

// General popup dialog
// used for things like help and merge cells dialogs

$objectID      = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$dialog        = isset( $Params['Dialog'] ) ? trim( $Params['Dialog'] ) : '';

if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}

$object = eZContentObject::fetch( $objectID );
if ( !$object instanceof eZContentObject || !$object->canRead() )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


if ( $dialog === '' )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'Dialog' ) );
   eZExecution::cleanExit();
}





$ezoeInfo = eZExtension::extensionInfo( 'ezoe' );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'ezoe_name', $ezoeInfo['name'] );
$tpl->setVariable( 'ezoe_version', $ezoeInfo['version'] );
$tpl->setVariable( 'ezoe_copyright', $ezoeInfo['copyright'] );
$tpl->setVariable( 'ezoe_license', $ezoeInfo['license'] );
$tpl->setVariable( 'ezoe_info_url', $ezoeInfo['info_url'] );

// use persistent_variable like content/view does, sending parameters
// to pagelayout as a hash.
$tpl->setVariable( 'persistent_variable', array() );




// run template and return result
$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/' . $dialog . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );
return $Result;


?>