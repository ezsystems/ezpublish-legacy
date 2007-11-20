<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentclass.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/eznodeviewfunctions.php' );

//include_once( 'lib/ezutils/classes/ezhttptool.php' );

require_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

$tpl = templateInit();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module = $Params['Module'];
$LanguageCode = $Params['Language'];
$Offset = $Params['Offset'];
$Year = $Params['Year'];
$Month = $Params['Month'];
$Day = $Params['Day'];

// Check if we should switch access mode (http/https) for this node.
//include_once( 'kernel/classes/ezsslzone.php' );
eZSSLZone::checkNodeID( 'content', 'view', $NodeID );

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Offset )
    $Offset = (int) $Offset;
if ( $Year )
    $Year = (int) $Year;
if ( $Month )
    $Month = (int) $Month;
if ( $Day )
    $Day = (int) $Day;

if ( $NodeID < 2 )
    $NodeID = 2;

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$ini = eZINI::instance();
$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

if ( isset( $Params['ViewCache'] ) )
{
    $viewCacheEnabled = $Params['ViewCache'];
}
elseif ( $viewCacheEnabled && !in_array( $ViewMode, $ini->variableArray( 'ContentSettings', 'CachedViewModes' ) ) )
{
    $viewCacheEnabled = false;
}

$collectionAttributes = false;
if ( isset( $Params['CollectionAttributes'] ) )
    $collectionAttributes = $Params['CollectionAttributes'];

$validation = array( 'processed' => false,
                     'attributes' => array() );
if ( isset( $Params['AttributeValidation'] ) )
    $validation = $Params['AttributeValidation'];

// Check if read operations should be used
$workflowINI = eZINI::instance( 'workflow.ini' );
$operationList = $workflowINI->variableArray( 'OperationSettings', 'AvailableOperations' );
$operationList = array_unique( array_merge( $operationList, $workflowINI->variable( 'OperationSettings', 'AvailableOperationList' ) ) );
if ( in_array( 'content_read', $operationList ) )
{
    $useTriggers = true;
}
else
{
    $useTriggers = false;
}

$res = eZTemplateDesignResource::instance();
$keys = $res->keys();
if ( isset( $keys['layout'] ) )
    $layout = $keys['layout'];
else
    $layout = false;

$viewParameters = array( 'offset' => $Offset,
                         'year' => $Year,
                         'month' => $Month,
                         'day' => $Day,
                         'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$user = eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


//include_once( 'lib/ezutils/classes/ezmoduleoperationdefinition.php' );

$operationResult = array();

if ( $useTriggers == true )
{
    //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    //include_once( 'kernel/classes/eztrigger.php' );

    $operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                              'user_id' => $user->id(),
                                                                              'language_code' => $LanguageCode ), null, $useTriggers );
}

if ( ( array_key_exists(  'status', $operationResult ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
{
    switch( $operationResult['status'] )
    {
        case eZModuleOperationInfo::STATUS_HALTED:
        {
            if ( isset( $operationResult['redirect_url'] ) )
            {
                $Module->redirectTo( $operationResult['redirect_url'] );
                return;
            }
            else if ( isset( $operationResult['result'] ) )
            {
                $result = $operationResult['result'];
                $resultContent = false;
                if ( is_array( $result ) )
                {
                    if ( isset( $result['content'] ) )
                    {
                        $resultContent = $result['content'];
                    }
                    if ( isset( $result['path'] ) )
                    {
                        $Result['path'] = $result['path'];
                    }
                }
                else
                {
                    $resultContent = $result;
                }
                $Result['content'] = $resultContent;
            }
        } break;
        case eZModuleOperationInfo::STATUS_CANCELLED:
        {
            $Result = array();
            $Result['content'] = "Content view cancelled<br/>";
        } break;
    }
    return $Result;
}
else
{
    $localVars = array( "cacheFileArray", "NodeID",   "Module", "tpl",
                        "LanguageCode",   "ViewMode", "Offset", "ini",
                        "cacheFileArray", "viewParameters",  "collectionAttributes",
                        "validation" );
    if ( $viewCacheEnabled )
    {
        $user = eZUser::currentUser();

        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $NodeID, $Offset, $layout, $LanguageCode, $ViewMode, $viewParameters );

        $cacheFilePath = $cacheFileArray['cache_path'];

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
        $args = compact( $localVars );
        $Result = $cacheFile->processCache( array( 'eZNodeviewfunctions', 'contentViewRetrieve' ),
                                            array( 'eZNodeviewfunctions', 'contentViewGenerate' ),
                                            null,
                                            null,
                                            $args );
        return $Result;
    }
    else
    {
        $cacheFileArray = array( 'cache_dir' => false, 'cache_path' => false );
        $args = compact( $localVars );
        $data = eZNodeviewfunctions::contentViewGenerate( false, $args ); // the false parameter will disable generation of the 'binarydata' entry
        return $data['content']; // Return the $Result array
    }
}

// Looking for some view-cache code?
// Try the eZNodeviewfunctions class for enlightenment.
?>
